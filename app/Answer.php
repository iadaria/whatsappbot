<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [ 
        'answer_id', 
        'user_id',
        'body', 
        'answers', 
        'need_send_to_email',
        'was_sent_to_email',
        'command',
        'filepath',
        'filename',
        'type',
        'caption',
        'lat',
        'lng',
        'address',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getBodyHtmlAttribute()
    {
        switch($this->type) {
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

    public function getCommands()
    {
        return $this->select('command');
    }
}