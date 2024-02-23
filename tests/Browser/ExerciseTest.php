<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Exercise;


class ExerciseTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1) // Login con l'utente con ID 1
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
                ->screenshot('ProvaFinale')
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
}
