<?php

namespace Tests\Browser\BotAnswers;

use App\{Answer, User};
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TextAnswerTest extends DuskTestCase
{
    private $jpgfile, $jpgfilename, $pathToSave;
    public function setUp() : void
    {
        $this->jpgfile = 'c:/temp/jfile.jpg';
        $this->savedjpgfile = 'c:/OSPanel/domains/localhost/whatsappbot/public_html/storage/doc/jfile.jpg';

        parent::setUp();
    }

    private function getAnswerId(): ?string {
        return Answer::where('answer_id', 41)->value('answer_id') ?? null;
    }
    private function getId(): ?string {
        return Answer::where('answer_id', 41)->value('id') ?? null;
    }
    private function getCommand(): string {
        return Answer::where('answer_id', 41)->value('command') ?? '';
    }
    /** @test */
    public function create_new_text_answer_success(): string
    {
        $this->getId() != null ? Answer::where('answer_id', 41)->delete() : '';
        
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->type('answer_id', 41)
                    ->select('type', 'text')
                    ->type('body', 'test text 1')
                    ->check('need_send_to_email')
                    ->type('command', 'commandtext2')
                    ->type('answers', '1')
                    ->press('Добавить команду бота')
                    ->assertSee('Ваше текстовое сообщение для пользователя успешно сохранено');       
            });
       
        $this->assertDatabaseHas('answers',[
            'answer_id' => '41',
            'type' => 'text',
            'command' => 'commandtext2',
            'body' => 'test text 1',
            'answers' => '1',
            'need_send_to_email' => 1,
            'filepath' =>  '',
            'filename' => '',
            'lng' => null,
            'lat' => null,
            'address' => null,
            'caption' => null,
        ]);

        return $this->getId();
    }

    /** @test */
    public function change_text_to_file_success() {
        
        $id = $this->create_new_text_answer_success();
        $href = request()->root() ."/answers/$id/edit";

        if (file_exists($this->savedjpgfile)) unlink($this->savedjpgfile);

        $this->browse(function (Browser $browser) use ($href) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->click("a[href='$href']")
                    ->assertSee('Редактирование')
                    //->type('answer_id', 40)
                    ->select('type', 'file')
                    ->attach('file', $this->jpgfile)
                    ->type('caption', 'caption test file 1')
                    ->press('Обновить команду')
                    ->assertSee('Команда обновлена.');
        });
    
        $this->assertFileExists($this->savedjpgfile);

        $this->assertDatabaseHas('answers',[
            'answer_id' => '41',
            'type' => 'file',
            'command' => null,
            'body' => null,
            'answers' => '',
            'need_send_to_email' => 0,
            'filepath' =>  request()->root() ."/storage/doc/jfile.jpg",
            'filename' => 'jfile.jpg'
        ]);

        return $this->getAnswerId();
    }

        /** @test */
    public function delete_text_answer_success() {
        
        $id = $this->create_new_text_answer_success();
        $href = request()->root() ."/answers/$id/edit";

        $this->browse(function (Browser $browser) use ($id) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->click("button[form='form_delete_$id']")
                    ->acceptDialog()
                    ->assertSee("Команда c ID = 41 была удалена.");
        });
    }

    /** @test */
    public function change_text_to_location_success() {
        
        $id = $this->create_new_text_answer_success();
        $href = request()->root() ."/answers/$id/edit";

        $this->browse(function (Browser $browser) use ($href) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->click("a[href='$href']")
                    ->assertSee('Редактирование')
                    ->select('type', 'location')
                    ->type('lat', 1)
                    ->type('lng', 1)
                    ->type('address', 'Chita')
                    ->press('Обновить команду')
                    ->assertSee('Команда обновлена.');
        });

        $this->assertDatabaseHas('answers',[
            'answer_id' => '41',
            'type' => 'location',
            'command' => null,
            'body' => null,
            'answers' => '',
            'need_send_to_email' => 0,
            'filepath' =>  null,
            'filename' => null,
            'lat' => 1,
            'lng' => 1,
            'address' => 'Chita',
        ]);
    }

      /** @test */
      public function change_text_to_text_success() {
        
        $id = $this->create_new_text_answer_success();
        $href = request()->root() ."/answers/$id/edit";

        $this->browse(function (Browser $browser) use ($href) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->click("a[href='$href']")
                    ->assertSee('Редактирование')
                    ->select('type', 'text')
                    ->type('body', 'test text 2')
                    ->check('need_send_to_email')
                    ->type('command', 'commandtext22')
                    ->type('answers', '2')
                    ->press('Обновить команду')
                    ->assertSee('Команда обновлена.');
        });

        $this->assertDatabaseHas('answers',[
            'answer_id' => $this->getAnswerId(),
            'type' => 'text',
            'command' => 'commandtext22',
            'body' => 'test text 2',
            'answers' => '2',
            'need_send_to_email' => 1,
            'filepath' =>  null,
            'filename' => null,
            'lng' => null,
            'lat' => null,
            'address' => null,
            'caption' => null,
        ]);
    }

     /** @test */
     public function create_new_text_answer_repeat_fields_fail()
     {
        $this->create_new_text_answer_success();
         
        $this->browse(function (Browser $browser){
            $browser->loginAs(User::find(1))
                     ->visit('/home')
                     ->type('answer_id', $this->getAnswerId())
                     ->select('type', 'text')
                     ->type('body', 'test text fail')
                     ->check('need_send_to_email')
                     ->type('command', $this->getCommand())
                     ->type('answers', '1')
                     ->press('Добавить команду бота')
                     //->assertSee('Ваше текстовое сообщение для пользователя успешно сохранено');     
                     ->assertSee('Команда с таким ID уже существует')      
                     ->assertSee('Такая команда уже существует');       
            });
        
        $this->assertDatabaseMissing('answers',[
             'answer_id' => $this->getAnswerId(),
             'type' => 'text',
             'command' => 'commandtext2',
             'body' => 'test text fail',
             'answers' => '1',
             'need_send_to_email' => 1,
             'filepath' =>  '',
             'filename' => '',
             'lng' => null,
             'lat' => null,
             'address' => null,
             'caption' => null,
         ]);
     }
     /** @test */
     public function create_new_text_answer_empty_answer_id_fail()
     {
        $this->browse(function (Browser $browser){
            $browser->loginAs(User::find(1))
                     ->visit('/home')
                     ->select('type', 'text')
                     ->type('body', 'test text fail')
                     ->check('need_send_to_email')
                     ->type('answers', '1')
                     ->press('Добавить команду бота')
                     //->assertSee('Ваше текстовое сообщение для пользователя успешно сохранено');        
                     ->assertSee('Поле обязательное для заполнения');       
            });
        
        $this->assertDatabaseMissing('answers',[
             'answer_id' => null,
             'type' => 'text',
             'command' => 'commandtext2',
             'body' => 'test text fail',
             'answers' => '1',
             'need_send_to_email' => 1,
             'filepath' =>  '',
             'filename' => '',
             'lng' => null,
             'lat' => null,
             'address' => null,
             'caption' => null,
         ]);
     }
    /** @test */
    public function change_text_to_location_empty_fileds_fail() {
            
        $id = $this->create_new_text_answer_success();
        $href = request()->root() ."/answers/$id/edit";

        $this->browse(function (Browser $browser) use ($href) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->click("a[href='$href']")
                    ->assertSee('Редактирование')
                    ->select('type', 'location')
                    ->type('address', 'Chita')
                    ->press('Обновить команду')
                    ->assertSee('Поле обязательное для заполнения');
        });

        $this->assertDatabaseMissing('answers',[
            'answer_id' => '41',
            'type' => 'location',
            'command' => null,
            'body' => null,
            'answers' => '',
            'need_send_to_email' => 0,
            'filepath' =>  null,
            'filename' => null,
            'lat' => 1,
            'lng' => 1,
            'address' => 'Chita',
        ]);
    }
}
