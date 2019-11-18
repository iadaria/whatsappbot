<?php
namespace App\Checker\Message;

use App\Answer;

class DefaultChecker extends MessageChecker {
    public function check(&$toBotMessage) {

        $strAnswers = Answer::select('answers')
            ->where('answer_id', '0')
            ->value('answers');

        if ($this->notEmpty($strAnswers)) {
                $toBotMessage->answers = $strAnswers;
                $toBotMessage->need_send_to_email = 0;
                $toBotMessage->command = 0;

                return;
        }
        
        parent::check($toBotMessage);
    }
}
?>