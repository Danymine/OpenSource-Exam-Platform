<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\EsercitazioneController;


//Temporanea
Route::get('/errore', function (){

    return "Non hai l'account adatto per svolgere questa opzione.";
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Rotta per raggiungere il sito EBBASTA
Route::get('/', function () {
    return view('welcome');
})->name('ciao');

//Rotta per raggiungere la propria dashboard questa si occupera di fornire SOLO la dashboard visibile al tipo di utente che la richiede
Route::get('/dashboard', function () {

    return view('dashboard');

})->middleware(['auth','verified'])->name('dashboard'); //Richiede il passaggio da due middleware Auth e Verified (Da approfondire Verified)
//Questa rotta va bene sia per studenti che per il docente che per l'amministratore nel caso.

//Tutte le rotte in questo group sono sottoposte al dover rispettare il middleware Auth
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware('auth', 'role')->group(function (){

    /* ROTTE DI MARCO */

    // Rotte per gli esercizi
    Route::prefix('exercises')->group(function () {
        // Mostra tutti gli esercizi
        Route::get('/', [ExerciseController::class, 'showAllExercises'])->name('showAllExercises');
        
        // Crea un nuovo esercizio
        Route::get('/create', [ExerciseController::class, 'create']);

        Route::post('/create', [ExerciseController::class, 'store'])->name("exercises.store");

        // Modifica un esercizio esistente
        Route::get('/{exercise}/edit', [ExerciseController::class, 'edit']);
        Route::put('/{exercise}/edit', [ExerciseController::class, 'update']);
        
        // Elimina un esercizio
        Route::delete('/{exercise}/delete', [ExerciseController::class, 'destroy']);
    });

    /* ROTTE DI FEDERICO */

    Route::get('/CreaEsercitazione', [EsercitazioneController::class, 'crea'])->name('crea_esercitazione');
    Route::post('/GeneraEsercitazione', [EsercitazioneController::class, 'genera'])->name('genera_esercitazione');


});

require __DIR__.'/auth.php';    //Istruzione per includere tutte le rotte definite nel file auth.php
