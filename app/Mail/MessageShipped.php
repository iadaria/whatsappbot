<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\OldMessage;

class MessageShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $messagesToShow = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $chatId)
    {
        $sendingMessages = \DB::table('old_messages')
            ->where('chatId', $chatId)
            ->where('need_send_to_email', 1)
            ->where('was_sent_to_email', 0)
            ->oldest()
            ->get();

        foreach($sendingMessages as $curMessage)
        {
            $arrayAnswers = explode(',', $curMessage->answers);
            //if ($curMessage->answers[0] == ',') $strAnswers = substr($strAnswers, 1);

            $bodies = \App\Answer::whereIn('answer_id', $arrayAnswers)
                ->orderBy( \DB::raw("FIELD(answer_id, $curMessage->answers)") )
                ->pluck('body', 'answer_id')
                ->toArray();

            $this->messagesToShow[] = [
                'body_html' => $curMessage->body,
                'show' => implode("<br>", $bodies)  
            ];
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $messagesForShow = $this->messagesToShow;

        return $this->view('emails.message', compact('messagesForShow'));
    }
}
