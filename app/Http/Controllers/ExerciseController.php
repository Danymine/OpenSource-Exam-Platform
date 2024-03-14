<?php
namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Practice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExerciseController extends Controller
{   
    //Si occupa di mostrare gli esercizi del docente loggato.
    public function showAllExercises()
    {
        $exercises = Exercise::where('user_id', Auth::user()->id)->get();
        $subjects = Exercise::distinct()->pluck('subject');
        $types = Exercise::distinct()->pluck('type');
    
        return view('exercise.esercizi_biblioteca', ['exercises' => $exercises, 'subjects' => $subjects, 'types' => $types]);
    }    

    /* NOTE: Processo di creazione di un esercizio:
        Il processo di creazione di un esercizio è diviso in tre fasi: create, create2, e create3. In ciascuna di queste fasi, vengono raccolti dati diversi (indicati come x, y, z) che 
        vengono validati e inseriti in una sessione. Le informazioni raccolte durante queste fasi vengono gestite nei metodi store, store2, e save, rispettivamente. Inoltre, i dati inviati 
        dalla vista exercise_create sono gestiti nel metodo create, e così via.
    */
    public function create()
    {
        return view('exercise.exercise_create');
    }

    public function create2()
    {
        return view('exercise.exercise_create2');
    }

    public function create3()
    {
        return view('exercise.exercise_create3');
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
            'subject' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
            'type' => ['required', Rule::in(['Risposta Aperta', 'Risposta Multipla', 'Vero o Falso'])],
        ]);

        $request->session()->put('exercise_step1', $validatedData);

        return redirect()->route('exercise.step2');
    }

    public function store2(Request $request)
    {

        $exerciseStep1 = session()->get('exercise_step1');
        if( $exerciseStep1['type'] == "Risposta Aperta" ){

            
            $validatedData = $request->validate([
                'question' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?\,\"]+$/|max:255|min:5',
            ]);

        }
        else if( $exerciseStep1['type'] == "Risposta Multipla" ){

            $validatedData = $request->validate([
                'question' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?\,\"]+$/|max:255|min:5',
                'options' => 'array|required',
                'options.*' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
                'correct_option' => ['required', Rule::in(['a', 'b', 'c', 'd'])],
                'explanation' => 'nullable|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-,\'\?\.]+$/|max:255',
            ]);
        }
        else{

            $validatedData = $request->validate([
                'question' => [
                    'required',
                    'string',
                    'regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?\,\"]+$/',
                    'max:255',
                    'min:5',
                ],
                'correct_option' => ['required', Rule::in(['vero', 'falso'])],
                'explanation' => [
                    'nullable',
                    'string',
                    'regex:/^[A-Za-zÀ-ÿ0-9\s\-,\'\?\.]+$/',
                    'max:255',
                ],
            ]);            
        }

        $exerciseStep1 = array_merge($exerciseStep1, $validatedData);
        $request->session()->put('exercise_step1', $exerciseStep1);

        return redirect()->route('exercise.step3');
    }

    public function save(Request $request){

        $exerciseStep1 = session()->get('exercise_step1');
        $validatedData = $request->validate([
            'difficulty' => 'required|string|in:Bassa,Media,Alta',
            'score' => 'required|numeric|min:1|max:100',
        ]);
        $request->session()->forget('exercise_step1');
        $exerciseStep1 = array_merge($exerciseStep1, $validatedData);

        $exercise = new Exercise($exerciseStep1);
        $exercise->user_id = Auth::user()->id;

        if( $exercise->type == "Risposta Multipla" ){


            $exercise->option_1 = $exerciseStep1['options'][0];
            $exercise->option_2 = $exerciseStep1['options'][1];
            $exercise->option_3 = $exerciseStep1['options'][2];
            $exercise->option_4 = $exerciseStep1['options'][3];
        }
        
        $exercise->save();
        
        return redirect()->route('showAllExercises')->with('success', trans("L'esercizio è stato creato correttamente."));
    }

    // Gestisce il caso in cui l'utente esca dal processo di creazione.
    public function exit_create(){

        if(session()->has('exercise_step1')){

            session()->forget('exercise_step1');
        }
    
        return redirect()->route('showAllExercises');
    }

    /*NOTE: Modifica ed Elimina.
        Queste funzioni si occupano di permettere al Docente di modificare esercizi e eliminarli.
        Modifica (Funzionamento e Peculiarità):
            La modifica di un esercizio potrebbe apparire una cosa semplice ma noi siamo stati capaci di renderla complessa risolvendo a un particolare problema:
                1) La modifica di un esercizio esistente e utilizzato in esami/esercitazioni passate NON deve modificare il suo stato passato (L'esercizio in cui veniva chiesto di sommare 2 + 2 e ora modificato in 3 + 5 dovrà comunque rimanere 2 + 2 )
            Per risolvere questo problema è stato scelto di non modificare l'istanza dell'esercizio che l'utente intende modificare ma di creane uno nuovo con le stesse informazioni e usare soft
            delete sull'esercizio "originale" in questo modo i test passati continueranno a poterlo visualizzare al suo stato originale. (Ovviamente se l'esercizio non è mai stato utilizzato verrà modificata la sua vera instanza al fine di non occupare memoriza)

        Elimina (Funzionamento e Peculiarità):
            Al fine di risolvere il problema precedentemente esposto si è utilizzato la soft_delete. (Eccetto per le istanze di esercizi non utilizzati in esercitazioni/esami)
    */
    public function update(Request $request)
    {
        
        $validateFirst = $request->validate([
            'id' => 'required|numeric|min:1',
            'name' => 'required|string|max:255',
            'question' => 'required|string|min:10|max:500',
            'score' => 'required|numeric|min:1|max:100',
            'difficulty' => 'required|string|in:Bassa,Media,Alta',
            'subject' => 'required|string|regex:/^[A-Za-z0-9\s\-\'\?]+$/|max:255',
            'type' => ['required', Rule::in(['Risposta Aperta', 'Risposta Multipla', 'Vero o Falso'])],
        ]);

        $OldExercise = Exercise::findOrFail($validateFirst['id']);
       
        if( $OldExercise->user_id == Auth::user()->id )
        {
            $newExercise = new Exercise($validateFirst);
            $validatedData = [];
            if( $validateFirst["type"] === "Risposta Multipla" ){

                $validatedData = $request->validate([
                    'options' => 'required|array',
                    'options.*' => 'required|string|max:255',
                    'correct_option' => ['required', Rule::in(['a', 'b', 'c', 'd'])],
                    'explanation' => 'nullable|string|max:255',
                ]);

                $options = $validatedData['options'];

                $newExercise->option_1 = $options[0];
                $newExercise->option_2 = $options[1];
                $newExercise->option_3 = $options[2];
                $newExercise->option_4 = $options[3];

                $newExercise->correct_option = $validatedData['correct_option'];
                $newExercise->explanation = $validatedData['explanation'];           
                
            }
            else if( $validateFirst["type"] === "Vero o Falso" ) {

                $validatedData = $request->validate([

                    'correct_option' => ['required', Rule::in(['Vero', 'Falso'])],
                    'explanation' => 'nullable|string|max:255',
                ]);

                $newExercise->correct_option = $validatedData['correct_option'];
                $newExercise->explanation = $validatedData['explanation'];
            }

            $OldExercise->delete();
            $newExercise->user_id = Auth::user()->id;
            $newExercise->save();

            return redirect()->route('showAllExercises')->with('success', trans("L'esercizio è stato modificato con successo."));
        }
        else{

            return back()->withErrors(trans('Non hai il permesso.'));
        }

    }


    public function deleteExercise(Exercise $exercise)
    {   
        if( $exercise->user_id == Auth::user()->id ){   //Questo viene fatto per evitare che l'utente possa eliminare esercizi di non sua competenza.

            $exerciseWithPractice = Exercise::whereHas('practices')->find($exercise->id);
            if( $exerciseWithPractice == NULL ){

                $exercise->forceDelete();
                return redirect()->route('showAllExercises')->with('success', trans("L'esercizio è stato eliminato con successo."));
            }
            else{

                $exercise->delete();
                return redirect()->route('showAllExercises')->with('success', trans("L'esercizio è stato eliminato con successo."));
            }
        }
        else{

            return back()->withErrors('Non hai il permesso.');
        }
    }

}

 /* FILE DI MARCO*/
 