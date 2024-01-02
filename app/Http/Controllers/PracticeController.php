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
        $title = $request->input('title');
        $description = $request->input('description');
        $difficulty = $request->input('difficulty');
        $subject = $request->input('subject');
        $maxQuestions = $request->input('max_questions');
        $maxScore = $request->input('max_score');

        $exerciseQuery = Exercise::query(); //Questa è deprecata non si usa più.
        /* Tips:
            1)La difficoltà di una esercitazione non è la somma della difficoltà degli esercizi che la contengono almeno secondo me.
            Esempio:
            Esercitazione Nome: Analisi Difficolta: Difficile Numero Esercizi: 5 esercizi Punteggio: massimo 30
                1)Media
                2)Difficile
                3)Difficile
                4)Difficile
                5)Difficile
            Per me questo è difficile e deve essere possibile. Soprattutto in un discorso più ampio di regolazione.
            Ricordiamo che il punteggio attribuito all'esercizio lo assegna il docente e così la difficoltà possono esservi esercizi difficili
            che valgono differtenti punteggi. Se ad esempio il 2 vale 8 il 3 vale 6 il 4 vale 6 e il 5 vale 5 ottengo 25 di punteggio massimo con 
            questi se ad esempio io non ho un altro esercizio difficile da metterci ma ho uno medio da 5 ci metto quello.
            2)Sistema la scelta degli esercizi pls vedi tu come fare basta che la fai decente e che consideri parecchie cose. 
            3)Poi per quale ragione usi il metodo get per inviare così? Secondo me rovina l'url. 
            4)Una volta terminata la generazione di una esercitazione fai vedere l'esercitazione creata per quale motivo? Quella è una sorta
            di anteprima? Può essere modificabile da li? Perchè rimandarlo li e non alla lista di tutte le sue esercitazioni?
            Sarebbe interessante se tu quella vista la utilizzassi per tutte le esercitazione nel senso che se clicco in una esercitazione da
            quella lista mi facesse vedere tutte le sue caratteristiche e me le facesse modificare ma sarebbe anche interessante il concetto di anteprima
            che il docente controlla e se viene soddisfatto "l'approva" se non li piace li diamo la possibilità di cancellare esercizi e aggiungerne dei nuovi
            manualmente.
            Considerare l'aggiunta di esercitazioni equilibrate (Interessante)
            Considerare l'aggiunta di una sezione dedicate al come si vogliono gli esercizi (Aperte, Chiuse, Misto)?
        */

        if ($difficulty) {
            $exerciseQuery->where('difficulty', $difficulty);
        }

        if ($subject) {
            $exerciseQuery->where('subject', $subject);
        }

        $filteredExercises = $exerciseQuery->get();
        /*$totalScore = $filteredExercises->sum('score');

        if ($maxScore && $totalScore > $maxScore) {
            //return redirect()->back()->with('error', 'La somma dei punteggi supera il massimo consentito.');
            return "Errore non consentito";
        }
        */
        if ($maxQuestions) {
            $filteredExercises = $filteredExercises->take($maxQuestions);
        }

       // Calcola la somma degli score dei filtri e calcola il rapporto per il totale richiesto
        $totalScoreFiltered = $filteredExercises->sum('score');
        //$scoreRatio = $totalScore ? $totalScore / $totalScoreFiltered : 1;
        /*
        // Proporziona gli score degli esercizi
        foreach ($filteredExercises as $exercise) {
            $exercise->score *= $scoreRatio;
        }
        */
        $newPractice = Practice::create([
            'title' => $title,
            'description' => $description,
            'difficulty' => $difficulty,
            'subject' => $subject,
            'total_score' => $totalScoreFiltered,
            'key' => $key = $this->generateKey(),
            'user_id' => Auth::id()
        ]);
        
        //Qui dentro non ci entra affatto
        foreach ($filteredExercises as $exercise) {
            
            $newPractice->exercises()->attach($exercise->id);
        }


        return view('practice_new')->with('newPractice', $newPractice);
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
        return view('practice_show', ['practice' => $practice]);
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

    public function destroy(Practice $practice)
    {
        // Iniziare una transazione per assicurarsi che l'operazione sia atomica
        \DB::beginTransaction();

        try {
            // Elimina gli esercizi associati alla pratica
            $practice->exercises()->delete();

            // Ora elimina la pratica stessa
            $practice->delete();

            // Commit della transazione se tutto va bene
            \DB::commit();

            return redirect()->route('practices.index')->with('success', 'Practice deleted successfully');
        } catch (\Exception $e) {
            // Rollback della transazione in caso di errore
            \DB::rollback();
            return redirect()->back()->with('error', 'Error deleting practice');
        }
    }
    /*Da vedere come far partecipare l'utente quando farò la waiting room */
    public function join(Request $request){

        $test = new Practice;
        $test = Practice::where('key', '=', $request->input('key'))->first();
        //Gestione nel caso in cui lo stesso utente riprovi ad accedere
        return redirect()->route('test', ['test' => $test]);
    }

    public function test(Practice $test){
        
        $rand = true; //Logica per la randomizzazione dell'ordine degli esercizi.
        return view('test', ['test' => $test]);
    }
    public function send(Request $request){

        $array_id = $request->input('id');
        $array_response = $request->input('risposte');
        $user_id = Auth::id();
        $practice_id = $request->input('id_practices');
        $i = 0;
        
        /*
        Da capire se oltre alle risposte devo memorizzare anche la partecipazione dello studente a quella esercitazione. Anche perchè per come
        è strutturato ora il database uno stesso utente può partecipare al test più volte anche dopo averlo già consegnato e le risposte vengono
        accodate
        */
        for($i = 0; $i < count($array_id); $i++){

            $test = new Answer;
            $test->response = $array_response[$array_id[$i]];
            $test->user_id = $user_id;
            $test->exercise_id = $array_id[$i];
            $test->practice_id = $practice_id;

            $test->save();
        }

        if( $feedback == false ){

            return "Invio avvenuto con successo";
        }
        else{

            //Correzione automatica.
            /* Devo occuparmi della correzione. In Answer ho l'esercizio id utilizzerò quello per andare a reperire il punteggio standard dell'esercizio
            e a cui applicherò una variazione in base al tipo di valutazione dell'esercitazione/Esame considererò come se il punteggio espresso sia quello riferito
            in 30. 

            Bisogna anche repirire le spiegazioni e la correct option e l'email alla quale inviare l'esito. 
            */
            $score_user = 0;
            $practice = Practice::where('id', '=', $practice_id)->first();
            $feedback = true;  //Logica per l'invio automatico del feeback all'invio dell'esercitazione/esame $practice->feedback == false
            $score_max = $practice->total_score;
            $explanation = [];
            $correct = 0;

            for($i = 0; $i < count($array_id); $i++ ){

                $test = new Answer;
                $test = Answer::where([
                        ['exercise_id', '=', $array_id[$i]],
                        ['practice_id', '=', $practice_id],
                        ['user_id', '=', $user_id],
                    ])->first();
                
                $score_esercizio = $test->exercise->score;
                $correct_response = $test->exercise->correct_option;
                
                switch($correct_response){

                    case 'a':
                        if( $test->response == $test->exercise->option_1 ){

                            $score_user += $score_esercizio;
                            $correct += 1;
                        }
                        else{

                            //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                        }
                    break;

                    case 'b':
                        if( $test->response == $test->exercise->option_2 ){

                            $score_user += $score_esercizio;
                            $correct += 1;
                        }
                        else{

                            //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                        }
                    break;

                    case 'c':
                        if( $test->response == $test->exercise->option_3 ){

                            $score_user += $score_esercizio;
                            $correct += 1;
                        }
                        else{

                            //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                        }
                    break;

                    case 'd':
                        if( $test->response == $test->exercise->option_4 ){

                            $score_user += $score_esercizio;
                            $correct += 1;
                        }
                        else{

                            //Logica per le spiegazioni e relative domande alla quale se sbagli togli uno score.
                        }
                    break;
                }
            }
            
            if( $feedback == true) {    //Qui al posto di questo va messo quello del test $practice->feedback

                Mail::to(Auth::user())
                ->send(new FeedbackEmail(Auth::user(), $practice, $score_user));
            }
            else{
                
                return "Invio riuscito";
            }
        }
    }
}