<?php

namespace App\Http\Controllers;

use App\Models\WaitingRoom;
use Illuminate\Http\Request;
use App\Models\Practice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class WaitingRoomController extends Controller
{
    public function show(string $key)
    {
        $practice = Practice::where('key', '=', $key)->first();
        date_default_timezone_set('Europe/Rome');
        if( $practice != NULL && (Auth::user()->roles == "Student" || Auth::user()->id == $practice->user_id)){

            if( $practice->practice_date == now()->toDateString() ){

                return view('waiting-room', ['practice' => $practice]);
            }
            
            return redirect()->route('dashboard')->withErrors(trans("La data del Test non è oggi. Non puoi avviare un Test che hai pianificato per una data diversa da oggi. Se vuoi puoi modificare la data."));
        }

        abort('404', "Non trovata.");
    }

    public function status(Practice $practice)
    {

        $waitingRoom = Auth::user()->waitingroom()->where('practice_id', $practice->id)->first();
        if ($waitingRoom != NULL ) {

            $status = $waitingRoom->pivot->status;
            if( $practice->allowed == 1 ){
    
                //La prova è avviata quindi è possibile entrarci ma dobbiamo constatare lo status dell'utente.
                switch($status){

                    case "execute":
                        return response()->json(['status' => 'allowed']);
                    break;

                    case "wait":
                        return response()->json(['status' => 'wait']); 
                    break;
                }
            }

            return response()->json(['status' => 'wait']); 
        }

        return response()->json(['status' => 'kicked']); 
    }

    public function status_test(Practice $practice)
    {

        //Siamo sicuri come la morte essendo dentro la pagina del test che practice sia stata avviata quindi che abbia allowed = 1; Se questo valore cambia allora è terminata.
        if( $practice->allowed == 0 ){

            return response()->json(['status' => 'finished']); 
        }
        else{

            //Altrimenti non è finita ma è possibile che lo studente sia stato kikkato quindi controlliamo la sua presenza in waiting room.
            $status = NULL;
            $waitingRoom = Auth::user()->waitingroom()->where('practice_id', $practice->id)->first();
            if( $waitingRoom === NULL ){

                return response()->json(['status' => 'kicked']); 
            }

            return response()->json(['status' => 'nothing']); 
        }
    }

    public function participants(Practice $practice) {
        
        $participants = [
            'status_practice' => $practice->allowed ? true : false,
            'data' => [],
        ];
        foreach ($practice->userwaiting as $user) {

            $delivered = $user->delivereds()->where('practice_id', $practice->id)->exists();
            $deliveredStatus = $delivered ? "Consegnato" : "Non Consegnato";

            if( $deliveredStatus == "Consegnato" ){

                $userData = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'first_name' => $user->first_name,
                    'status' => $deliveredStatus,
                ];
            }
            else{

                $userData = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'first_name' => $user->first_name,
                    'status' => $user->pivot->status,
                ];
            }

            array_push($participants['data'], $userData);
        }
        // Restituisci il risultato in formato JSON
        return response()->json(['participants' => $participants]);
    }
    
    public function empower(Request $request, Practice $practice){

        $validateData = $request->validate([
            'setDurationOption' => ['string', Rule::in(['yes', 'no']), 'nullable'],
            'time' => 'required_if:setDurationOption,yes|nullable|numeric|min:1',
        ]);        
        if ($practice->user_id == Auth::user()->id ) {

            $practice->allowed = 1;
            if( $validateData['setDurationOption'] === "yes" ) {

                $practice->time = $validateData['time'];
            }
            else{

                $practice->time = NULL;
            }
    
            $practice->save();
    
            foreach ( $practice->userwaiting as $user_wait ) {

                $user_wait->waitingroom()->updateExistingPivot($practice->id, ['status' => 'execute']);
            }
    
            return redirect()->back()->with('success', trans('Gli studenti hanno iniziato la prova'));
        }
        
        abort('403', "Non autorizzato.");
    }    

    public function kick($user_id){

        $waitingRoom = WaitingRoom::where('user_id', $user_id)->first();
        if ($waitingRoom) {

            $waitingRoom->delete();
            return response()->json(['message' => 'Lo studente è stato espulso dalla waiting room'], 200);

        } else {

            return response()->json(['error' => 'Lo studente non è presente nella waiting room'], 404);
        }
    }

    public function allowed($user_id){

        $waitingRoom = WaitingRoom::where('user_id', $user_id)->first();
        if ($waitingRoom) {

            $waitingRoom->status = 'execute';
            $waitingRoom->save();
            return response()->json(['message' => 'Lo studente è stato ammesso alla prova'], 200);

        } else {

            return response()->json(['error' => 'Lo studente non è presente nella waiting room'], 404);
        }
    }

    public function cancel(Practice $practice){

        if( $practice->user_id == Auth::user()->id ){

            $practice->userwaiting()->detach();
            
            return redirect()->route('dashboard');
        }
        
        abort('403', "Non autorizzato.");
    }

    public function terminateTest(Practice $practice){
        
        // Verifica se l'utente che sta cercando di terminare il test è l'insegnante
        if ($practice->user_id == Auth::user()->id) {
            // Imposta lo stato della pratica come non più consentito
            $practice->allowed = 0;
            $practice->key = NULL;
            $practice->save();

            return redirect()->route('dashboard');        
        }
    
        // Ritorna una risposta di errore se l'utente non è autorizzato
        abort(403, 'Non sei autorizzato a terminare questa prova');
    }

}
