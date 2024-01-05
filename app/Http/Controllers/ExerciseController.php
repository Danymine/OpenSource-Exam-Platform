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
        if (Auth::check()) {
            $validatedData = $request->validate([
                'name' => 'required',
                'question' => 'required',
                'score' => 'required|numeric',
                'difficulty' => 'required',
                'subject' => 'required',
                'type' => 'required',
            ]);
    
            $exercises = new Exercise;
            $exercises->user_id = Auth::id(); // Imposta l'ID dell'utente autenticato
            $exercises->name = $request->input('name');
            $exercises->question = $request->input('question');
            $exercises->score = $request->input('score');
            $exercises->difficulty = $request->input('difficulty');
            $exercises->subject = $request->input('subject');
            $exercises->type = $request->input('type');
    
            if ($exercises->type === 'Risposta Aperta') {
                $exercises->save();
            } else {
                $exercises->correct_option = $request->input('correct_option');
                if ($exercises->type === 'Vero o Falso') {
                    $exercises->correct_option = $request->input('correct_answer');
                    $exercises->explanation = $request->input('explanation');
                }
                 else {
                    $options = $request->input('options');
                    $exercises->option_1 = $options[0];
                    $exercises->option_2 = $options[1];
                    $exercises->option_3 = $options[2];
                    $exercises->option_4 = $options[3];
                    $exercises->explanation = $request->input('explanation');
                }
                $exercises->save();
            }
        }
        return redirect()->route('showAllExercises');
    }
    
    

public function showAllExercises()
{
    $exercises = Exercise::where('user_id', Auth::id())->get(); // Recupera solo gli esercizi creati dall'utente autenticato
    return view('esercizi_biblioteca', compact('exercises'));
}


public function edit($id)
{
    $exercise = Exercise::findOrFail($id);
    return view('exercise_update', compact('exercise'));
}
public function update(Request $request, $id)
{
    $exercise = Exercise::findOrFail($id);
    $exercise->update($request->all());
    return redirect()->route('showAllExercises');
}

public function delete($id)
{
    $exercise = Exercise::findOrFail($id);
    
    // Controlla se l'utente è autorizzato a eliminare l'esercizio
    if (Auth::id() !== $exercise->user_id) {
        return redirect()->route('showAllExercises')->with('error', 'Non hai l\'autorizzazione per eliminare questo esercizio.');
    }

    // Elimina l'esercizio dal database
    $exercise->delete();

    return redirect()->route('showAllExercises')->with('success', 'Esercizio eliminato con successo.');
}
}

 /* FILE DI MARCO*/
 