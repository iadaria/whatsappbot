<?php
namespace App\Checker\SavedMessages;

use App\Answer;

class EndAnswerChecker extends CheckerMessages {
    public function checkSavedMessages()
    {
         // По конечным ответам
        $arr = $this->getAllEndAnswers();
        //$variants = array();
        //$this->depth_picker($arr, "", $variants);
        
        $authorsEnd = \DB::table('messages')
            ->select('chatId', \DB::raw('MAX(created_at) as max_date'), 'answers')
            //>whereIn('answers', $variants)
            ->groupBy('chatId', 'answers')
            ->get()
            ->map(function($item) use ($arr) {
                $answers = !empty($item->answers) ? explode(',', $item->answers) : array();;
                if ( count(array_diff($answers, $arr)) == 0 && count($answers) > 0 )
                {
                    $item->condition = '<=';
                    return $item;
                }
            })
            ->filter();

/*         $authorsEnd = \DB::table('messages')
             ->select('chatId', \DB::raw('MAX(created_at) as max_date'))
             ->whereIn('answers', $variants)
             ->groupBy('chatId')
             ->get()
             ->map(function($item, $key) {
                 $item->condition = '<=';
                 return $item;
            }); */
        if ($authorsEnd->count() > 0) {
            $this->checkAuthors($authorsEnd);
        }
    }

    private function getAllEndAnswers() {
        $endAnswers = "";
        $endAnswers = Answer::select('id', 'answer_id')
            ->where(function ($query) {
                $query->whereNull('command')
                ->orWhere('command', '');
            })
            ->where(function ($query) {
                $query->whereNull('answers')
                ->orWhere('answers', '');
            })
            ->pluck('answer_id')
            ->toArray();

        return $endAnswers;
    }

    private function depth_picker($arr, $temp_string, &$collect) {
        if ($temp_string != "") 
            $collect []= substr($temp_string, 1);
    
        for ($i=0; $i<sizeof($arr);$i++) {
            $arrcopy = $arr;
            $elem = array_splice($arrcopy, $i, 1); // removes and returns the i'th element
            if (sizeof($arrcopy) > 0) {
                $this->depth_picker($arrcopy, $temp_string ."," . $elem[0], $collect);
            } else {
                $collect []= substr($temp_string. "," . $elem[0], 1);
            }   
        }   
    }

}
?>