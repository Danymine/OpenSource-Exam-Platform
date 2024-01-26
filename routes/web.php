<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\WaitingRoomController;
use App\Http\Controllers\DeliveredController;


//Temporanea
Route::get('/errore', function (){

    return "Non hai l'account adatto per svolgere questa opzione.";
});

Route::get('/errore2', function (){

    return "Non puoi accedere a questo test";
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

    //Rotte di partecipazione Esame/Esercitazione
    Route::post('/join', [PracticeController::class, 'join'])->name('pratices.join');
    Route::get('/join/{key}', [PracticeController::class, 'showExam'])->name('test')->middleware('allowed');
    Route::post('/send', [PracticeController::class, 'send'])->name('pratices.send');
    Route::get('/waiting-room/{key}', [WaitingRoomController::class, 'show'])->name('waiting-room');
    Route::get('/status/{key}', [WaitingRoomController::class, 'status'])->name('status');
    Route::get('/user/{key}', [WaitingRoomController::class, 'participants'])->name('user');
    Route::get('/authorize/{key}', [WaitingRoomController::class, 'empower'])->name('empower');

    Route::get('/view/{id}', [DeliveredController::class, 'show'])->name('view-test');  //Vietare l'accesso a esami che non sia il suo attraverso un middleware
});

Route::middleware('auth', 'role')->group(function (){

    /* ROTTE DI MARCO */

    // Rotte per gli esercizi
    Route::prefix('exercises')->group(function () {
        // Mostra tutti gli esercizi
        Route::get('/esercizi_biblioteca', [ExerciseController::class, 'showAllExercises'])->name('showAllExercises');
        
        
        // Crea un nuovo esercizio
        Route::get('/create', [ExerciseController::class, 'create']);

        Route::post('/create', [ExerciseController::class, 'store'])->name("exercises.store");

        //Modifica esercizio
       Route::get('/edit-exercise/{id}', [ExerciseController::class, 'edit'])->name('editExercise');
       Route::post('/edit-exercise/{id}', [ExerciseController::class, 'update'])->name('editExercise');
       Route::put('/edit-exercise/{id}', [ExerciseController::class, 'update'])->name('exercises.update');
        // Elimina un esercizio
        Route::get('/{id}/delete', [ExerciseController::class, 'deleteExercise'])->name('deleteExercise');
       

    });

    /* ROTTE DI FEDERICO */
    Route::prefix('practices')->group(function () {
        Route::get('/', [PracticeController::class, 'index'])->name('practices.index');

        Route::get('/create', [PracticeController::class, 'create'])->name('practices.create');
        Route::get('/new', [PracticeController::class, 'generatePracticeWithFilters'])->name('practices.new');

        Route::get('/exercise-list', [PracticeController::class, 'exerciseList'])->name('exercise.list');
        Route::post('/create-exercise-set', [PracticeController::class, 'createExerciseSet'])->name('createExerciseSet');


        Route::get('/{practice}', [PracticeController::class, 'show'])->name('practices.show');
        Route::get('/{practice}/edit', [PracticeController::class, 'edit'])->name('practices.edit');
        Route::put('/{practice}', [PracticeController::class, 'update'])->name('practices.update');
        Route::delete('/{practice}', [PracticeController::class, 'destroy'])->name('practices.destroy');
    });
    
});
    

require __DIR__.'/auth.php';    //Istruzione per includere tutte le rotte definite nel file auth.php
