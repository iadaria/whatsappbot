<?php

namespace App\Http\Controllers;

use App\{User, Helper, ApiRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $apiRequest = null;

    public function __construct()
    {
        $this->middleware('auth');

        $this->apiRequest = new ApiRequest();
    }
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        return view('auth.profile', compact('user'));
    }
        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required', 
            'whatsapp_api_url' => "required",
            'whatsapp_token' => "required"
        ],
        [
            'name.required' => 'Поле обязательное для заполнения',
            'email.required' => 'Поле обязательное для заполнения',
            'whatsapp_api_url.required' => 'Поле обязательное для заполнения',
            'whatsapp_token.required' => 'Поле обязательное для заполнения',
            //'email.email' => 'Неккоректный email-адрес'
        ]);

        $request->emails_to_send = str_replace(" ", "", $request->emails_to_send);

        $user->update(
           $request->only(
                'name', 
                'email', 
                'emails_to_send',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_from_address',
                'mail_subject',
                'mail_from_name',
                'whatsapp_api_url',
                'whatsapp_token'
               )
        );

        $chat_api_url = rtrim($request->whatsapp_api_url, '/') .'/';

        $envs = array(
           // 'MAIL_FROM_ADDRESS' => "\"".$request->mail_subject."\"",
            'MAIL_FROM_NAME' => "\"".$request->mail_from_name."\"",
            'CHAT_API_URL' => $chat_api_url,
            'CHAT_API_TOKEN' => $request->whatsapp_token,
            'CHATID' => $request->chatid,
        );

        $helper = new Helper();
        $helper->setEnvironmentValue($envs);

        $valueEnvs = array(
         //   'mail.from.address' => $request->mail_subject,
            'mail.from.name' => $request->mail_from_name,
            'value.chatapiurl' => $chat_api_url,
            'value.chatapitoken' => $request->whatsapp_token,
            'value.chatid' => $request->chatid,
        );
        config($valueEnvs);

        return redirect()
            ->route('profile')
            ->with('success', 'Команда обновлена.');
    }

    public function resetwebhook() {

        try {
            $data = array(
                'webhookUrl' => 'http://' .request()->getHttpHost() ."/webhook",
                'ackNotificationsOn' => 'true',
                'chatUpdateOn' => 'true',
                'videoUploadOn' => 'true',
                'guaranteedHooks' => 'true',
                'ignoreOldMessages' => 'false',
                'processArchive' => 'false',
            );

            $response = $this->apiRequest->sendRequest( '/settings', $data);

            return back()
                ->with('success', 'webhook обновлен');
        } catch (Exception $e) {
            Log::channel('single')->info('Exception resetwebhook ' .$e->getMessage());

            return back()
                ->with('danger', "Возникла ошибка для записи webhook с адресом " .request()->getHttpHost() ."/webhook");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
