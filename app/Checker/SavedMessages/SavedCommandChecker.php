<?php 
namespace App\Checker\SavedMessages;

use App\Message;

class SavedCommandChecker extends CheckerMessages {
    public function checkSavedMessages(){
        $authorsBeginNew = Message::select('chatId', \DB::raw('MAX(created_at) as max_date'))
            ->whereNotNull('command')
            ->where('command', '!=', '')
            ->groupBy('chatId')
            ->get()
            ->map(function($item, $key) {
                $item->condition = '<';
                return $item;
            });

        if ($authorsBeginNew->count() > 0) {
            $this->checkAuthors($authorsBeginNew);
        }
    }
}

?>