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
                            ->visit('/exercises')
                            ->press('Crea')
                            ->type('name', 'Test Exercise')
                            ->type('subject', 'Test Subject')
                            ->radio('type', 'Risposta Aperta')
                            ->press('Avanti')
                            ->type('question', 'This is a test question?')
                            ->press('Avanti')
                            ->select('difficulty', 'Bassa')
                            ->type('score', 10)
                            ->press('Avanti')
                            ->assertPathIs('/exercises');
            });
        }


        public function testCreateMultipleChoiceExercise()
        {
            $this->browse(function ($browser) {
                $browser->loginAs(User::find(1))
                        ->visit('/exercises')
                        ->press('Crea')
                        ->type('name', 'Test Exercise')
                        ->type('subject', 'Test Subject')
                        ->radio('type', 'Risposta Multipla')
                        ->press('Avanti')
                        ->type('question', 'What is the capital of France?')
                        ->type('#option1', 'Paris')
                        ->type('#option2', 'London')
                        ->type('#option3', 'Berlin')
                        ->type('#option4', 'Madrid')
                        ->select('correct_option', 'A')
                        ->type('explanation', 'The capital of France is Paris')
                        ->press('Avanti')
                        ->select('difficulty', 'Media')
                        ->type('score', 10)
                        ->press('Avanti')
                        ->assertPathIs('/exercises');
                        
                $latestExercise = Exercise::orderBy('id', 'desc')->first();
                $this->assertEquals('Test Exercise', $latestExercise->name);
                $this->assertEquals('Test Subject', $latestExercise->subject);
                $this->assertEquals('Risposta Multipla', $latestExercise->type);
                $this->assertEquals('What is the capital of France?', $latestExercise->question);
                $this->assertEquals('Media', $latestExercise->difficulty);
                 // Check if options relationship is not null before accessing its properties
                 if (!is_null($latestExercise->options)) {
                    $this->assertEquals('Paris', $latestExercise->options[0]->content);
                    $this->assertEquals('London', $latestExercise->options[1]->content);
                    $this->assertEquals('Berlin', $latestExercise->options[2]->content);
                    $this->assertEquals('Madrid', $latestExercise->options[3]->content);
                    $this->assertEquals('A', $latestExercise->options[0]->pivot->correct_option);
                }
                $this->assertEquals('The capital of France is Paris', $latestExercise->explanation);
            });
        }

        public function testCreateTrueFalseExercise()
        {
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::find(1))
                    ->visit('/exercises')
                    ->press('Crea')
                    ->type('name', 'Test Exercise')
                    ->type('subject', 'Test Subject')
                    ->radio('type', 'Vero o Falso')
                    ->press('Avanti')
                    ->type('question', 'Is the sky blue?')
                    ->select('correct_option', 'vero')
                    ->type('explanation', 'The sky is typically blue due to scattering of sunlight')
                    ->press('Avanti')
                    ->select('difficulty', 'Media')
                    ->type('score', 10)
                    ->press('Avanti')
                    ->assertPathIs('/exercises');
                    
                $latestExercise = Exercise::orderBy('id', 'desc')->first();
                $this->assertEquals('Test Exercise', $latestExercise->name);
                $this->assertEquals('Test Subject', $latestExercise->subject);
                $this->assertEquals('Vero o Falso', $latestExercise->type);
                $this->assertEquals('Is the sky blue?', $latestExercise->question);
                $this->assertEquals('Media', $latestExercise->difficulty);
                $this->assertEquals('vero', $latestExercise->correct_option);
                $this->assertEquals('The sky is typically blue due to scattering of sunlight', $latestExercise->explanation);
            });
        }

        public function testCreateExerciseWithInvalidScore()
        {
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::find(1))
                    ->visit('/exercises')
                    ->press('Crea')
                    ->type('name', 'Test Exercise')
                    ->type('subject', 'Test Subject')
                    ->radio('type', 'Risposta Aperta')
                    ->press('Avanti')
                    ->type('question', 'This is a test question?')
                    ->press('Avanti')
                    ->select('difficulty', 'Bassa')
                    ->type('score', -5) // Punteggio negativo non valido
                    ->press('Avanti')
                    ->assertSee('Il campo score deve essere almeno 1'); // Verifica il messaggio di errore per il punteggio non valido
            });
        }

        public function testCreateExerciseWithMissingNameField()
            {
                $this->browse(function (Browser $browser) {
                    $browser->loginAs(User::find(1))
                        ->visit('/exercises')
                        ->press('Crea')
                        ->type('name', '')
                        ->type('subject', 'Test Subject')
                        ->radio('type', 'Risposta Aperta')
                        ->press('Avanti')
                        ->assertPathIs('/exercises/create-first'); // Verifica il messaggio di errore per il campo obbligatorio
                });
            }
        
            public function testCreateExerciseWithMissingSubjectField()
            {
                $this->browse(function (Browser $browser) {
                    $browser->loginAs(User::find(1))
                        ->visit('/exercises')
                        ->press('Crea')
                        ->type('name', 'Test Exercise')
                        ->type('subject', '')
                        ->radio('type', 'Risposta Aperta')
                        ->press('Avanti')
                        ->assertPathIs('/exercises/create-first'); // Verifica il messaggio di errore per il campo obbligatorio
                });
            }
            
            public function testCreateExerciseWithMissingQuestionField()
            {
                $this->browse(function (Browser $browser) {
                    $browser->loginAs(User::find(1))
                        ->visit('/exercises')
                        ->press('Crea')
                        ->type('name', 'Test Exercise')
                        ->type('subject', 'Test Subject')
                        ->radio('type', 'Vero o Falso')
                        ->press('Avanti')
                        ->type('question', '')
                        ->select('correct_option', 'vero')
                        ->type('explanation', 'The sky is typically blue due to scattering of sunlight')
                        ->press('Avanti')
                        ->assertPathIs('/exercises/create-second');
                });
            } 

}







