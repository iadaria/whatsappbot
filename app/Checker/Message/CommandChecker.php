<?php
namespace App\Checker\Message;

use App\Answer;

class CommandChecker extends MessageChecker {

    public function check(&$toBotMessage) {
        $text = explode( ' ', trim( $toBotMessage->body ) );
        $command = mb_strtolower( $text[0], 'UTF-8' );

        if ( $this->isCommand( $command ) ) {
            $answer = Answer::select('answers', 'need_send_to_email', 'command')
                ->where('command', $command)
                ->latest()
                ->first()
                ->toArray();


            if ($this->notEmpty($answer)) {
                $toBotMessage->answers = $answer['answers'];
                $toBotMessage->need_send_to_email = $answer['need_send_to_email'];
                $toBotMessage->command = $answer['command'];

                return;
            }
        }
        parent::check($toBotMessage);
    }

    private function isCommand(string $command) {
        return Answer::where('command', $command)->count() > 0;
    }
}