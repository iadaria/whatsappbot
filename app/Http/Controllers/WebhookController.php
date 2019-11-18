<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\{Collection, Carbon};
use App\{Answer, Message, OldMessage, ApiRequest};
use App\Checker\Message\{CommandChecker, LastMessageChecker, DefaultChecker, TestChecker};
use App\Checker\SavedMessages\{CheckerContainer, EndAnswerChecker, SavedCommandChecker, TimeMessagesChecker};
use Carbon\Traits\Timestamp;

class WebhookController extends Controller
{

    public $checkBotMessage = null;
    public $checkersSavedMessages = null;
    public $apiRequest = null;

    public function __construct()
    {
       // session_start();
        $this->middleware('guest');

        $this->apiRequest = new ApiRequest();

        //$this->checkBotMessage = new TestChecker($this->apiRequest);
        $this->checkBotMessage = new CommandChecker($this->apiRequest);
        $this->checkBotMessage
            ->setNext( new LastMessageChecker() )
            ->setNext( new DefaultChecker() );
            //->setNext( new TestChecker() ) 

        //Проверяем сохраненные новые сообщения для переноса в историю и отправки email
        $this->checkersSavedMessages = new CheckerContainer();
        //По последней команде
        $this->checkersSavedMessages->addChecker( new SavedCommandChecker() );
        //По последнему ответу
        $this->checkersSavedMessages->addChecker( new EndAnswerChecker() );
        //Прошло более 20 минут
        $this->checkersSavedMessages->addChecker( new TimeMessagesChecker() );
    }

    public function index(Request $request)
    {
        $input = json_decode($request->getContent());
        if ( isset ($input->messages) ) {
            foreach( $input->messages as $message ) {
                if ( !$message->fromMe) {
                    $this->execCommand($message);
                }
            }
        }
    }

    private function execCommand($botMessage) {
        $answersForSend = "";

       $this->checkBotMessage->check($botMessage);

       $this->save( $botMessage );
    
        if ( !empty($botMessage->answers) ) {
            $answersForSend = $this->getAnswerMessages($botMessage->answers);
            $this->sendMessagesToUser($answersForSend, $botMessage->chatId);

            $this->checkersSavedMessages->checkSavedMessages();
        }
    }
    
    private function save($botMessage) {

        $botMessage->bot_id = $botMessage->id;          
        unset(
            $botMessage->id,
            $botMessage->fromMe,
            $botMessage->messageNumber
        );

        Message::create((array)$botMessage);
    }

    protected function getAnswerMessages(string $strAnswers): Collection {
        $answers = explode(',', $strAnswers);

        if ($strAnswers[0] == ',') $strAnswers = substr($strAnswers, 1);

        $answersForSend = \DB::table('answers')
            ->whereIn('answer_id', $answers)
            ->orderBy( \DB::raw("FIELD(answer_id, $strAnswers)"));
        
        return $answersForSend->get();
    }

    private function sendMessagesToUser(Collection $answersForSend, string $chatId) {
        $bodies = $answersForSend->where('type', 'text')
            ->pluck('body', 'answer_id')
            ->toArray();

        if ( count($bodies) > 0 ) {
            $messageForShow = implode("\n", $bodies);
            $this->apiRequest->sendMessage( $chatId, $messageForShow );
        }

        //$chatId = $botMessage->chatId;
        $answersForSend->where('type', 'file')
            ->map(function($item) use($chatId) {
                $this->apiRequest->sendFile( 
                    $chatId, 
                    $item->filepath,
                    $item->filename,
                    $item->caption
                );
            });

        $answersForSend->where('type', 'location')
            ->map(function($item) use($chatId) {
                $this->apiRequest->sendLocation( 
                    $chatId, 
                    $item->lat,
                    $item->lng,
                    $item->address
                );
            });
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
}

            
/*                     Queue::create([
                        'chatId' => $message->chatId,
                        'body' => $message->body,
                        'author' => $message->author
                    ]); */
                  /*   isset($_SESSION[$message->chatId]) 
                        ? sleep(5)
                        : $_SESSION[$message->chatId] = Carbon::now()->timestamp; */
                   // \DB::transaction(function() use($message) {
                    //    \DB::raw('LOCK TABLE Messages READ');
                        //\DB::table('messages')->where('chatId', $message->chatId)->lockForUpdate()->get();
                    //$this->execCommand( $message );

                    //unset($_SESSION[$message->chatId]);
                    //    \DB::raw('UNLOCK TABLES');
                  //  });      