<?php

namespace App\Console;

use App\{ApiRequest, Helper};
use Illuminate\Support\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call( function() {
            $apiRequest = new ApiRequest();

            $message = $this->createMessage();
            $message['body'] = 'testsend';

            $data = array(
                'instanceId' => '41777',
                'messages' => [$message]
            );
            $timeSent = Carbon::now()->timestamp;
            $envs = array(
                'TEST_SEND_TIME' => $timeSent,
            ); 
            $helper = new Helper();
            $helper->setEnvironmentValue($envs);
            $valueEnvs = array(
                'value.testsendtime' => $timeSent,
            );
            config($valueEnvs);

            $apiRequest->sendRequest( 'sendMessage', $data, request()->root() .'/webhook' );
                   
            Log::channel('single')->info('Test log and scheduler minute');
        })->everyMinute();
    }

    private function createMessage() {
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
      
          return $message;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
