<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [  
        'body', 
        'type',
        'senderName',
        'author',
        'time',
        'chatId',
        'bot_id',
        'need_send_to_email',
        'answers',
        'command',
        'was_sent_to_email',
        'filepath',
        'filename',
        'type',
        'caption',
        'lat',
        'lng',
        'address'
    ];
    
    public function getAnswersArrayAttribute(): array 
    {
        $strAnswers = str_replace(" ", "", $this->answers);
        return explode(",", $strAnswers);
    }

    public function getBodyHtmlAttribute()
    {
        switch($this->type_answer) {
            case "file":
                return "Файл: $this->filename, \n"
                    ."Подпись: $this->caption";
            case "location":
                return "Ширина: $this->lng, \n"
                    ."Долгота: \n $this->lng, "
                    ."Текст под сообщением: $this->address";
            default:
                return \Parsedown::instance()->text($this->body);
        }
    }

/* 
    public 
    public function answer()
    {
        return $this->hasOne(Answer::class);
    } */
}