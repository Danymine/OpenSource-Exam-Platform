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
use Illuminate\Support\Arr;


class PracticeController extends Controller
{
    //Funzione interna di generazione Key.
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

    private function selectExercises($exercises, $targetScore, $selectedExercises = []) {
        foreach ($exercises as $exercise) {
            // Aggiungi l'esercizio corrente alla selezione
            $selectedExercises[] = $exercise;
    
            // Calcola la somma dei punteggi degli esercizi selezionati
            $selectedScore = collect($selectedExercises)->sum('score');
    
            // Se la somma dei punteggi è uguale al target, restituisci gli esercizi selezionati
            if ($selectedScore == $targetScore) {
                return $selectedExercises;
            }
    
            // Se la somma dei punteggi è inferiore al target, seleziona ulteriori esercizi
            if ($selectedScore < $targetScore) {
                $remainingExercises = $exercises->diff($selectedExercises);
                $result = selectExercises($remainingExercises, $targetScore, $selectedExercises);
                if ($result) {
                    return $result;
                }
            }
    
            // Se la somma dei punteggi è maggiore del target, rimuovi l'ultimo esercizio aggiunto e continua
            array_pop($selectedExercises);
        }
    
        // Se non è possibile trovare una combinazione di esercizi, restituisci null
        return null;
    }

    //Generazione automatica.
    public function  create_automation(){
        
        return view('automation_create');
    }

    public function save_automation(Request $request){

        
        $validatedData = $request->validate([
            'title' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
            'subject' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
            'description' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:512',
            'time' => 'nullable|numeric|min:1',
            'practice_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->toDateString(),
            ],
            'feedback' => 'nullable|numeric|min:0|max:1',
            'randomize_questions' => 'required|numeric|min:0|max:1',
            'difficulty' => 'required|string|in:Bassa,Media,Alta',
            'total_score' => 'required|numeric|min:1|max:1000',
            'type' => 'required|string|in:Exam,Practice',
        ]);

        $exerciseQuery = Auth::user()->exercises()
            ->when($validatedData["feedback"] == 1, function ($query) {
                return $query->whereIn('type', ['Risposta Multipla', 'Vero o Falso']);
            });
    

        $exerciseQuery->where('difficulty', $validatedData['difficulty']);
        $exerciseQuery->where('subject', $validatedData['subject']);

        $exercises = $exerciseQuery->get();

