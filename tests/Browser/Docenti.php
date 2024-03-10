<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use App\Models\Practice;
use Illuminate\Support\Carbon;
use Tests\DuskTestCase;

class Docenti extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testTeacherCanSeeExam(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/dashboard')
                ->press("#menu")
                ->clickLink('Esami')
                ->assertPathIs('/exam');
        });
    }
    

    public function testTeacherCanSeeCreateExamManual(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/exam')
                ->press('#dropdownCreateMenuButton')
                ->pause(1000)
                ->clickLink('Crea Manualmente')
                ->assertPathIs('/exam/create-first');
        });
    }

    public function testTeacherCanCreateExamManual(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/exam/create-first')
                ->type('title', "ProvaTitolo")
                ->type('subject', "ProvaMateria")
                ->type('description', "DescrizioneMoltoLunga")
                ->press('#avanti')
                ->assertPathIs('/exam/create-second')
                ->check('#exercise1')
                ->check('#exercise2')
                ->check('#exercise3')
                ->press('#avanti')
                ->assertPathIs('/exam/create-third')
                ->type('time', 60)
                ->type('practice_date', now()->format('d/m/Y'))
                ->radio('randomize_questions', 1)
                ->select('difficulty', 'Bassa')
                ->press('#avanti');
        });

        $exam = Practice::where('title', 'ProvaTitolo')->latest()->first();
        $this->assertNotNull($exam);
        $this->assertEquals('ProvaMateria', $exam->subject);
        $this->assertEquals('DescrizioneMoltoLunga', $exam->description);
        $this->assertEquals(60, $exam->time);
        $this->assertEquals(Carbon::now()->format('Y-d-m'), $exam->practice_date);
        $this->assertEquals(1, $exam->randomize_questions);
        $this->assertEquals("Bassa", $exam->difficulty);
        $this->assertCount(3, $exam->exercises);
        $this->assertEquals("Exam", $exam->type);

        $exam->exercises()->detach();

        $exam->delete();
        $exam->forceDelete();
    }

    public function testTeacherCannotExamVisitStep2(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/exam/create-second')
                ->assertSee('NON AUTORIZZATO.'); 
        });
    }

    public function testTeacherCannotExamVisitStep3(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/exam/create-third')
                ->assertSee('NON AUTORIZZATO.');   
        });
    }

    public function testTeacherCanSeeCreateExamAutomation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/exam')
                ->press('#dropdownCreateMenuButton')
                ->pause(1000)
                ->clickLink('Genera Automaticamente')
                ->assertPathIs('/create-automation');
        });
    }


    public function testTeacherCanCreateExamAutomation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/create-automation')
                ->type('title', "ProvaTitolo")
                ->type('description', "DescrizioneMoltoLunga")
                ->select('difficulty', 'Bassa')
                ->type('subject', "Informatica")
                ->type('total_score', 15)
                ->type('practice_date', now()->format('d/m/Y'))
                ->select('type', 'Exam')
                ->type('time', 60)
                ->radio('randomize_questions', 0)
                ->radio('feedback', 0)
                ->press('#crea')
                ->assertPathIs('/exam');
        });

        $exam = Practice::where('title', 'ProvaTitolo')->latest()->first();
        $this->assertNotNull($exam);
        $this->assertEquals('Informatica', $exam->subject);
        $this->assertEquals('DescrizioneMoltoLunga', $exam->description);
        $this->assertEquals(60, $exam->time);
        $this->assertEquals(Carbon::now()->format('Y-d-m'), $exam->practice_date);
        $this->assertEquals(0, $exam->randomize_questions);
        $this->assertEquals("Bassa", $exam->difficulty);
        $this->assertEquals("Exam", $exam->type);

        $exam->exercises()->detach();

        $exam->delete();
        $exam->forceDelete();
    }

    public function testTeacherCanSeePractice(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/dashboard')
                ->press("#menu")
                ->clickLink('Esercitazioni')
                ->assertPathIs('/practice');
        });
    }

    public function testTeacherCanSeeCreatePracticeManual(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/practice')
                ->press('#dropdownCreateMenuButton')
                ->pause(1000)
                ->clickLink('Crea Manualmente')
                ->assertPathIs('/practice/create-first');
        });
    }

    public function testTeacherCanCreatePracticeManual(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/practice/create-first')
                ->type('title', "ProvaTitolo2")
                ->type('subject', "ProvaMateria2")
                ->type('description', "DescrizioneMoltoLunga2")
                ->press('#avanti')
                ->assertPathIs('/practice/create-second')
                ->check('#exercise1')
                ->check('#exercise2')
                ->check('#exercise3')
                ->press('#avanti')
                ->assertPathIs('/practice/create-third')
                ->type('time', 60)
                ->type('practice_date', now()->format('d/m/Y'))
                ->radio('randomize_questions', 1)
                ->select('difficulty', 'Bassa')
                ->press('#avanti');
        });

        $exam = Practice::where('title', 'ProvaTitolo2')->latest()->first();
        $this->assertNotNull($exam);
        $this->assertEquals('ProvaMateria2', $exam->subject);
        $this->assertEquals('DescrizioneMoltoLunga2', $exam->description);
        $this->assertEquals(60, $exam->time);
        $this->assertEquals(Carbon::now()->format('Y-d-m'), $exam->practice_date);
        $this->assertEquals(1, $exam->randomize_questions);
        $this->assertEquals("Bassa", $exam->difficulty);
        $this->assertCount(3, $exam->exercises);
        $this->assertEquals("Practice", $exam->type);


        $exam->exercises()->detach();
        
        $exam->delete();
        $exam->forceDelete();
    }

    public function testTeacherCannotVisitPracticeStep2(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/exam/create-second')
                ->assertSee('NON AUTORIZZATO.');    
        });
    }

    public function testTeacherCannotVisitPracticStep3(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/exam/create-third')
                ->assertSee('NON AUTORIZZATO.');   
        });
    }

    public function testTeacherCanSeeCreatePracticeAutomation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/exam')
                ->press('#dropdownCreateMenuButton')
                ->pause(1000)
                ->clickLink('Genera Automaticamente')
                ->assertPathIs('/create-automation');
        });
    }

    public function testTeacherCanCreatePracticeAutomation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/create-automation')
                ->type('title', "ProvaTitolo")
                ->type('description', "DescrizioneMoltoLunga")
                ->select('difficulty', 'Bassa')
                ->type('subject', "Informatica")
                ->type('total_score', 15)
                ->type('practice_date', now()->format('d/m/Y'))
                ->select('type', 'Practice')
                ->type('time', 60)
                ->radio('randomize_questions', 0)
                ->radio('feedback', 0)
                ->press('#crea')
                ->assertPathIs('/practice');
        });

        $practice = Practice::where('title', 'ProvaTitolo')->latest()->first();
        $this->assertNotNull($practice);
        $this->assertEquals('Informatica', $practice->subject);
        $this->assertEquals('DescrizioneMoltoLunga', $practice->description);
        $this->assertEquals(60, $practice->time);
        $this->assertEquals(Carbon::now()->format('Y-d-m'), $practice->practice_date);
        $this->assertEquals(0, $practice->randomize_questions);
        $this->assertEquals("Bassa", $practice->difficulty);
        $this->assertEquals("Practice", $practice->type);
    }


    //Proviamo a vedere se un teacher può accedere a dati di terzi
    public function testTeacherCannotVisitNoOwner(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/show/' . Practice::where('user_id', '!=', 1)->first()->id )       //Vi deve essere almeno una Practice che non è stata creata da lui
                ->assertSee('NON AUTORIZZATO.');
        });
    }

    public function testTeacherCannotEditNoOwner(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/edit/' . Practice::where('user_id', '!=', 1)->first()->id )       //Vi deve essere almeno una Practice che non è stata creata da lui
                ->assertSee('NON AUTORIZZATO.');     
        });
    }

    public function testTeacherCannotCreatePracticeAutomationWithoutTitle(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/create-automation')
                ->type('description', "DescrizioneMoltoLunga")
                ->select('difficulty', 'Bassa')
                ->type('subject', "Informatica")
                ->type('total_score', 15)
                ->type('practice_date', now()->format('d/m/Y'))
                ->select('type', 'Practice')
                ->type('time', 60)
                ->radio('randomize_questions', 0)
                ->radio('feedback', 0)
                ->press('#crea')
                ->assertPathIs('/create-automation');
        });
    }

    public function testTeacherCannotCreatePracticeAutomationWithoutDescription(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/create-automation')
                ->type('title', "ProvaTitolo")
                ->select('difficulty', 'Bassa')
                ->type('subject', "Informatica")
                ->type('total_score', 15)
                ->type('practice_date', now()->format('d/m/Y'))
                ->select('type', 'Practice')
                ->type('time', 60)
                ->radio('randomize_questions', 0)
                ->radio('feedback', 0)
                ->press('#crea')
                ->assertPathIs('/create-automation');
        });
    }
    
    public function testTeacherCannotCreatePracticeAutomationWithoutSubject(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/create-automation')
                ->type('title', "ProvaTitolo")
                ->type('description', "DescrizioneMoltoLunga")
                ->select('difficulty', 'Bassa')
                ->type('total_score', 15)
                ->type('practice_date', now()->format('d/m/Y'))
                ->select('type', 'Practice')
                ->type('time', 60)
                ->radio('randomize_questions', 0)
                ->radio('feedback', 0)
                ->press('#crea')
                ->assertPathIs('/create-automation');
        });
    }

    public function testTeacherCannotCreatePracticeAutomationWithoutTotalScore(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/create-automation')
                ->type('title', "ProvaTitolo")
                ->type('description', "DescrizioneMoltoLunga")
                ->select('difficulty', 'Bassa')
                ->type('subject', "Informatica")
                ->type('practice_date', now()->format('d/m/Y'))
                ->select('type', 'Practice')
                ->type('time', 60)
                ->radio('randomize_questions', 0)
                ->radio('feedback', 0)
                ->press('#crea')
                ->assertPathIs('/create-automation');
        });
    }

    public function testTeacherCannotCreatePracticeAutomationWithoutPracticeDate(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/create-automation')
                ->type('title', "ProvaTitolo")
                ->type('description', "DescrizioneMoltoLunga")
                ->select('difficulty', 'Bassa')
                ->type('total_score', 15)
                ->type('subject', "Informatica")
                ->select('type', 'Practice')
                ->type('time', 60)
                ->radio('randomize_questions', 0)
                ->radio('feedback', 0)
                ->press('#crea')
                ->assertPathIs('/create-automation');
        });
    }

    public function testTeacherCannotCreatePracticeAutomationWithoutRand(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/create-automation')
                ->type('title', "ProvaTitolo")
                ->type('description', "DescrizioneMoltoLunga")
                ->select('difficulty', 'Bassa')
                ->type('total_score', 15)
                ->type('subject', "Informatica")
                ->type('practice_date', now()->format('d/m/Y'))
                ->select('type', 'Practice')
                ->type('time', 60)
                ->radio('feedback', 0)
                ->press('#crea')
                ->assertPathIs('/create-automation');
        });
    }

    public function testTeacherCannotCreatePracticeAutomationWithoutFeedback(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/create-automation')
                ->type('title', "ProvaTitolo")
                ->type('description', "DescrizioneMoltoLunga")
                ->select('difficulty', 'Bassa')
                ->type('total_score', 15)
                ->type('subject', "Informatica")
                ->type('practice_date', now()->format('d/m/Y'))
                ->select('type', 'Practice')
                ->type('time', 60)
                ->radio('randomize_questions', 0)
                ->press('#crea')
                ->assertPathIs('/create-automation');
        });
    }
}
