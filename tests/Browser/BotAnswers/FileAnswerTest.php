<?php

namespace Tests\Browser\BotAnswers;

use App\{Answer, User};
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Http\UploadedFile;

class FileAnswerTest extends DuskTestCase
{
    private $jpgfile, $savedjpgfile, $jpgfilename;
    private $docxfile, $saveddocxfile, $docxfilename;
    public function setUp() : void
    {
        $this->jpgfile = 'c:/temp/jfile.jpg';
        $this->jpgfilename = 'jfile.jpg';
        $this->savedjpgfile = 'c:/OSPanel/domains/localhost/whatsappbot/public_html/storage/doc/jfile.jpg';


        $this->docxfile = 'c:/temp/WordDoc.docx';
        $this->saveddocxfile = 'c:/OSPanel/domains/localhost/whatsappbot/public_html/storage/doc/WordDoc.docx';
        $this->docxfilename = 'WordDoc.docx';

        parent::setUp();
    }

    private function getAnswerId():?string {
        return Answer::where('answer_id', 40)->value('answer_id') ?? null;
    }
    private function getId():?string {
        return Answer::where('answer_id', 40)->value('id') ?? null;
    }
    private function getNewAnswerId():?int {
        return Answer::raw('max(answer_id)')->value('answer_id') + 1 ?? null;
    }
    /** @test */
    public function create_new_jpg_file_answer_success(): string
    {
        Answer::where('answer_id', 40)->delete();
        if (file_exists($this->savedjpgfile)) unlink($this->savedjpgfile);

       /*  $file = UploadedFile::fake()->image('avatar.jpg');  
        $filename = $file->getClientOriginalName();
        $filefull = $file->getPathname();
        $filepath = $file->getPath(); */
        
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->assertSee('Текст бота')
                    ->type('answer_id', 40)
                    ->select('type', 'file')
                    ->attach('file', $this->jpgfile)//UploadedFile::fake()->image('avatar.jpg'))//'C:\temp\jfile.jpg')
                    ->type('caption', 'caption test file 1')
                    ->press('Добавить команду бота')
                    ->assertSee('Ваше текстовое сообщение для пользователя успешно сохранено');
        });

        $this->assertFileExists($this->savedjpgfile);

        $this->assertDatabaseHas('answers',[
            'answer_id' => $this->getAnswerId(),
            'type' => 'file',
            'command' => null,
            'body' => '',
            'answers' => '',
            'need_send_to_email' => 0,
            'filepath' =>  request()->root() ."/storage/doc/jfile.jpg",
            'filename' => 'jfile.jpg'
        ]);

        return $this->getId();
    }
    /** @test */
    public function change_jpg_file_answer_to_doc_file_success() {
        
        $id = $this->create_new_jpg_file_answer_success();
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
    public function delete_jpg_file_answer_success() {
        
        $id = Answer::where('answer_id', 40)->value('id') 
            ?? $this->create_new_jpg_file_answer_success();

        $href = request()->root() ."/answers/$id/edit";

        $this->browse(function (Browser $browser) use ($id) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->click("button[form='form_delete_$id']")
                    ->acceptDialog()
                    ->assertSee("Команда c ID = 40 была удалена.");
        });
    }

     /** @test */
     public function change_file_to_location_success() {
        
        $id = $this->create_new_jpg_file_answer_success();
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
            'answer_id' => $this->getAnswerId(),
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
      public function change_file_to_text_success() {
        
        $id = $this->create_new_jpg_file_answer_success();
        $href = request()->root() ."/answers/$id/edit";

        $this->browse(function (Browser $browser) use ($href) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->click("a[href='$href']")
                    ->assertSee('Редактирование')
                    ->select('type', 'text')
                    ->type('body', 'test text 1')
                    ->check('need_send_to_email')
                    ->type('command', 'commandtext3')
                    ->type('answers', '1')
                    ->press('Обновить команду')
                    ->assertSee('Команда обновлена.');
        });

        $this->assertDatabaseHas('answers',[
            'answer_id' => $this->getAnswerId(),
            'type' => 'text',
            'command' => 'commandtext3',
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
    public function create_new_jpg_file_empty_file_fail()
    {
        $newAnswerId = $this->getNewAnswerId();
        $this->browse(function (Browser $browser) use($newAnswerId) {
            $browser->loginAs(User::find(1))
                    ->visit('/home')
                    ->assertSee('Текст бота')
                    ->type('answer_id', $newAnswerId)
                    ->select('type', 'file')
                    ->type('caption', 'caption test file 1')
                    ->press('Добавить команду бота')
                    ->assertSee('Поле обязательное для заполнения');
        });

        $this->assertDatabaseMissing('answers',[
            'answer_id' => $newAnswerId,
            'type' => 'file',
            'command' => null,
            'body' => '',
            'answers' => '',
            'need_send_to_email' => 0,
            'filepath' =>  request()->root() ."/storage/doc/jfile.jpg",
            'filename' => 'jfile.jpg'
        ]);
    }

}
