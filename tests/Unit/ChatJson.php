<?php

class ChatJson
{
    public $instanceId;
    public $messages;
    
}

class Message
{
    public $id;
    public $body;
    public $type;
    public $senderName;
    public $fromMe;
    public $author;
    public $time;
    public $chatId;
    public $messageNumber;
}

?>