<?php
namespace App;

use Illuminate\Support\Facades\{Storage, File};

class ApiRequest {
    private $APIurl = '';
    private $token = '';

    public function __construct()
    {
        $this->APIurl = config('value.chatapiurl') ?? env('CHAT_API_URL', ''); //TODO ?? env()
        $this->token = config('value.chatapitoken') ?? env('CHAT_API_TOKEN', '');
    }

    public function sendMessage( $chatId, $text ) 
    {
        $data = array(
            'chatId' => $chatId,
            'body' => $text
        );
        $this->sendRequest( 'sendMessage', $data );
    }

    public function sendFile( $chatId, $filepath, $filename, $caption ) {
        
/*         $filepath2 = storage_path('app/public/doc/' .$filename);
        $filecontent = File::get($filepath2);
        $content_type = mime_content_type($filepath2);
        $filebase64 = base64_encode($filecontent); */
        
        //$filepath = request()->root() .'/' .$filepath;

        $data = array(
            'chatId' => $chatId,
            'body' => str_replace('bot.test', 'transittea.ru', $filepath),
            'filename' => $filename,
            'caption' => ''
        );

        $this->sendRequest( 'sendFile', $data );
    }

    public function sendLocation( $chatId, $lat, $lng, $address ) {
        $data = array(
            'chatId' => $chatId,
            'lat' => $lat,
            'lng' => $lng,
            'address' => $address //"for use two line point simbol \n"
        );

        $this->sendRequest( 'sendLocation', $data );
    }

    public function sendRequest( $method, $data, $url = null)
    {
        $url = empty($url) ? $this->APIurl .$method .'?token=' .$this->token : $url;

        if (is_array($data)) {
            $data = json_encode($data);
        }

        $options = stream_context_create(['http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/json',
            'content' => $data // передаваемые данные
        ]]);

        //return; //this need delete because it is using to test

        $response = file_get_contents($url, false, $options);
    }
}
?>