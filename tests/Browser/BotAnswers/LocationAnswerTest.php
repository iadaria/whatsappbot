<?php

namespace Tests\Browser\BotAnswers;

use App\{Answer, User};
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Http\UploadedFile;

class LocationAnswerTest extends DuskTestCase
{
    private $jpgfile, $savedjpgfile, $jpgfilename;
    private $docxfile, $saveddocxfile, $docxfilename;
    public function setUp() : void
    {
        $this->docxfile = 'c:/temp/WordDoc.docx';
        $this->saveddocxfile = 'c:/OSPanel/domains/localhost/whatsappbot/public_html/storage/doc/WordDoc.docx';
        $this->docxfilename = 'WordDoc.docx';

        parent::setUp();
    }

    private function getAnswerId():?string {
        return Answer::where('answer_id', 42)->value('answer_id') ?? null;
    }
    private function getId():?string {
        return Answer::where('answer_id', 42)->value('id') ?? null;
    }
    /** @test */
    public function create_new_location_answer_success(): string
    {
        Answer::where('answer_id', 42)->delete();

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->assertSee('Текст бота')
                    ->type('answer_id', 42)
                    ->select('type', 'location')
                    ->type('lat', 1)
                    ->type('lng', 1)
                    ->type('address', 'Chita')
                    ->press('Добавить команду бота')
                    ->assertSee('Ваше текстовое сообщение для пользователя успешно сохранено');
        });

         $this->assertDatabaseHas('answers',[
            'answer_id' => '42',
            'type' => 'location',
            'command' => null,
            'body' => '',
            'answers' => '',
            'need_send_to_email' => 0,
            'filepath' =>  '',
            'filename' => '',
            'lat' => 1,
            'lng' => 1,
            'address' => 'Chita',
        ]);
        return $this->getId();
    }
    /** @test */
    public function change_location_to_file_success() {
        
        $id = $this->create_new_location_answer_success();
        $href = request()->root() ."/answers/$id/edit";

        if (file_exists($this->saveddocxfile)) unlink($this->saveddocxfile);

        $this->browse(function (Browser $browser) use ($href) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->click("a[href='$href']")
                    ->assertSee('Редактирование')
                    ->select('type', 'file')
                    ->attach('file', $this->docxfile)
                    ->type('caption', 'caption test file 1')
                    ->press('Обновить команду')
                    ->assertSee('Команда обновлена.');
        });

        $this->assertFileExists($this->saveddocxfile);

        $this->assertDatabaseHas('answers',[
            'answer_id' => $this->getAnswerId(),
            'type' => 'file',
            'command' => null,
            'body' => null,
            'answers' => '',
            'need_send_to_email' => 0,
            'filepath' =>  request()->root() ."/storage/doc/" .$this->docxfilename,
            'filename' => $this->docxfilename
        ]);
    }
    /** @test */
    public function delete_location_answer_success() {
        
        $id = Answer::where('answer_id', 42)->value('id') 
            ?? $this->create_new_location_answer_success();

        $href = request()->root() ."/answers/$id/edit";

        $this->browse(function (Browser $browser) use ($id) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->click("button[form='form_delete_$id']")
                    ->acceptDialog()
                    ->assertSee("Команда c ID = 42 была удалена.");
        });
    }

      /** @test */
      public function change_location_to_text_success() {
        
        $id = $this->create_new_location_answer_success();
        $href = request()->root() ."/answers/$id/edit";

        $this->browse(function (Browser $browser) use ($href) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->click("a[href='$href']")
                    ->assertSee('Редактирование')
                    ->select('type', 'text')
                    ->type('body', 'test text 1')
                    ->check('need_send_to_email')
                    ->type('command', 'commandtext4')
                    ->type('answers', '1')
                    ->press('Обновить команду')
                    ->assertSee('Команда обновлена.');
        });

        $this->assertDatabaseHas('answers',[
            'answer_id' => $this->getAnswerId(),
            'type' => 'text',
            'command' => 'commandtext4',
            'body' => 'test text 1',
            'answers' => '1',
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
   public function create_new_location_empty_lat_fail()
   {
        if ($this->getAnswerId() != null) {
            Answer::where('answer_id', $this->getAnswerId())->delete();
        }

       $this->browse(function (Browser $browser) {
           $browser->loginAs(User::find(1))
                   ->visit('/home')
                   ->assertSee('Текст бота')
                   ->type('answer_id', 42)
                   ->select('type', 'location')
                   ->type('lng', 1)
                   ->type('address', 'Chita')
                   ->press('Добавить команду бота')
                   ->assertSee('Поле обязательное для заполнения');
       });

        $this->assertDatabaseMissing('answers',[
           'answer_id' => '42',
           'type' => 'location',
           'command' => null,
           'body' => '',
           'answers' => '',
           'need_send_to_email' => 0,
           'filepath' =>  '',
           'filename' => '',
           'lng' => 1,
           'address' => 'Chita',
       ]);
   }
   /** @test */
   public function create_new_location_empty_lng_fail()
   {
        if ($this->getAnswerId() != null) {
            Answer::where('answer_id', $this->getAnswerId())->delete();
        }

       $this->browse(function (Browser $browser) {
           $browser->loginAs(User::find(1))
                   ->visit('/home')
                   ->assertSee('Текст бота')
                   ->type('answer_id', 42)
                   ->select('type', 'location')
                   ->type('lat', 1)
                   ->type('address', 'Chita')
                   ->press('Добавить команду бота')
                   ->assertSee('Поле обязательное для заполнения');
       });

        $this->assertDatabaseMissing('answers',[
           'answer_id' => '42',
           'type' => 'location',
           'command' => null,
           'body' => '',
           'answers' => '',
           'need_send_to_email' => 0,
           'filepath' =>  '',
           'filename' => '',
           'lat' => 1,
           'address' => 'Chita',
       ]);
    }
}
