<?php
namespace App\Checker\Message;

use App\{Answer, ApiRequest, Helper};
use Illuminate\Support\Carbon;

class TestChecker extends MessageChecker {
    private $apiRequest = null;

    public function __construct(ApiRequest $apiRequest)
    {
        $this->apiRequest = $apiRequest;
    }

    public function check(&$toBotMessage) {
        $text = explode( ' ', trim( $toBotMessage->body ) );
        $command = mb_strtolower( $text[0], 'UTF-8' );

        if (strcasecmp($command, 'testsend') == 0) {
            $timeRecieved = Carbon::now()->timestamp;

            $envs = array(
                 'TEST_RECEIVED_TIME' => $timeRecieved,
             ); 
            $helper = new Helper();
            $helper->setEnvironmentValue($envs);
            $valueEnvs = array(
                 'value.testreceivedtime' => $timeRecieved,
            );
            config($valueEnvs);

            $timesend = intval(config('value.testsendtime') ?? 0);
            $different = $timeRecieved - $timesend;
            $emodji = $different < 2 ? "👌" : ($different > 4 ? "🐢" : '');

            $this->apiRequest->sendMessage( 
                config('value.chatid'), 
                "testreceived  $emodji Бот ответил через $different секунд"
                );

            return;

        }
        parent::check($toBotMessage);
    }
}
?>