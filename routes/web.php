<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\WaitingRoomController;
use App\Http\Controllers\DeliveredController;
use App\Http\Controllers\AdminRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequestController;


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
    Route::get('/view-test/{key}', [PracticeController::class, 'showExam'])->name('view-test')->middleware('allowed');


    Route::post('/send', [PracticeController::class, 'send'])->name('pratices.send');
    //Forse questo non dovrebbe essere relativo alla Practice ma al Delivered in quanto lui consegna la consegna non una practice.

    Route::get('/waiting-room/{key}', [WaitingRoomController::class, 'show'])->name('waiting-room');


    Route::get('/status/{test}', [WaitingRoomController::class, 'status'])->name('status');
    Route::get('/user/{test}', [WaitingRoomController::class, 'participants'])->name('user');
    Route::get('/authorize/{test}', [WaitingRoomController::class, 'empower'])->name('empower');

    Route::get('/view-details-delivered/{delivered}', [DeliveredController::class, 'show'])->name('view-details-delivered')->middleware('control'); //Il middleware permette di vedere i dettagli della consegna solo per gli utenti che l'hanno consegnata o per il docente che ha creato la practice alla quale si riferisce
    Route::get('/download-details-delivered/{delivered}', [DeliveredController::class, 'print'])->name('download-details-delivered')->middleware('control');
    Route::get('/download-correct-delivered/{delivered}', [DeliveredController::class, 'printCorrect'])->name('download-correct-delivered');
    Route::get('/aggiungi-utente', [UserController::class, 'showAddUserForm'])->name('show-add-user-form');
    Route::post('/aggiungi-utente', [UserController::class, 'aggiungiUtente'])->name('aggiungi-utente');
    Route::get('/user-list', [UserController::class, 'showUserList'])->name('user-list');
    Route::delete('/utenti/{id}', [UserController::class, 'destroy'])->name('delete-user');

    Route::get('/lista-utenti', [UserController::class, 'showUserListFromDb'])->name('users-list');

    Route::get('/modifica-utente/{id}', [UserController::class, 'editUserForm'])->name('edit-user-form');


    Route::post('/aggiorna-utente/{id}', [UserController::class, 'updateUser'])->name('update-user');

    Route::get('/annulla-modifiche', [UserController::class, 'cancelEdit'])->name('cancel-edit');

    Route::get('/search-user', [UserController::class, 'search'])->name('search-user');






 });

Route::middleware('auth', 'role')->group(function (){


    Route::get('/view-delivered/{practice}', [DeliveredController::class, 'index'])->name('view-delivered');
    Route::post('/save', [DeliveredController::class, 'save'])->name('store-valutation');
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
    Route::prefix('admin')->group(function () {
    Route::get('/richiedi-assistenza', [RequestController::class, 'showAssistanceRequestForm'])->name('createAssistanceRequest');
    Route::post('/richiedi-assistenza', [RequestController::class, 'createAssistanceRequest'])->name('storeAssistanceRequest'); 
    Route::get('/admin/requests', [AdminRequestController::class, 'index'])->name('admin.requests.index');
    });
});
    

require __DIR__.'/auth.php';    //Istruzione per includere tutte le rotte definite nel file auth.php