<?php
namespace App\Checker\SavedMessages;

use App\{Message, OldMessage, Answer};
use Illuminate\Support\Facades\{Mail, Log};

abstract class CheckerMessages
{
    public $email = array();

    public function __construct() {
        $this->email['from_address'] = config('mail.from.address') ?? env('MAIL_FROM_ADDRESS');
        $this->email['from_name'] = config('mail.from.name') ?? env('MAIL_FROM_NAME');
    }

    public function checkSavedMessages() {
    }

    public function checkAuthors($authors) {

        if ( !is_iterable($authors) ) return array();

        foreach($authors as $author) {
            $messages = Message
                ::where('chatId', 'LIKE', '%' .$author->chatId .'%')
                ->where('created_at', $author->condition, $author->max_date);
            
/*                 $temp1 = $messages->get()->count(); 
                $temp2 = $messages->get()->toArray(); */

            // Нет сообщений с более ранней датой
            if ( $messages->get()->count() > 0 ) {                  

                //Если есть содержимое для отправки, чтобы не пустое письмо
                $messagesWithBody = \DB::table('messages')
                    ->where('chatId', 'LIKE', '%' .$author->chatId .'%')
                    ->where('created_at', $author->condition, $author->max_date)
                    ->where(function($query) {
                        $query->whereNull('command')->orWhere('command', '');
                    })
                    ->get();

                $this->moveToHistory(
                    $author->chatId,
                    $author->max_date,
                    $author->condition
                );

                if ( $messagesWithBody->count() > 0 ) {
                    $this->sendToEmail( $author->chatId );
                }
            }
        }
    }

    public function moveToHistory(string $chatId, string $lastDate, string $condition = "<") {

        $willDeleteMessages = null;
        $idsForDelete = null;

        $willDeleteMessages = Message::where('chatId', $chatId)
            ->where('created_at', $condition, $lastDate)
            ->oldest()
            ->get();
        
        if ( count($willDeleteMessages) > 0 ) {

            $idsForDelete = $willDeleteMessages->pluck('id')->toArray();

            $arrayForInsert = array();
            array_filter($willDeleteMessages->toArray(), function($message) use(&$arrayForInsert) {
                unset($message['id']);
                $arrayForInsert[] = $message;
            });
            
            if (isset($arrayForInsert) && count($arrayForInsert) > 0) {
                OldMessage::insert($arrayForInsert);
            }
    
            if (isset($idsForDelete) && is_array($idsForDelete)) {
                \DB::table('messages')->whereIn('id', $idsForDelete)->delete();
            }
        }
    }

    public function sendToEmail( $chatId )
    {
        $messagesToShow = [];
        $needToSendMessages = \DB::table('old_messages')
            ->where('chatId', $chatId)
            ->where('need_send_to_email', 1)
            ->where('was_sent_to_email', 0)
            ->oldest()
            ->get();
        
        if ( $needToSendMessages->count() > 0) {

            foreach($needToSendMessages as $curMessage)
            {
                $arrayAnswers = explode(',', $curMessage->answers);
                //if ($curMessage->answers[0] == ',') $strAnswers = substr($strAnswers, 1);

                $bodies = Answer::whereIn('answer_id', $arrayAnswers)
                    ->orderBy( \DB::raw("FIELD(answer_id, $curMessage->answers)") )
                    //->pluck('body', 'answer_id')
                    ->latest()
                    ->get()
                    ->map(function($item) {
                        switch($item->type) {
                            case 'file':
                                return "Файл: $item->filepath Подпись под файлом: $item->caption";
                            case 'location':
                                return "Локация: $item->lat, $item->lng. Текст под сообщением: \"$item->address\"";
                            default:
                                return $item->body;
                        }
                    })
                    ->toArray();

                $messagesToShow[] = [
                    'body_html' => $curMessage->body,
                    'show' => implode("<br>", $bodies)  
                ];
            }

            try {
                $user = \DB::table('users')->get()->last();
                $emails = explode(',', str_replace(' ', '', $user->emails_to_send));
             
                Mail::send(
                    'emails.message', 
                    compact('messagesToShow'), 
                    function($message) use ($emails) {
                        $message->to($emails, $this->email['from_name'])->subject($this->email['from_address']);
                    });

                OldMessage::where('chatId', $chatId)
                    ->where('need_send_to_email', 1)
                    ->where('was_sent_to_email', 0)
                    ->update(['was_sent_to_email' => 1]);
                
            } catch (Exception $e) {
                Log::channel('single')->info('Error send mail: ' .$e->getMessage());          
            }
        }
    }

}
?>