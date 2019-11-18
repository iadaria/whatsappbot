<?php namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FormTest extends TestCase {
    
    /** @test */
    public function not_correct_fill_form() {
        $this->visit('/answers')
             ->see('Добавить команду бота');
    }
}

?>