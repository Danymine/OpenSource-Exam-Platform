<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
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

       /* public function testCreateMultipleResponseExercise()
        {
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::find(1))
                    ->visit('/exercises/create')
                    ->press('Crea Esercizio')
                    ->type('name', 'Esercizio a Risposta Multipla')
                    ->type('question', 'Seleziona la risposta corretta tra le opzioni fornite')
                    ->type('score', 10)
                    ->select('difficulty', 'Media')
                    ->type('subject', 'Scienze')
                    ->select('type', 'Risposta Multipla')
                    ->waitFor('#multiple_choice') // Attendere che il div con le opzioni multiple diventi visibile
                    ->type('options[]', 'Opzione 1')
                    ->type('options[]', 'Opzione 2')
                    ->type('options[]', 'Opzione 3')
                    ->type('options[]', 'Opzione 4')
                    ->select('correct_option', 1)
                    ->type('explanation', 'La risposta corretta è Opzione 1')
                    ->press('Crea Esercizio')
                    ->assertPathIs('/exercises/create');
            });
        }
        */
        /*

        public function testCreateTrueFalseExercise()
        {
            $this->browse(function (Browser $browser) {
                $browser->loginAs(User::find(1))
                    ->visit('/exercises/esercizi_biblioteca')
                    ->press('Crea Esercizio')
                    ->type('name', 'Esercizio Vero o Falso')
                    ->type('question', 'Questa affermazione è vera?')
                    ->type('score', 10)
                    ->select('difficulty', 'Alta')
                    ->type('subject', 'Matematica')
                    ->select('type', 'Vero o Falso')
                    ->waitFor('#true_false') // Attendere che il div per il tipo Vero o Falso diventi visibile
                    ->screenshot('AB')
                    ->select('correct_option', 'Vero')
                    ->type('#explanation', 'La risposta corretta è Vero perché...')
                    ->screenshot('ABC')
                    ->press('Crea Esercizio')
                    ->assertPathIs('/exercises/esercizi_biblioteca');
        });
    }*/
} 

