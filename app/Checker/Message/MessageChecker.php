<?php
namespace App\Checker\Message;

use Illuminate\Support\Facades\Log;

abstract class MessageChecker {
    protected $successor;

    public function setNext(MessageChecker $messageChecker) {
        $this->successor = $messageChecker;

        return $messageChecker;
    }

    public function check(&$toBotMessage) {
        if (!$this->successor) {
            Log::channel('single')->info('Error. Did not found answers to bot message');   
            $toBotMessage->answers = null;
            $toBotMessage->need_send_to_email = 0;
            $toBotMessage->command = null;

            return;
        }
        $this->successor->check($toBotMessage);
    }

    protected function notEmpty($answers): bool {
        return  isset($answers) && !empty($answers) && !($answers === null);
    }
}
?>