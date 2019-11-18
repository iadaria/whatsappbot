<?php
use Illuminate\Support\Facades\File;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

Auth::routes([
    'register' => false
]);
Route::get('/resetpasswordform', 'Auth\ResetPasswordController@showResetPasswordForm')->name('resetpasswordform');
Route::post('/resetpassword', 'Auth\ResetPasswordController@resetpassword')->name('resetpassword');

Route::get('/', 'AnswerController@index'); //'Auth\LoginController@showLoginForm');
Route::get('/home', 'AnswerController@index')->name('home');

Route::get('/profile', 'UserController@index')->name('profile');
Route::get('/resetwebhook', 'UserController@resetwebhook')->name('resetwebhook');
Route::resource('user', 'UserController');

Route::get('/showmessages', 'AnswerController@showmessages')->name('showmessages');
Route::get('/newmessages', 'AnswerController@newmessages')->name('newmessages');
Route::get('/savemessages', 'AnswerController@savemessages')->name('savemessages');
Route::any('/search', 'AnswerController@search')->name('search');
Route::delete('/delmessages', 'AnswerController@delmessages')->name('delmessages');
Route::delete('/delnewmessages', 'AnswerController@delnewmessages')->name('delnewmessages');
//Route::post('/update/{answer}', 'AnswerController@update')->name('answers.update');
Route::get('/exportexcel', 'AnswerController@exportexcel')->name('exportexcel');
Route::resource('answers', 'AnswerController');//->except([//'update']);

Route::post('/webhook', 'WebhookController@index')->name('webhook.index');
Route::any('/test', 'TestController@index');
/* Route::resource('webhook', 'WebhookController')->except([
    'create',
]); */



/* Route::group(['middleware' => 'guest:api'], function() {
    Route::get('/doc/{filename}', function($filename)
    {
        $filePath = storage_path('app/public/doc/' .$filename);

        if ( !File::exists($filePath) ) {
            return Response::make("Файла $filename не существует", 404);
        }

        $fileContents = File::get($filePath);

        return Response::make($fileContents, 200, array('Content-Type' => 'multipart/form-data'));
    });
}); */