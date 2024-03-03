<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Support\Facades\Auth;


class Esercizi extends DuskTestCase
{
        public function testCreateOpenResponseExercise()
        {
            $this->browse(function (Browser $browser) {
                    $browser->loginAs(User::find(1))
                            ->visit('/exercises/esercizi_biblioteca')
                            ->press('Crea Esercizio')
                            ->type('name', 'Test Exercise')
                            ->type('question', 'This is a test question?')
                            ->type('score', 10)
                            ->select('difficulty', 'Bassa')
                            ->type('subject', 'Test Subject')
                            ->select('type', 'Risposta Aperta')
                            ->press('Crea Esercizio')
                            ->assertPathIs('/exercises/esercizi_biblioteca');
            });
        }

        public function testCreateMultipleResponse(): void
        {
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::find(1)) 
                    ->visit('/exercises/esercizi_biblioteca')
                    ->press('Crea Esercizio')
                    ->type('name','Prova')
                    ->type('question','ProvaProvaProvaProva')
                    ->type('score','1')
                    ->select('difficulty','Bassa')
                    ->type('subject','Materia')
                    ->select('type','Risposta Multipla')
                    ->type('#option1', 'Valore 1')
                    ->type('#option2', 'Valore 2')
                    ->type('#option3', 'Valore 3')
                    ->type('#option4', 'Valore 4')
                    ->type('correct_option','1')
                    ->type('explanation','Prova')
                    ->press('Crea Esercizio')
                    ->assertPathIs('/exercises/esercizi_biblioteca');
            });
    
            $latestExercise = Exercise::orderBy('id', 'desc')->first();
    
            // Verifica che l'esercizio sia stato salvato nel database e che i dati corrispondano a quanto inserito nel form
            $this->assertEquals('Prova', $latestExercise->name);
            $this->assertEquals('ProvaProvaProvaProva', $latestExercise->question);
            $this->assertEquals(1, $latestExercise->score);
            $this->assertEquals('Bassa', $latestExercise->difficulty);
            $this->assertEquals('Materia', $latestExercise->subject);
            $this->assertEquals('Risposta Multipla', $latestExercise->type);
    
        }

        public function testCreateTrueorFalseResponse(): void
        {
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::find(1)) 
                    ->visit('/exercises/esercizi_biblioteca')
                    ->press('Crea Esercizio')
                    ->type('name','Prova')
                    ->type('question','ProvaProvaProvaProva')
                    ->type('score','1')
                    ->select('difficulty','Bassa')
                    ->type('subject','Materia')
                    ->select('type','Vero o Falso')
                    ->select('correct_option','Vero')
                    ->type('explanation','Verooooooo')
                    ->press('Crea Esercizio')
                    ->assertPathIs('/exercises/esercizi_biblioteca');
            });
    
            $latestExercise = Exercise::orderBy('id', 'desc')->first();
    
            // Verifica che l'esercizio sia stato salvato nel database e che i dati corrispondano a quanto inserito nel form
            $this->assertEquals('Prova', $latestExercise->name);
            $this->assertEquals('ProvaProvaProvaProva', $latestExercise->question);
            $this->assertEquals(1, $latestExercise->score);
            $this->assertEquals('Bassa', $latestExercise->difficulty);
            $this->assertEquals('Materia', $latestExercise->subject);
            $this->assertEquals('Vero o Falso', $latestExercise->type);
    
        }





}







