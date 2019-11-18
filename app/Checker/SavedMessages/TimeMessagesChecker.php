<?php
namespace App\Checker\SavedMessages;

use DateInterval;
use DateTime;

class TimeMessagesChecker extends CheckerMessages {
    public function checkSavedMessages()
    {
        $dateNow = new DateTime();
        $dateMinus20min = $dateNow->sub(new DateInterval('PT20M'));

        $authorOld = \DB::table('messages')
            ->select('chatId', \DB::raw('MAX(created_at) as max_date'))
            ->where('created_at', '<=',  $dateMinus20min)
            ->groupBy('chatId')
            ->get()
            ->map(function($item, $key) {
                $item->condition = '<=';
                return $item;
                });
                
        if ($authorOld->count() > 0) {
            $this->checkAuthors($authorOld);
        }
    }
}
?>