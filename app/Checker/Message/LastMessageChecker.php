<?php
namespace App\Checker\Message;

use App\{Message, Answer};

class LastMessageChecker extends MessageChecker {

    public function check(&$toBotMessage) {

        $lastMessage = Message::select('answers', 'need_send_to_email')
            ->where('chatId', $toBotMessage->chatId)
            ->latest()
            ->first();

        if ($this->notEmpty($lastMessage)) {
            $strAnswers = $lastMessage['answers'];

            $answers = explode(',', $strAnswers);

            $arrayAnswers = Answer::whereIn('answer_id', $answers)
                ->whereNull('command')
                ->orderByRaw("FIELD(answer_id,$strAnswers)")
                ->pluck('answers')
                ->filter()
                ->all();
            
            $strAnswers = implode(',', $arrayAnswers);

            if ($this->notEmpty($strAnswers)) {
                $toBotMessage->answers = $strAnswers;
                $toBotMessage->need_send_to_email = $lastMessage['need_send_to_email'];
                $toBotMessage->command = '';

                return;
            }         
        }
        
        parent::check($toBotMessage);
    }
}

?>