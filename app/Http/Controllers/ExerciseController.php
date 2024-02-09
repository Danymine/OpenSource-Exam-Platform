<?php
namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExerciseController extends Controller
{
    public function create()
    {
        return view('exercise_create');
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'question' => 'required|string|min:10|max:500',
            'score' => 'required|numeric|min:1|max:100',
            'difficulty' => 'required|string|in:Bassa,Media,Alta',
            'subject' => 'required|string|regex:/^[A-Za-z0-9\s\-\'\?]+$/|max:255',
            'type' => ['required', Rule::in(['Risposta Aperta', 'Risposta Multipla', 'Vero o Falso'])],
            'explanation' => 'nullable|max:100',
            'correct_option' => $request->input('type') === 'Risposta Multipla' ? 'required|in:1,2,3,4' : 'nullable',
            'correct_option' => $request->input('type') === 'Vero o Falso' ? 'required|in:Vero,Falso' : 'nullable',
            'options.*' => $request->input('type') == 'Risposta Multipla' ? 'required|string|min:1|max:100' : 'nullable',
        ]);
           
    
        $exercise = new Exercise($request->all());
        $exercise->user_id = Auth::user()->id;
        $exercise->save();
    
        return redirect()->route('showAllExercises');
    }

    public function showAllExercises()
    {
        $exercises = Exercise::where('user_id', Auth::user()->id)->get(); // Recupera solo gli esercizi creati dall'utente autenticato
        return view('esercizi_biblioteca', ['exercises' => $exercises]);
    }

    public function update(Request $request, $id)
    {

        if (!is_numeric($id) || $id < 0) {

            abort(400, "Non giocare con l'ispeziona");
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'question' => 'required|string|min:10|max:500',
            'score' => 'required|numeric|min:1|max:100',
            'difficulty' => 'required|string|in:Bassa,Media,Alta',
            'subject' => 'required|string|regex:/^[A-Za-z0-9\s\-\'\?]+$/|max:255',
            'type' => ['required', Rule::in(['Risposta Aperta', 'Risposta Multipla', 'Vero o Falso'])],
            'explanation' => 'nullable|max:100',
            'correct_option' => $request->input('type') === 'Risposta Multipla' ? 'required|in:1,2,3,4' : 'nullable',
            'correct_option' => $request->input('type') === 'Vero o Falso' ? 'required|in:Vero,Falso' : 'nullable',
            'options.*' => $request->input('type') == 'Risposta Multipla' ? 'required|string|min:1|max:100' : 'nullable',
        ]);

        $originalExercise = Exercise::findOrFail($id);

        // Creazione di un nuovo esercizio con le modifiche richieste
        $newExercise = new Exercise($request->all());
        $newExercise->user_id = Auth::user()->id;
        $newExercise->save();

        // Soft delete dell'esercizio originale
        $originalExercise->delete();

        return redirect()->route('showAllExercises')->with('success', 'Esercizio modificato con successo.');
    }


    public function deleteExercise($id)
    {
        $exercise = Exercise::findOrFail($id);

        // Utilizza soft delete invece di delete
        $exercise->delete();

        return redirect()->route('showAllExercises')->with('success', 'Esercizio eliminato con successo.');
    }



}

 /* FILE DI MARCO*/
 