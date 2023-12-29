<?php
namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    public function create()
    {
        return view('exercise_create');
    }



        public function store(Request $request)
    {
    if (Auth::check()) { // Controlla se l'utente è autenticato
        $validatedData = $request->validate([
        'name' => 'required',
        'question' => 'required',
        'score' => 'required|numeric',
        'difficulty' => 'required',
        'subject' => 'required',
        'type' => 'required',
        // Aggiungi ulteriori regole di validazione per le opzioni in base al tipo di esercizio, se necessario
    ]);
   
    $exercises = new Exercise;
    $exercises->user_id = Auth::id(); // Imposta l'ID dell'utente autenticato
    $exercises->name = $request->input('name');
    $exercises->question = $request->input('question');
    $exercises->score = $request->input('score');
    $exercises->difficulty = $request->input('difficulty');
    $exercises->subject = $request->input('subject');
    $exercises->type = $request->input('type');
    if ($exercises->type=='risposta_aperta'){
        
        $exercises->save();
    }
    else
    {
        $exercises->correct_option = $request->input('correct_option');
        $options = $request->input('options');
        $exercises->option_1 = $options[0];
        $exercises->option_2 = $options[1];
        $exercises->option_3 = $options[2];
        $exercises->option_4 = $options[3];
    }
    
    /*PER CORRECT OPTION HO MESSO IL NUMERO DELL'OPZIONE CORRETTA*/

    
    $exercises->save();

    }
}
    

public function showAllExercises()
{
    $exercises = Exercise::where('user_id', Auth::id())->get(); // Recupera solo gli esercizi creati dall'utente autenticato
    return view('esercizi_biblioteca', compact('exercises'));
}


    
}

 /* FILE DI MARCO*/
 