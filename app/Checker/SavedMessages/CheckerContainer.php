<?php
namespace App\Checker\SavedMessages;

class CheckerContainer extends CheckerMessages{

    /**
     * @var CheckerMessagesInterface[]
     */
    protected $checkers = [];
    public function checkSavedMessages() {
        foreach($this->checkers as $checker) {
            $checker->checkSavedMessages();
        }
    }

    public function addChecker(CheckerMessages $checker) {
        $this->checkers[] = $checker;
    }
}
?>