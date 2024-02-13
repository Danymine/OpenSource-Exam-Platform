<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Practice;
use App\Models\Exercise;
use App\Models\Answer;
use App\Models\Delivered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

    public function index($type) 
    {
        // Recupera tutte le pratiche associate all'utente autenticato di tipo specificato
        $query = Practice::where('user_id', Auth::id())
                         ->where('type', $type);
    
        // Applica i filtri se sono stati inviati
        if (request()->has('subject')) {
            $query->where('subject', request('subject'));
        }
    
        if (request()->has('difficulty')) {
            $query->where('difficulty', request('difficulty'));
        }
    
        // Pagina i risultati dopo il filtraggio
        $practices = $query->paginate(10);
    
        // Recupera le materie distinte per popolare il menu a tendina dei filtri
        $subjects = Practice::distinct('subject')->pluck('subject');
    
        return view('practices', [
            'practices' => $practices, 
            'type' => $type,
            'subjects' => $subjects, 
        ]);
    }

    public function create($type = 'default')
    {
        $user = Auth::user(); // Supponendo che tu abbia un sistema di autenticazione e che stia utilizzando Laravel
        $exercises = $user->exercises()->get();
        $subjects = $user->exercises()->distinct('subject')->pluck('subject');
    
        return view('practice_create', ['exercises' => $exercises, 'subjects' => $subjects, 'type' => $type]);
    } 
    
    public function generatePracticeWithFilters(Request $request, $type)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'difficulty' => 'string',
            'subject' => 'string',
            'max_questions' => 'nullable|integer|min:1',
            'max_score' => 'nullable|integer|min:1',
            'practice_date' => [
                'nullable',
                'date',
                'after_or_equal:' . now()->toDateString(),
            ],
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
    
        // Impostiamo la data di pratica come la data odierna se non specificata
        $practice_date = $validatedData['practice_date'] ?? now()->toDateString();
    
        $exerciseQuery = Auth::user()->exercises(); // Ottiene gli esercizi dell'utente corrente

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
    
        // Ottieni la data corrente per il fuso orario Roma. Questo è da cambiare nel momento in cui aggiungiamo l'internazionalizzazione.
        date_default_timezone_set('Europe/Rome');
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
            'total_score' => $maxScore,
            'key' => $key,
            'user_id' => Auth::id(),
            'allowed' => 0,
            'feedback_enabled' => $feedbackEnabled,
            'randomize_questions' => $randomizeQuestions,
            'generated_at' => $generatedDate,
            'practice_date' => $practice_date,
            'type' => $type,
            'public' => 0,
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
            'type' => $type, // Passa il parametro $type alla vista
        ]);
    }       
    
    public function exerciseList($type)
    {
        $user = Auth::user(); // Ottiene l'utente corrente
    
        // Ottiene solo gli esercizi dell'utente corrente
        $exercises = $user->exercises()->get();
        
        // Ottiene le materie degli esercizi
        $subjects = $user->exercises()->distinct('subject')->pluck('subject');
        
        // Ottiene i tipi di esercizi 
        $exerciseTypes = $user->exercises()->distinct('type')->pluck('type');
        
        return view('exercise_list', [
            'exercises' => $exercises,
            'subjects' => $subjects,
            'exerciseTypes' => $exerciseTypes,
            'type' => $type
        ]);
    }

    public function createExerciseSet(Request $request, $type)
    {
        // Validazione del form, se necessario
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'selected_exercises' => 'required|array',
            'max_score' => 'nullable|integer|min:1',
            'practice_date' => [
                'nullable',
                'date',
                'after_or_equal:' . now()->toDateString(),
            ],
        ]);
    
        $title = $validatedData['title'];
        $description = $validatedData['description'];
        $maxScore = $validatedData['max_score'];
        $practice_date = $validatedData['practice_date'] ?? null;
    
        $feedbackEnabled = $request->has('feedback');
        $randomizeQuestions = $request->has('randomize');
    
        // Impostiamo la data di pratica come la data odierna se non specificata
        $practice_date = $validatedData['practice_date'] ?? now()->toDateString();
    
        // Ottieni gli ID degli esercizi selezionati dal form
        $selectedExerciseIds = $request->input('selected_exercises');
    
        // Ottieni la data corrente per il fuso orario Roma. Questo è da cambiare nel momento in cui aggiungiamo l'internazionalizzazione.
        date_default_timezone_set('Europe/Rome');
        $generatedDate = now();
    
        // Ottieni gli esercizi selezionati dal database
        $selectedExercises = Exercise::whereIn('id', $selectedExerciseIds)->get();
    
        // Ottieni la difficoltà da ciascun esercizio e stampala
        foreach ($selectedExercises as $exercise) {
            $difficulty = $exercise->difficulty;
            // Ora puoi utilizzare $difficulty come stringa
        }
    
        // Calcola la somma degli score dei filtri
        $totalScoreSelected = $selectedExercises->sum('score');
    
        // Ottieni il massimo tra max_score e il totale dei punteggi degli esercizi selezionati
        $maxScore = $maxScore ?? $totalScoreSelected;
    
        // Assegna la materia comune a tutti gli esercizi selezionati
        $commonSubject = $selectedExercises->first()->subject;
    
        // Genera la chiave (presumo che tu abbia già implementato questa logica)
        $key = $this->generateKey();
    
        $newPractice = new Practice([
            'title' => $title,
            'description' => $description,
            'difficulty' => $difficulty,
            'subject' => $commonSubject,
            'total_score' => $maxScore,
            'key' => $key,
            'user_id' => Auth::id(), // Utilizza Auth::id() per ottenere l'ID dell'utente autenticato
            'allowed' => 0,
            'feedback_enabled' => $feedbackEnabled,
            'randomize_questions' => $randomizeQuestions,
            'generated_at' => $generatedDate,
            'practice_date' => $practice_date,
            'type' => $type,
            'public' => 0,
        ]); 
    
        $newPractice->save();
    
        // Modifica dei punteggi personalizzati degli esercizi nella pratica
        foreach ($selectedExercises as $exercise) {
            // Calcola il punteggio proporzionato per ciascun esercizio rispetto al max_score
            $customScore = round(($exercise->score / $totalScoreSelected) * $maxScore, 2);
            $newPractice->exercises()->attach($exercise->id, ['custom_score' => $customScore]);
        }
    
        // Recupero gli esercizi associati con i loro punteggi personalizzati
        $selectedExercisesWithCustomScores = $newPractice->exercises()->withPivot('custom_score')->get();
    
        return view('practice_new', [
            'newPractice' => $newPractice,
            'selectedExercisesWithCustomScores' => $selectedExercisesWithCustomScores,
            'type' => $type,
        ]);
    }

    public function show($type, Practice $practice)
    {
        $practice->load('exercises'); // Carica gli esercizi associati alla pratica
        return view('practice_show', ['practice' => $practice, 'type' => $type]);
    }

    public function edit($type, Practice $practice)
    {
        // Genera una nuova chiave univoca
        $newKey = $this->generateKey();
    
        // Duplica la pratica e assegna la nuova chiave
        $newPractice = $practice->replicate();
        $newPractice->key = $newKey;
    
        // Salva la nuova pratica con gli stessi esercizi della pratica originale
        $newPractice->save();
        $newPractice->exercises()->sync($practice->exercises);
    
        // Otteni gli ID degli esercizi presenti nella nuova pratica
        $newPracticeExerciseIds = $newPractice->exercises->pluck('id')->toArray();
    
        // Otteni tutte le subject dagli esercizi presenti nel database
        $allSubjects = Exercise::distinct()->pluck('subject')->toArray();
        $allTypes = Exercise::distinct()->pluck('type')->toArray();

        // Softdelete la pratica originale
        $practice->delete();
    
        // Passa la nuova pratica, la lista completa degli esercizi, gli ID degli esercizi e tutte le subject alla vista
        return view('practice_edit', [
            'practice' => $newPractice,
            'allExercises' => Exercise::all(),
            'newPracticeExerciseIds' => $newPracticeExerciseIds,
            'type' => $type,
            'subjects' => $allSubjects,
            'exerciseType' => $allTypes,
        ]);
    }    

    public function update(Request $request, $type, Practice $practice)
    {
        // Validate form data, adjust if necessary
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'difficulty' => 'string',
            'subject' => 'string',
            'total_score' => 'nullable|integer|min:1',
            'practice_date' => [
                'nullable',
                'date',
                'after_or_equal:' . now()->toDateString(),
            ],
        ]);
    
        $title = $validatedData['title'];
        $description = $validatedData['description'];
        $difficulty = $validatedData['difficulty'];
        $subject = $validatedData['subject'];
        $total_score = $validatedData['total_score'];
        $practice_date = $validatedData['practice_date'] ?? null;
    
        $feedbackEnabled = $request->has('feedback');
        $randomizeQuestions = $request->has('randomize');
    
        // Update practice attributes
        $updateData = [
            'title' => $title,
            'description' => $description,
            'difficulty' => $difficulty,
            'subject' => $subject,
            'total_score' => $total_score,
            'practice_date' => $practice_date,
            // ... (other existing update fields)
        ];
    
        $practice->update($updateData);
    
        // Update exercises for the practice
        $practice->exercises()->sync($request->input('exercise_ids', []));
    
        // Get selected exercises from the JSON array
        $selectedExercises = $request->input('selected_exercises', []);
    
        // Update selected exercises for the practice
        $practice->exercises()->sync($selectedExercises);
    
        // Assign custom scores to selected exercises
        $totalScoreSelected = $practice->exercises->sum('score');
        foreach ($practice->exercises as $exercise) {
            $customScore = round(($exercise->score / $totalScoreSelected) * $total_score, 2);
            $practice->exercises()->updateExistingPivot($exercise->id, ['custom_score' => $customScore]);
        }
    
        // Retrieve exercises associated with their custom scores
        $exercisesWithCustomScores = $practice->exercises()->withPivot('custom_score')->get();
        
        // Ricrea $newPractice dopo l'aggiornamento
        $newPractice = Practice::with('exercises')->find($practice->id);
    
        // Return the practice_new view with updated practice details
        return view('practice_new', [
            'newPractice' => $newPractice,
            'exercisesWithCustomScores' => $exercisesWithCustomScores,
            'type' => $type,
        ])->with('success', 'Pratica aggiornata con successo.');
    }
       
    public function duplicate(Request $request, $type, Practice $practice)
    {
        // Duplica la pratica
        $newPractice = $practice->replicate();
        $newPractice->title = $practice->title . ' (Copia)';
        $newPractice->key = $this->generateKey();
        $newPractice->allowed = 0; // Imposta allowed a 0
        $newPractice->save();
    
        // Duplica gli esercizi associati con i loro punteggi personalizzati
        foreach ($practice->exercises as $exercise) {
            $customScore = $exercise->pivot->custom_score ?? $exercise->score; // Usa il punteggio personalizzato se presente
            $newPractice->exercises()->attach($exercise->id, ['custom_score' => $customScore]);
        }
    
        return redirect()->route('practices.show', ['type' => $type, 'practice' => $newPractice->id])->with('success', 'Pratica duplicata con successo.');
    }    

    public function destroy($type, $id)
    {
        $practice = Practice::withTrashed()->findOrFail($id);

        // Dissocia gli esercizi collegati a questa pratica
        $practice->exercises()->detach();

        if ($practice->trashed()) {
            // Se la pratica è già "soft deleted", forza la cancellazione definitiva
            $practice->forceDelete();
        } else {
            // Altrimenti, esegui il soft delete impostando "deleted_at"
            $practice->delete();
        }

        return redirect()->route('practices.index', ['type' => $type])->with('success', 'Pratica eliminata correttamente.');
    }
    
    // NOTE: Questa funzione si occupa di ricevere dall'utente la Key del test alla quale vuole accedere 
    public function join(Request $request){

        if( $request->has('key') ){

            $validated = $request->validate([
                'key' => 'required|max:6|min:6|alpha_num:ascii',
            ]);
            //Anche questo da cambiare nel caso in cui utenti cercaressero di cambiare la propria locazione.
            date_default_timezone_set('Europe/Rome');
            $test = Practice::where('key', '=', $request->input('key'))->first();   //Vado a repire il test con quella key. (Se Esiste)
            if($test == NULL){

                return back()->withErrors(['error' => 'Key not found']);
            }
            else if( $test->practice_date != now()->toDateString() ){

                return back()->withErrors(['error' => "La data di esecuzione dell'esame non è oggi"]);
            }
            else{

                //Verifico che l'utente non abbia già effettuato quel test.
                $response = Delivered::where([
                    ['user_id', '=' , Auth::user()->id],
                    ['practice_id', '=', $test->id]
                ])->first();
                
                if($response == NULL){
                    
                    //Non trovando nulla sono sicuro. Ora verifico che sia già startata o meno. Se non lo è l'utente viene reinderizzo in waiting-room altrimenti direttamente al test.
                    if( $test->allowed == 0 ){
                    
                        Auth::user()->waitingroom()->attach($test->id);
                        return redirect()->route('waiting-room', ['key' => $test->key]);
                    }
                    else{

                        return redirect()->route('view-test', ['key' => $test->key]);
                    }
                }
                else{

                    return back()->withErrors(['error' => 'You have already taken part in this test']);
                }
            }
        }
        else{

            return back()->withErrors(['error' => 'Smettila di giocare con l\'ispeziona']);
        }
    }

    //NOTE: Mostra all'utente il test. Questa verrà richiamata dalla vista WaitingRoom attraverso la chiamata asincrona.
    public function showExam(string $key){

        $practice = Practice::where('key', '=', $key)->first();
        $exercise = $practice->exercises->toArray();    //Questo sarebbe da cambiare non c'è più una relazione diretta.
        if( $practice->randomize_questions == true ){

            shuffle($exercise); //Disordino gli esercizi.
        }

        return view('test', ['test' => $practice, 'exercises' => $exercise]);   
    }

    //NOTE:: Funzione interna che si occupa della correzione automatica di un test.
    private function AutoCorrect(int $practice_id){

        //Information practice
        $practice = Practice::where('id', '=', $practice_id)->first();
        $exercise = $practice->exercises->toArray();
        $score_max = $practice->total_score;

        $score_user = 0;
        $user_id = Auth::user()->id;

        $explanation = [];
        $correct = 0;

        //Prendo la consegna dal database
        $delivere = Delivered::where([
            ['practice_id','=', $practice_id],
            ['user_id', '=', $user_id]
        ])->first();


        for($i = 0; $i < count($exercise); $i++ ){

            $temporay = $score_user;
            
            $score_esercizio = $exercise[$i]["pivot"]["custom_score"];
            $correct_response = $exercise[$i]["correct_option"];
            
            $answer = Answer::where([
                ['delivered_id', '=', $delivere->id],
                ['exercise_id', '=', $exercise[$i]["id"]]
            ])->first();

            /*
            Arrivati a questo punto ho le risposte che l'utente ha dato ho gli esercizi effettivamente presenti nel test non rimane altro che effettuare la correzione
            che sarà fatta verificando che il contenuto di $correct_response sia uguale alla risposta che effettiamente ha dato l'utente.
            */
            if( $exercise[$i]["type"] == "Risposta Multipla" ){ //Da cambiare nel caso in cui sia Risposta Chiusa

                switch($correct_response){

                    case 'a':
                        if( $answer->response == $exercise[$i]["option_1"]){

                            $score_user += $score_esercizio;
                        }
                        else{

                            //Logica relative domande alla quale se sbagli togli uno score.
                        }
                        $response = $exercise[$i]["option_1"];
                    break;

                    case 'b':
                        if( $answer->response == $exercise[$i]["option_2"]){

                            $score_user += $score_esercizio;
                        }
                        else{

                            //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                        }
                        $response = $exercise[$i]["option_2"];
                    break;

                    case 'c':
                        if( $answer->response == $exercise[$i]["option_3"] ){

                            $score_user += $score_esercizio;
                        }
                        else{

                            //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                        }
                        $response = $exercise[$i]["option_3"];
                    break;

                    case 'd':
                        if( $answer->response == $exercise[$i]["option_4"] ){

                            $score_user += $score_esercizio;
                        }
                        else{

                            //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                        }
                        $response = $exercise[$i]["option_4"];
                    break;
                }
            }
            else{

                //Risposta vero o falso.
                if( $correct_response == $answer->response )
                {

                    $score_user += $score_esercizio;
                }
                else{

                    //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                }
            }

            //Temporary memorizza lo score dell'utente sull'iterazione precedente se questo valore è == vuol dire che l'utente ha sbagliato l'esercizio e quindi aggiungo la spiegazione all'email.
            if( $temporay == $score_user ){ 

                array_push($explanation, [$exercise[$i]["question"], $answer->response, $response, $exercise[$i]["explanation"]]); 
            }
        }
            
        //Qui al posto di questo va messo quello del test $practice->feedback
        Mail::to(Auth::user())
        ->send(new FeedbackEmail(Auth::user(), $practice, $score_user, $explanation));

        //Qui nel caso occorre implementare la gestione del libretto dello studente visto che possediamo già il suo voto.
        
        return $score_user;
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
        
        date_default_timezone_set('Europe/Rome');
        $newDelivered = new Delivered([
            'user_id' => $user_id,
            'practice_id' => $practice_id,
        ]);

        $newDelivered->save();

        for($i = 0; $i < count($array_id); $i++){

            $test = new Answer([
                'delivered_id' => $newDelivered->id,
                'response' => $array_response[$array_id[$i]],
                'exercise_id' => $array_id[$i]
            ]);
            $test->save();
        }
        

        //Verifico se il test preve l'invio automatico del feedback.
        if( $feedback->feedback_enabled == false ){

            return redirect()->route('ciao')->withErrors(['error' => 'Invio avvenuto con successo']);
        }
        else{

            $newDelivered->valutation = $this->AutoCorrect($practice_id);
            $newDelivered->save();
            return redirect()->route('ciao')->withErrors(['error' => 'Invio avvenuto con successo']);
        }
    }
    
    public function showHistoryExame(){

        //Mostriamo gli esami che hanno qualcuno che ha consegnato, anche quelli cancellati quindi, solo dell'utente che fa la richiesta.   
        $exames = Practice::where([
            ['type',"=",'esame'],
            ['user_id', '=', Auth::user()->id],
            ['public', '=', 1]
            ])->simplePaginate(15);

        
        return view('showHistory', ['tests' => $exames]);
    }

    public function showHistoryPractice(){

        $practices = Practice::where([
            ['type',"=",'esercitazione'],
            ['user_id', '=', Auth::user()->id],
            ['public', '=', 1]
            ])->simplePaginate(15);

        
        return view('showHistory', ['tests' => $practices]);

    }

    public function stats( Practice $practice ){
        
        //Servono tutte le consegne con i rispettivi utenti che hanno consegnato
        $delivereds = $practice->delivereds()->with('user')->get();
        return view('stats', ['practice' => $practice, 'delivereds' => $delivereds]);
    }
}
