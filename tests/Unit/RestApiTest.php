<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function GuzzleHttp\json_encode;

class RestApiTest extends TestCase
{
  protected $json;
  protected $message;

  public function setUp() : void
  {
    $this->message = [
      "id"=> "79143528288@c.us_DF38E6A25B42CC8CCE57EC40F",
      "body"=> "1",
      "type"=> "chat",
      "senderName"=> "Ivanov Ivan",
      "fromMe"=> false,
      "author"=> "79143528288@c.us",
      "time"=> 1504208593,
      "chatId"=> "79143528288@c.us",
      "messageNumber"=> 100
    ];

    $this->json = [
      "instanceId" => "51777",
      'messages' => [$this->message]
    ];

    parent::setUp();
  }  
  
  /** @test */
  public function not_full()
  {
    $json = $this->json;

    $json['messages'][0]['body'] = "1";
    //$json['messages'][] = $message;
    $this->sendRequest($json);
    
    $json['messages'][0]['body']= "One Ones";
    $this->sendRequest($json);

    $json['messages'][0]['body']= "foto1";
    $this->sendRequest($json);

    $json['messages'][0]['body']= "foto2";
    $this->sendRequest($json);

    $this->assertTrue(true);
  }


  private function sendRequest($json) {
    $client = new \GuzzleHttp\Client();    
    $response = $client->post('http://cw21459.tmweb.ru'.'/webhook', ['body' => json_encode($json)]);
    //$response = $client->post('bot.test/webhook', ['body' => json_encode($json)]);
    $status = $response->getStatusCode();
    $body = $response->getBody();
  }

    /** @test */
    public function is_full()
    {
      $json = $this->json;
      $message = $this->message;
      $message['author'] = "79143528288@c.us";
      $message['chatId'] = "79143528288@c.us";
      $message['senderName'] = "Darya Yakimova";
  
      $json['messages'][] = $message;
      
      $message['body'] = "Darya Yakimova";
      $json['messages'][] = $message;
  
      $message['body'] = "foto1";
      $json['messages'][] = $message;
  
      $message['body'] = "foto2";
      $json['messages'][] = $message;
  
      $this->sendRequest($json);
      $this->assertTrue(true);
    }
  

  /** @test */
  public function is_full_and_word()
  {
    $json = $this->json;
    $message = $this->message;
    $message['author'] = "79143528288@c.us";
    $message['chatId'] = "79143528288@c.us";
    $message['senderName'] = "Darya Yakimova";

    $json['messages'][] = $message;
    
    $message['body'] = "Darya Yakimova";
    $json['messages'][] = $message;

    $message['body'] = "foto1";
    $json['messages'][] = $message;

    $message['body'] = "foto2";
    $json['messages'][] = $message;

    $message['body'] = "hellow";
    $json['messages'][] = $message;

    $this->sendRequest($json);

    $this->assertTrue(true);
  }

  

  /**  */
  public function create_command_zero() {
    $message = [
      "id"=> "79143528288@c.us_DF38E6A25B42CC8CCE57EC40F",
      "body"=> "hellow",
      "type"=> "chat",
      "senderName"=> "Ivanov Ivan",
      "fromMe"=> false,
      "author"=> "79143528288@c.us",
      "time"=> 1504208593,
      "chatId"=> "79143528288@c.us",
      "messageNumber"=> 100
    ];

    $json = [
      "instanceId" => "51777",
      'messages' => []
    ];
    $json['messages'][] = $message;

   
    $this->sendRequest($json);

    $this->assertTrue(true);
  }

  /** @test */
  public function is_five_command_keep_one()
  {
    $json = $this->json;
    $message = $this->message;
    $message['author'] = "79143528288@c.us";
    $message['chatId'] = "79143528288@c.us";
    $message['senderName'] = "Darya Yakimova";

    $json['messages'][] = $message;
    
    $message['body'] = "1";
    $json['messages'][] = $message;

    $message['body'] = "2";
    $json['messages'][] = $message;

    $message['body'] = "3";
    $json['messages'][] = $message;

    $this->sendRequest($json);

    $this->assertTrue(true);
  }
}