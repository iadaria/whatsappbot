<?php

namespace Tests\Browser;

use App\{Answer, User};
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
/*     public function testBasicExample() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Вход');
        });
    } */

    /** @test */
    public function a_user_can_login() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Вход')
                    ->type('email', 'jadarya@mail.ru')
                    ->type('password', 'password')
                    ->press('Войти')
                    ->assertPathIs('/');
        });
    }

    /************* CREATE TEXT ANSER 333 ****** */
    public function create_text_record_in_bot() {

        $answer = Answer::where('answer_id', '333')->delete();

        $user =  User::find(1);
        $this->browse(function (Browser $browser) use($user) {
            //->loginAs(User::find(1))
            //->puase(2000)
            //->waitForText('willbecreate', digitSecond)
            $browser->loginAs(User::find(1))
                    ->visit('/')
                    ->assertSee('Текст бота')
                    ->type('answer_id', '333')
                    ->select('type', 'text')
                    ->type('body', 'test 1')
                    ->check('need_send_to_email')
                    ->type('command', 'command1')
                    ->type('answers', '0')
                    ->press('Добавить команду бота')
                    ->assertSee('Ваше текстовое сообщение для пользователя успешно сохранено');
        });
    }

    /** @test */
    public function create_text_record_in_bot_check_bd() {
        $answerId = Answer::where('answer_id', '333')->value('id');

        if ($answerId == null) { $this->create_text_record_in_bot(); }

        $this->assertDatabaseHas('answers',[
            'answer_id' => '333',
            'command' => 'command1',
            'body' => 'test 1',
            'answers' => '0',
            'need_send_to_email' => 1,
            'lng' => null,
            'lat' => null,
            'filepath' => '',
            'filename' => ''
        ]);
    }
    /********* UPDATE ANSWER 333 to LOCATION */
    /** @test */
    public function not_change_text_record_to_location_without_lng_lat() {
        //because lng is emty, and lat is empty

        $answerId = Answer::where('answer_id', '333')->value('id');

        if ($answerId == null) $this->create_text_record_in_bot();
            //$user =  User::find(1);
            $this->browse(function (Browser $browser) use($answerId) {
                $browser->loginAs(User::find(1))
                        ->visit("/answers/$answerId/edit")
                        ->type('answer_id', '333')
                        ->select('type', 'location')
                        ->press('Обновить команду')
                        ->assertDontSee('Ваше текстовое сообщение для пользователя успешно сохранено');
            });

    }
    /** @test */
    public function change_text_record_to_location() {

        $answerId = Answer::where('answer_id', '333')->value('id');

        if ($answerId == null) { $this->create_text_record_in_bot(); }

        //$user =  User::find(1);
        $this->browse(function (Browser $browser) use($answerId) {
            $browser->loginAs(User::find(1))
                    ->visit("/answers/$answerId/edit")
                    ->type('answer_id', '333')
                    ->select('type', 'location')
                    ->type('lat', 33)
                    ->type('lng', 22)
                    ->press('Обновить команду')
                    ->assertDontSee('Ваше текстовое сообщение для пользователя успешно сохранено');
        });
    }

    /** @test */
    public function is_changed_text_to_location_success() {
        //$createdAnswer = Answer::where('command', 'command1')->get();
        $this->assertDatabaseHas('answers',[
            'answer_id' => '333',
            'command' => null,
            'body' => null,
            'answers' => '',
            'need_send_to_email' => 0,
            'filename' => '',
            'filepath' => '',
            'lat' => 33,
            'lng' => 22
        ]);
    }

    /** @test */    
    public function create_text_record_change_to_location() {
        $user =  User::find(1);
        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs(User::find(1))
                    ->visit('/')
                    ->assertSee('Текст бота')
                    ->type('answer_id', '334')
                    ->select('type', 'text')
                    ->type('body', 'test 1')
                    ->check('need_send_to_email')
                    ->type('command', 'command1')
                    ->type('answers', '1')
                    ->select('type', 'location')
                    ->type('lng', '33')
                    ->type('lat', '22')
                    ->press('Добавить команду бота')
                    ->assertSee('Ваше текстовое сообщение для пользователя успешно сохранено');
        });
    }

    /** @test */
    public function create_text_record_to_location_db() {
        //$createdAnswer = Answer::where('command', 'command1')->get();
        $this->assertDatabaseHas('answers',[
            'answer_id' => '334',
            'type' => 'location',
            'command' => null,
            'body' => '',
            'answers' => '',
            'need_send_to_email' => 0,
        ]);
    }
}
