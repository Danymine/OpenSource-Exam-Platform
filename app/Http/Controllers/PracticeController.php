<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Practice;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Answer;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackEmail;
use Carbon\Carbon;

class PracticeController extends Controller
{
    private function generateKey()
    {
        $alphabet = array_merge(range('a', 'z'), range('A', 'Z'));
        $key = "";
        $found = false;

        do {
            for ($i = 0; $i < 6; $i++) {
                $rand = rand(0, 1);
                if ($rand == 0) {
                    $number = rand(0, 9);
                    $key .= $number;
                } else {
                    $char = rand(0, count($alphabet) - 1);
                    $key .= $alphabet[$char];
                }
            }

            $result = DB::table('practices')->where('key', $key)->get();
            if ($result->count() == 0) {
                $found = true;
            }
        } while (!$found);

        return $key;
    }

    public function generatePracticeWithFilters(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'difficulty' => 'string',
            'subject' => 'string',
            'max_questions' => 'nullable|integer|min:1',
            'max_score' => 'nullable|integer|min:1',
            'practice_date' => 'nullable|date',
        ]);
    
        $title = $validatedData['title'];
        $description = $validatedData['description'];
        $difficulty = $validatedData['difficulty'];
        $subject = $validatedData['subject'];
        $maxQuestions = $validatedData['max_questions'];
        $maxScore = $validatedData['max_score'];
        $practice_date = $validatedData['practice_date'] ?? null;
    
        $feedbackEnabled = $request->has('feedback');
        $randomizeQuestions = $request->has('randomize');
    
        $exerciseQuery = Exercise::query();
    
        if ($difficulty) {
            $exerciseQuery->where('difficulty', $difficulty);
        }
    
        if ($subject) {
            $exerciseQuery->where('subject', $subject);
        }
    
        // Ottieni gli esercizi filtrati
        $filteredExercises = $exerciseQuery->get();
    
        // Limita il numero massimo di domande se specificato
        if ($maxQuestions && $filteredExercises->count() > $maxQuestions) {
            $filteredExercises = $filteredExercises->take($maxQuestions);
        }
    
        // Calcola la somma degli score dei filtri
        $totalScoreFiltered = $filteredExercises->sum('score');
    
        // Ottieni la data corrente
        $generatedDate = now();
    
        // Ottieni il massimo tra max_score e il totale dei punteggi degli esercizi filtrati
        $maxScore = $maxScore ?? $totalScoreFiltered;
    
        // Genera la chiave (presumo che tu abbia già implementato questa logica)
        $key = $this->generateKey();
    
        $newPractice = new Practice([
            'title' => $title,
            'description' => $description,
            'difficulty' => $difficulty,
            'subject' => $subject,
            'total_score' => $maxScore, // Imposta il punteggio totale della pratica come max_score
            'key' => $key,
            'user_id' => Auth::id(),
            'allowed' => 0,
            'feedback_enabled' => $feedbackEnabled,
            'randomize_questions' => $randomizeQuestions,
            'generated_at' => $generatedDate,
            'practice_date' => $practice_date,
        ]);
    
        $newPractice->save();
    
        // Modifica dei punteggi personalizzati degli esercizi nella pratica
        foreach ($filteredExercises as $exercise) {
            // Calcola il punteggio proporzionato per ciascun esercizio rispetto al max_score
            $customScore = round(($exercise->score / $totalScoreFiltered) * $maxScore, 2);
            $newPractice->exercises()->attach($exercise->id, ['custom_score' => $customScore]);
        }               
    
        // Recupero gli esercizi associati con i loro punteggi personalizzati
        $filteredExercisesWithCustomScores = $newPractice->exercises()->withPivot('custom_score')->get();
    
        return view('practice_new', [
            'newPractice' => $newPractice,
            'filteredExercises' => $filteredExercisesWithCustomScores,
        ]);
    }    

    public function create()
    {
        return view('practice_create');
    }

    public function index()
    {
        $practices = Practice::where('user_id', '=', Auth::id())->get();
        return view('practices', ['practices' => $practices]);
    }

    public function show(Practice $practice)
    {
        $practice = Practice::with('exercises')->findOrFail($practice->id);
        return view('practice_show', compact('practice'));
    }    

    public function edit(Practice $practice)
    {
        return view('practice_edit', ['practice' => $practice]);
    }

    public function update(Request $request, Practice $practice)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'difficulty' => 'required',
            'subject' => 'required',
            'total_score' => 'required',
        ]);

        $practice->update($validatedData);

        return redirect()->route('practices.show', $practice->id)->with('success', 'Practice updated successfully');
    }

    public function destroy($id)
    {
        $practice = Practice::findOrFail($id);
    
        // Dissocia gli esercizi collegati a questa pratica
        $practice->exercises()->detach();
    
        // Elimina la pratica
        $practice->delete();
    
        return redirect()->route('practices.index')->with('success', 'Pratica eliminata correttamente.');
    }
    
    // NOTE: Questa funzione si occupa di ricevere dall'utente la Key del test alla quale vuole accedere 
    public function join(Request $request){

        if( $request->has('key') ){

            $validated = $request->validate([
                'key' => 'required|max:6|min:6|alpha_num:ascii',
            ]);
            $test = new Practice;
            $test = Practice::where('key', '=', $request->input('key'))->first();   //Vado a repire il test con quella key. (Se Esiste)
            if($test == NULL){

                return back()->withErrors(['error' => 'Key not found']);
            }

            //Verifico che l'utente non abbia già effettuato quel test.
            $response = Answer::where([
                ['user_id', '=', Auth::id()],
                ['practice_id', '=', $test->id],
            ])->first();
            
            if($response == NULL){
                
                //Non trovando nulla sono sicuro. Ora verifico che sia già startata o meno. Se non è reinderizzo in waiting-room altrimenti direttamente al test.
                if( $test->allowed == 0 ){
                
                    Auth::user()->waitingroom()->attach($test->id);
                    return redirect()->route('waiting-room', ['key' => $test->key]);
                }
                else{

                    return redirect()->route('test', ['key' => $test->key]);
                }
            }
            else{

                return back()->withErrors(['error' => 'You have already taken part in this test']);
            }
        }
        else{

            return back()->withErrors(['error' => 'Smettila di giocare con l\'ispeziona']);
        }
    }

    //NOTE: Mostra all'utente il test. Questa verrà richiamata dalla vista WaitingRoom attraverso la chiamata asincrona.
    public function showExam($key){

        $practice = Practice::where('key', '=', $key)->first();
        $exercise = $practice->exercises->toArray();
        if( $practice->randomize_questions == true ){

            shuffle($exercise); //Disordino gli esercizi.
        }

        return view('test', ['test' => $practice, 'exercises' => $exercise]);   
    }

    //NOTE:: Funzione interna che si occupa della correzione automatica di un test.
    private function AutoCorrect(int $practice_id, int $user_id){

        $score_user = 0;
        $practice = Practice::where('id', '=', $practice_id)->first();
        $feedback = $practice->feedback_enabled;
        $exercise = $practice->exercises->toArray();
        $score_max = $practice->total_score;
        $explanation = [];
        $correct = 0;

        for($i = 0; $i < count($exercise); $i++ ){

            $temporay = $score_user;
            $test = new Answer;
            $test = Answer::where([
                    ['exercise_id', '=', $exercise[$i]['id']],
                    ['practice_id', '=', $practice_id],
                    ['user_id', '=', $user_id],
                ])->first();

            $score_esercizio = $exercise[$i]["pivot"]["custom_score"];
            $correct_response = $test->exercise->correct_option;
            
            switch($correct_response){

                case 'a':
                    if( $test->response == $test->exercise->option_1 ){

                        $score_user += $score_esercizio;
                    }
                    else{

                        //Logica relative domande alla quale se sbagli togli uno score.
                    }
                break;

                case 'b':
                    if( $test->response == $test->exercise->option_2 ){

                        $score_user += $score_esercizio;
                    }
                    else{

                        //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                    }
                break;

                case 'c':
                    if( $test->response == $test->exercise->option_3 ){

                        $score_user += $score_esercizio;
                    }
                    else{

                        //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                    }
                break;

                case 'd':
                    if( $test->response == $test->exercise->option_4 ){

                        $score_user += $score_esercizio;
                    }
                    else{

                        //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                    }
                break;
            }

            if( $feedback == true ){

                if( $temporay <= $score_user ){ //Minore o uguale nel caso in cui togliessimo punti su potenziali domande errate

                    array_push($explanation, [$test->response, $correct_response, $explanation]); 
                }
            }
        }
            
        //Qui al posto di questo va messo quello del test $practice->feedback
        Mail::to(Auth::user())
        ->send(new FeedbackEmail(Auth::user(), $practice, $score_user, $explanation));

        //Qui nel caso occorre implementare la gestione del libretto dello studente visto che possediamo già il suo voto.
    }

    //NOTE: Si occupa di memorizzare gli esercizi compilati. 
    public function send(Request $request){

        $validated = $request->validate([
            'id' => 'array|required',
            'id.*' => 'integer',
            'id_practices' => 'integer|required',
            'risposte' => 'array|required',
            'risposte.*' => 'string|max:255'
        ]);

        $array_id = $request->input('id');
        $array_response = array_map('htmlspecialchars', $validated['risposte']);    //Sostituiamo caratteri speciali.
        $user_id = Auth::id();
        $practice_id = $request->input('id_practices');
        $feedback = Practice::find($practice_id);
        $i = 0;

        //Inserisco tutte le risposte salvate nel DB
        for($i = 0; $i < count($array_id); $i++){

            $test = new Answer;
            $test->response = $array_response[$array_id[$i]];
            $test->user_id = $user_id;
            $test->exercise_id = $array_id[$i];
            $test->practice_id = $practice_id;

            $test->save();
        }

        //Verifico se il test preve l'invio automatico del feedback.
        if( $feedback->feedback_enabled == false ){

            return "Invio avvenuto con successo";
        }
        else{

            $this->AutoCorrect($test->practice_id, $user_id);
        }
    }
    
}
