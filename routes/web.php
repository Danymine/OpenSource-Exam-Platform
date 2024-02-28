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
Route::get('/', function(){

    return redirect('it');
});

Route::prefix('{locale}')
    ->middleware(Localization::class)
    ->group(function (){

    Route::get('/', function () {
        return view('navigation.welcome');
    })->name('ciao');

    //Rotta per raggiungere la propria dashboard questa si occupera di fornire SOLO la dashboard visibile al tipo di utente che la richiede
    Route::get('/dashboard', function () {

        return view('navigation.dashboard');

    })->middleware(['auth','verified'])->name('dashboard');

    //Questa rotta va bene sia per studenti che per il docente che per l'amministratore nel caso.
    Route::middleware('auth')->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        //Rotte di partecipazione Esame/Esercitazione
        Route::post('/join', [PracticeController::class, 'join'])->name('pratices.join');
        
        Route::get('/view-test/{key}', [PracticeController::class, 'showExam'])->name('view-test')->middleware('allowed');

        Route::post('/send', [PracticeController::class, 'send'])->name('pratices.send');

        //Rotta di partecipazione alla waiting-room (Avvio esame per il docente.) Questa rotta è disponibile solo per studenti o per il docente che ha creato la Practice con quella Key.
        Route::get('/waiting-room/{key}', [WaitingRoomController::class, 'show'])->name('waiting-room');

        Route::get('/status/{practice}', [WaitingRoomController::class, 'status'])->name('status');

        //Mostra i dettagli della consegna. (Le risposte e se presenti voti o altro.)
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

        /* ROTTE DI MARCO */

        // Rotte per gli esercizi
        Route::prefix('exercises')->group(function () {

            // Mostra tutti gli esercizi
            Route::get('/', [ExerciseController::class, 'showAllExercises'])->name('showAllExercises');

            // Crea un nuovo esercizio
            //Step - 1
            Route::get('/create-first', [ExerciseController::class, 'create'])->name('exercise.step1');
            Route::post('/create-first', [ExerciseController::class, 'store'])->name("create_step_1");

            //Step - 2
            Route::get('/create-second', [ExerciseController::class, 'create2'])->name('exercise.step2')->middleware('checkLocalStorage');
            Route::post('/create-second', [ExerciseController::class, 'store2'])->name("create_step_2");

            //Step - 3
            Route::get('/create-third', [ExerciseController::class, 'create3'])->name('exercise.step3')->middleware('checkStep');
            Route::post('/save-exercise', [ExerciseController::class, 'save'])->name("save");

            //Exit Process
            Route::get('/exit', [ExerciseController::class, 'exit_create'])->name('exit_process_create');

            //Modifica esercizio
            Route::post('/edit-exercise', [ExerciseController::class, 'update'])->name('editExercise');
            Route::put('/edit-exercise', [ExerciseController::class, 'update'])->name('exercises.update');

            // Elimina un esercizio
            Route::get('/delete/{exercise}', [ExerciseController::class, 'deleteExercise'])->name('deleteExercise');

        });

    
        Route::prefix('exam')->group(function () {
            
            //Pagina iniziale mostra tutti gli esami
            Route::get('/', [PracticeController::class, 'examIndex'])->name('exam.index');

            //Processo di Creazione Manuale
            //Step - 1 Creazione Esame
            Route::get('/create-first', [PracticeController::class, 'create_exame'])->name('exame.step1');
            Route::post('/create-first', [PracticeController::class, 'store_exame'])->name('create_exame_step1');

            //Step - 2 Creazione Esame
            Route::get('/create-second', [PracticeController::class, 'create_exame2'])->name('exame_step2');
            Route::post('/create-second', [PracticeController::class, 'store_exame2'])->name('create_exame_step2');

            //Step - 3 Creazione Esame
            Route::get('/create-third', [PracticeController::class, 'create_exame3'])->name('exame_step3');
            Route::post('/save-practices', [PracticeController::class, 'save'])->name('save_exame');

            //Exit Process
            Route::get('/exit', [PracticeController::class, 'exit_create'])->name('exit_exame_process');
            
        });

        Route::prefix('practice')->group(function () {
            
            //Pagina iniziale mostra tutte le practice
            Route::get('/', [PracticeController::class, 'practiceIndex'])->name('practices.index');   
            
            Route::get('/create-first', [PracticeController::class, 'create_practice'])->name('practice.step1');
            Route::post('/create-first', [PracticeController::class, 'store_practice'])->name('create_practice_step1');

            Route::get('/create-second', [PracticeController::class, 'create_practice2'])->name('practice_step2');
            Route::post('/create-second', [PracticeController::class, 'store_practice2'])->name('create_practice_step2');

            Route::get('/create-third', [PracticeController::class, 'create_practice3'])->name('practice_step3');
            Route::post('/save-practices', [PracticeController::class, 'save_practice'])->name('save_practice');

            //Exit Process
            Route::get('/exit', [PracticeController::class, 'exit_create_practice'])->name('exit_practice_process');

        });

        //Creazione Auotomatica.
        Route::get('/create-automation', [PracticeController::class, 'create_automation'])->name('create_automation');
        Route::post('/save-automation', [PracticeController::class, 'save_automation'])->name('save_automation');
        
        //Cancellazione
        Route::delete('/delete/{practice}', [PracticeController::class, 'destroy'])->name('practices.destroy');

        //Duplica
        Route::get('/duplicate/{practice}', [PracticeController::class, 'duplicate'])->name('practices.duplicate');

        //Mostra il contenuto di una Practice
        Route::get('/show/{practice}', [PracticeController::class, 'show'])->name('practices.show');

        //Modifica
        Route::get('/edit/{practice}', [PracticeController::class, 'edit'])->name('practices.edit');
        Route::put('/update', [PracticeController::class, 'update_details'])->name('practices.update.details'); //Modifica dettagli.
        Route::delete('/remove/{practice}/{exercise}', [PracticeController::class, 'remove'])->name('practices.remove_exercise'); //Togli Esercizi
        Route::post('/add-exercise/{practice}', [PracticeController::class, 'add'])->name('practices.add_exercises'); //Aggiungi esercizi.

        //Passiamo a gestire le consegne.
        //Pagina Iniziale
        Route::get('/view-delivered/{practice}', [DeliveredController::class, 'index'])->name('view-delivered');

        //Si occupa di memorizzare il voto.
        Route::post('/save-valutation/{delivered}', [DeliveredController::class, 'save'])->name('store-valutation');

        //Si occupa di pubblicare le valutazioni
        Route::get('/public/{practice}', [DeliveredController::class, 'public'])->name('public');

        //Passiamo a gestire la Waiting-Room e l'avvio dell'esame.
        Route::get('/authorize/{practice}', [WaitingRoomController::class, 'empower'])->name('start-test');

        //Annulla l'avvio di un test kikkando tutti gli studenti che tentavano di partecipare.
        Route::get('/cancel-start/{practice}', [WaitingRoomController::class, 'cancel'])->name('cancel-start');

        //Otteniamo gli utenti che cercano di partecipare alla Prova.
        Route::get('/user/{practice}', [WaitingRoomController::class, 'participants'])->name('fetch-partecipants');

        //Kick studenti
        Route::delete('/kick/{user_id}', [WaitingRoomController::class, 'kick'])->name('kick-partecipants');

        //Accetta Studenti
        Route::get('/allowed/{user_id}', [WaitingRoomController::class, 'allowed'])->name('allow-student');

        //Annulla l'avvio di un test kikkando tutti gli studenti che tentavano di partecipare.
        Route::get('/finish-test/{practice}', [PracticeController::class, 'finish'])->name('finish-test');

        Route::get('/view-exame-passed', [PracticeController::class, 'showHistoryExame'])->name('exame-passed');
        Route::get('/view-practice-passed', [PracticeController::class, 'showHistoryPractice'])->name('practice-passed');

        Route::get('/stats-practice/{practice}', [PracticeController::class, 'stats'])->name('stats');


    });

    Route::prefix('admin')->group(function () {
        Route::get('/richiedi-assistenza', [RequestController::class, 'showAssistanceRequestForm'])->name('createAssistanceRequest');
        Route::post('/richiedi-assistenza', [RequestController::class, 'createAssistanceRequest'])->name('storeAssistanceRequest'); 
        Route::get('/admin/requests', [AdminRequestController::class, 'index'])->name('admin.requests.index');
    });

    require __DIR__.'/auth.php';    //Istruzione per includere tutte le rotte definite nel file auth.php
    
});