        if( !$exercises->isEmpty() ){

            //Ora che sono qui ho x esercizi che rispettano quei criteri non resta che scegliere quelli adatti a raggiungere lo score che ha inserito. (Se possibile se non è possibile restituiamo errore)
            $totalScoreFiltered = $exercises->sum('score');
            if( $totalScoreFiltered >= $validatedData["total_score"]){

                //Allora posso andare avanti nella creazione ora sono sicuro di poterlo soddisfare in qualche modo.
                $selectedExercises = $this->selectExercises($exercises, $validatedData["total_score"]);
                if ($selectedExercises) {

                    date_default_timezone_set('Europe/Rome');
                    $generatedDate = now();
                    $key = $this->generateKey();
                
                    $newPractice = new Practice($validatedData);

                    $newPractice->user_id = Auth::user()->id;
                    $newPractice->key = $this->generateKey();
                    $newPractice->allowed = 0;
                    $newPractice->public = 0;

                    $newPractice->save();
                    
                    foreach ($selectedExercises as $exercise) {

                        $newPractice->exercises()->attach($exercise->id);
                    }

                    if($newPractice->type == "Exam") return redirect()->route('exam.index');

                    return redirect()->route('practices.index');
                } 
                else {

                    return back()->withErrors(trans('Non è possibile formare un test con lo score desiderato con gli esercizi disponibili'))->withInput();
                }

            }
            else{

                return back()->withErrors(trans('Con gli esercizi di cui disponi non riesci a raggiungere lo score desiderato. Il massimo raggiungibile è: ') . $totalScoreFiltered)->withInput();
            }
        }
        else{

            return back()->withErrors(trans('Non hai esercizi adatti alla creazione'))->withInput();
        }
                
    } 

    /* NOTE: INIZIO AREA ESAME
    ///
    ///
    ///
    ///
    ///
    */

    public function examIndex() 
    {
        // Determina il tipo di pratica come "exam"
        $type = 'Exam';
    
        // Recupera tutte le pratiche associate all'utente autenticato di tipo "exam"
        $practices = Practice::where('user_id', Auth::id())
                             ->where('type', $type)
                             ->get();
    
        // Estrai tutte le materie univoche dalle pratiche
        $subjects = $practices->pluck('subject')->unique();
    
        // Restituisci la vista degli esami con i dati recuperati
        return view('exame.exam-index', [
            'practices' => $practices, 
            'subjects' => $subjects, 
        ]);
    }  

    public function create_exame()
    {
        return view('exame.exame_create1');
    }

    public function create_exame2()
    {
        if(session()->has('exame_step1')){

            $exercises = Exercise::where('user_id', Auth::user()->id)->get();
            return view('exame.exame_create2', ['exercises' => $exercises]);
        }

        abort('403', "Non autorizzato.");
    }

    public function create_exame3()
    {
        if(session()->has('exame_step1') && array_key_exists('total_score', session()->get('exame_step1'))) return view('exame.exame_create3'); abort('403', "Non autorizzato.");
    }

    public function store_exame(Request $request){

        $validatedData = $request->validate([
            'title' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
            'subject' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
            'description' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:512',
        ]);

        $request->session()->put('exame_step1', $validatedData);

        return redirect()->route('exame_step2');

    }

    public function store_exame2(Request $request){

        $validatedData = $request->validate([
            'exercise' => 'array|required',
            'total_score' => 'required|numeric|min:1'
        ]);
        $exameStep1 = session()->get('exame_step1');
        
        $exameStep2 = array_merge($exameStep1, $validatedData);
        $request->session()->put('exame_step1', $exameStep2);

        return redirect()->route('exame_step3');
    }

    public function save(Request $request){

        $exameStep1 = session()->get('exame_step1');
        $validatedData = $request->validate([
            'time' => 'nullable|numeric|min:1',
            'practice_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->toDateString(),
            ],
            'feedback' => 'nullable|numeric|min:0|max:1',
            'randomize_questions' => 'required|numeric|min:0|max:1',
            'difficulty' => 'required|string|in:Bassa,Media,Alta'
        ]);

        $request->session()->forget('exame_step1');
        $date = array_merge($exameStep1, $validatedData);
        $practice = new Practice($date);
        
        $practice->user_id = Auth::user()->id;
        $practice->key = $this->generateKey();
        $practice->allowed = 0;
        $practice->type = "Exam";
        $practice->public = 0;

        $practice->save();

        for( $i = 0; $i < count($date["exercise"]); $i++ ){

            $practice->exercises()->attach($date["exercise"][$i]);
        }

        return redirect()->route('exam.index')->with('success', trans("L'esame è stato creato correttamente"));
    }

    public function exit_create(){

        if(session()->has('exercise_step1')){

            session()->forget('exercise_step1');
        }
    
        return redirect()->route('exam.index');
    }

    public function story_exame(){

        $practices = Practice::where('type', 'Exam')
            ->where('public', 1)
            ->where('user_id', Auth::user()->id)
            ->has('delivereds')
            ->get();
        return view('practice_history', ['practices' => $practices]);
    }

    /* NOTE: INIZIO AREA ESERCITAZIONI
    ///
    ///
    ///
    */

    public function practiceIndex() 
    {
        // Determina il tipo di pratica come "practice"
        $type = 'Practice';
    
        // Recupera tutte le pratiche associate all'utente autenticato di tipo "practice"
        $practices = Practice::where('user_id', Auth::id())
                     ->where('type', $type)
                     ->orderBy('created_at', 'DESC')
                     ->get();
    
        // Estrai tutte le materie univoche dalle pratiche
        $subjects = $practices->pluck('subject')->unique();
    
        // Restituisci la vista degli esami con i dati recuperati
        return view('practice.practice-index', [
            'practices' => $practices,
            'subjects' =>  $subjects,
        ]);
    }


    public function create_practice()
    {
        return view('practice.practice_create1');
    }

    public function create_practice2()
    {
        if (session()->has('exame_step1')) {

            $exercises = Exercise::where('user_id', Auth::user()->id)->get();
            return view('practice.practice_create2', ['exercises' => $exercises]);
        }
        
        abort('403', "Non autorizzato.");
    }

    public function create_practice3()
    {
        if (session()->has('exame_step1') && array_key_exists('total_score', session()->get('exame_step1'))) return view('practice.practice_create3'); abort('403', "Non autorizzato.");
    }

    public function store_practice(Request $request){

        $validatedData = $request->validate([
            'title' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
            'subject' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
            'description' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:512',
        ]);

        $request->session()->put('exame_step1', $validatedData);

        return redirect()->route('practice_step2');

    }

    public function store_practice2(Request $request){

        $validatedData = $request->validate([
            'exercise' => 'array|required',
            'total_score' => 'required|numeric|min:1'
        ]);
        $exameStep1 = session()->get('exame_step1');
        
        $exameStep2 = array_merge($exameStep1, $validatedData);
        $request->session()->put('exame_step1', $exameStep2);

        return redirect()->route('practice_step3');
    }

    public function save_practice(Request $request){

        $exameStep1 = session()->get('exame_step1');
        $validatedData = $request->validate([
            'time' => 'nullable|numeric|min:1',
            'practice_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->toDateString(),
            ],
            'feedback' => 'nullable|numeric|min:0|max:1',
            'randomize_questions' => 'required|numeric|min:0|max:1',
            'difficulty' => 'required|string|in:Bassa,Media,Alta'
        ]);

        $request->session()->forget('exame_step1');
        $date = array_merge($exameStep1, $validatedData);
        $practice = new Practice($date);
        
        $practice->user_id = Auth::user()->id;
        $practice->key = $this->generateKey();
        $practice->allowed = 0;
        $practice->type = "Practice";
        $practice->public = 0;

        $practice->save();

        for( $i = 0; $i < count($date["exercise"]); $i++ ){

            $practice->exercises()->attach($date["exercise"][$i]);
        }

        return redirect()->route('practices.index')->with('success', trans("L'esercitazione è stata creata con successo"));
    }

    public function exit_create_practice(){

        if(session()->has('exercise_step1')){

            session()->forget('exercise_step1');
        }
    
        return redirect()->route('practices.index');
    }

    public function story_practice(){

        $practices = Practice::where('type', 'Practice')
            ->where('public', 1)
            ->where('user_id', Auth::user()->id)
            ->has('delivered')
            ->get();
        return view('practice_history', ['practices' => $practices]);
    }


    /* NOTE:: Comuni. Funzioni che hanno valenza per entrambe
    ///
    ///
    ///
    ///
    */
    
    public function show(Practice $practice)
    {   
        if( $practice->user_id == Auth::user()->id ){

            $practice->load('exercises'); // Carica gli esercizi associati alla pratica
            return view('practice_show', ['practice' => $practice]);
        }
        else{

            abort('403', "Non autorizzato.");
        }
    }

    public function edit(Practice $practice)
    {
        if( Auth::user()->id == $practice->user_id ){

            return view('practice_edit', ['practice' => $practice, 'availableExercises' => Auth::user()->exercises]);
        }
        
        abort('403', "Non autorizzato");
    }    

    public function update_details(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|numeric|min:1',
            'title' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
            'subject' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:255',
            'description' => 'required|string|regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\?]+$/|max:512',
            'time' => 'nullable|numeric|min:1',
            'practice_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->toDateString(),
            ],
            'feedback_enabled' => 'nullable|numeric|min:0|max:1',
            'randomize_questions' => 'required|numeric|min:0|max:1',
            'difficulty' => 'required|string|in:Bassa,Media,Alta'
        ]);
        
        $practice = Practice::findOrFail($validatedData['id']);
        $validatedDataWithoutId = Arr::except($validatedData, ['id']);
        if( $practice->user_id == Auth::user()->id ){

            if( $practice->delivereds->isEmpty() ){

                $practice->update($validatedDataWithoutId);
                return redirect()->route('practices.edit', ['practice' => $practice]);
            }
            else{

                $newPractice = new Practice($validatedDataWithoutId);
                $newPractice->save();

                $newPractice->exercises()->attach($practice->exercises->pluck('id')); //pluck viene utilizzato per estrarre una singola key da una collezione in questo caso id
                $practice->delete();
                    
                return redirect()->route('practices.edit', ['practice' => $newPractice]);
       
            }
        }
        else{

            abort('403', "Modifica non autorizzata.");
        }
    }

    //Funzione per l'aggiunta di esercizi ad una practice esistente.
    public function add(Request $request, Practice $practice){

        $validatedData = $request->validate([
            'exercises' => 'array|required',
            'exercises.*' => 'required|numeric|min:1'
        ]);
        if( $practice->user_id == Auth::user()->id ){
            
            if( $practice->delivereds->isEmpty() ){

                $practice->exercises()->attach($validatedData["exercises"]);
                $scoreAdd = 0;
                for( $i = 0; $i < count($validatedData["exercises"]); $i++ ){

                    $exercise = Exercise::find($validatedData["exercises"][$i]);
                    if($exercise->type == "Risposta Aperta" && $practice->feedback_enabled == 1 ){

                        $practice->feedback_enabled = 0;
                    }
                    $scoreAdd += $exercise->score;
                }

                $practice->total_score += $scoreAdd;
                $practice->save();
            }
            else{

                $newPractice = $practice->replicate();
                $newPractice->title = $practice->title;
                if( $practice->key != NULL ){

                    $key = $practice->key;
                    $practice->key = NULL;
                    $newPractice->key = $key;
                    $practice->save();
                }
                else{

                    $newPractice->key = $this->generateKey();
                }
                $newPractice->save();
                $newPractice->exercises()->attach($practice->exercises->pluck('id')); //pluck viene utilizzato per estrarre una singola key da una collezione in questo caso id
                $scoreAdd = 0;
                for( $i = 0; $i < count($validatedData["exercises"]); $i++ ){

                    $exercise = Exercise::find($validatedData["exercises"][$i]);
                    $scoreAdd += $exercise->score;
                }

                $newPractice->total_score += $scoreAdd;
                $practice->delete();
            }

            return redirect()->route('practices.edit', ['practice' => $practice])->with('success', trans("L'esercizio è stato inserito con successo."));
        }

        abort('403', "Non autorizzato.");
    }

    //Funzione per rimuovere gli esercizi da una practice esistente.
    public function remove(Practice $practice, Exercise $exercise){

        if( $practice->user_id == Auth::user()->id ){

            if( $practice->delivereds->isEmpty() ){

                if ($practice->exercises->contains($exercise)) {
                
                    $practice->exercises()->detach($exercise);
                    $practice->total_score -= $exercise->score;
                    $practice->save();
                }
            }
            else{

                $newPractice = $practice->replicate();
                $newPractice->title = $practice->title;
                $newPractice->total_score -= $exercise->score;
                if( $practice->key != NULL ){

                    $key = $practice->key;
                    $practice->key = NULL;
                    $newPractice->key = $key;
                    $practice->save();
                }
                else{

                    $newPractice->key = $this->generateKey();
                }
                $newPractice->save();
                $newPractice->exercises()->detach($exercise); //pluck viene utilizzato per estrarre una singola key da una collezione in questo caso id
                $practice->delete();
            }

            return redirect()->route('practices.edit', ['practice' => $practice])->with('success', trans("L'esercizio è stato rimosso con successo."));
        }

        abort('403', "Non autorizzato");
    }
       
    public function duplicate(Practice $practice)
    {
        // Duplica la pratica
        $newPractice = $practice->replicate();
        $newPractice->title = $practice->title . ' (Copia)';
        $newPractice->key = $this->generateKey();
        $newPractice->allowed = 0; // Imposta allowed a 0
        $newPractice->save();
    
        // Duplica gli esercizi associati con i loro punteggi personalizzati
        foreach ($practice->exercises as $exercise) {
            $newPractice->exercises()->attach($exercise->id);
        }
    
        return redirect()->route('exam.index')->with('success', trans("La duplicazione è andata a buon termine"));
    }    

    public function destroy(Practice $practice)
    {
        //Elimino definitivamente se non è mai stata utilizzata.
        if( $practice->user_id == Auth::user()->id ){   //Questo viene fatto per evitare che l'utente possa eliminare practice di non sua competenza.

            $practiceWithDelivery = Practice::whereHas('delivereds')->find($practice->id);
            if( $practiceWithDelivery == NULL ){

                if( $practice->type == "Practice" ){

                    $practice->exercises()->detach();
                    $practice->forceDelete();
                    return redirect()->route('practices.index')->with('success', trans("L'esercitazione è stata eliminata con successo."));
                }
                else{

                    $practice->exercises()->detach();
                    $practice->forceDelete();
                    return redirect()->route('exam.index')->with('success', trans("L'esame è stato eliminato con successo."));
                }
            }
            else{
                
                if( $practice->type == "Practice" ){

                    $practice->delete();
                    return redirect()->route('practices.index')->with('success', trans("L'esercitazione è stata eliminata con successo."));
                }
                else{

                    $practice->delete();
                    return redirect()->route('exam.index')->with('success', trans("L'esame è stato eliminato con successo."));
                }
            }
        }
        else{

            return back()->withErrors('Non hai il permesso.');
        } 
        
    }
    
    // NOTE: Questa funzione si occupa di ricevere dall'utente la Key del test alla quale vuole accedere 
    public function join(Request $request){

        $validated = $request->validate([
            'key' => 'required|max:6|min:6|alpha_num:ascii',
        ]);

        //Utilizziamo un fuso orario comune per determinare se l'utente possa accedere
        date_default_timezone_set('Europe/Rome');
        $test = Practice::where('key', '=', $request->input('key'))->first();   //Vado a repire il test con quella key. (Se Esiste)
        if($test == NULL){

            return back()->withErrors(['error' => trans('Il Test che stai cercando non esiste o è scaduto.')]);
        }
        else if( $test->practice_date != now()->toDateString() ){

            return back()->withErrors(['error' => "La data di esecuzione dell'esame non è oggi."]);
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
                
                    $status = 'wait';
                    Auth::user()->waitingroom()->attach($test->id, ['status' => $status]);
                    return redirect()->route('waiting-room', ['key' => $test->key]);
                }
                else{

                    $status = 'wait';
                    Auth::user()->waitingroom()->attach($test->id, ['status' => $status]);
                    return redirect()->route('waiting-room', ['key' => $test->key]);
                }
            }
            else{

                return back()->withErrors(['error' => 'Hai già preso parte a questo Test']);
            }
        }
    }

    //NOTE: Mostra all'utente il test. Questa verrà richiamata dalla vista WaitingRoom attraverso la chiamata asincrona.
    public function showExam(string $key){

        $practice = Practice::where('key', '=', $key)->first();
        $exercise = $practice->exercises->toArray(); 
        if( $practice->randomize_questions == true ){

            shuffle($exercise); //Disordino gli esercizi.
        }

        return view('test', ['test' => $practice, 'exercises' => $exercise]);   
    }

    public function finish(Practice $practice){

        if( $practice->user_id == Auth::user()->id ){

            $practice->allowed = 0;
            $practice->save();
        }

        abort('403', "Non autorizzato");
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
        if( Auth::user()->id == $practice->user_id ){

            $delivereds = $practice->delivereds()->with('user')->get();
            return view('stats', ['practice' => $practice, 'delivereds' => $delivereds]);
        }
        
        abort('403', "Non autorizzato");
    }
}
