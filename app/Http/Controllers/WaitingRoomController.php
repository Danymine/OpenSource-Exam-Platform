<?php

namespace App\Http\Controllers;

use App\Models\WaitingRoom;
use Illuminate\Http\Request;
use App\Models\Practice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $status = Auth::user()->waitingroom()->where('practice_id', $practice->id)->first()->pivot->status;
        $kicked = DB::table('waiting_rooms')->where('user_id', Auth::user()->id)->exists();
        
        if (!$practice) {
            // Se la pratica non esiste, restituisci un errore 404
            return response()->json(['error' => 'Pratica non trovata'], 404);
        }

        $allowed = $practice->allowed;
        if( $allowed == 1 && $status != "wait"){

            return response()->json(['status' => 'allowed']);
        }

        if (!$kicked) {
            return response()->json(['status' => 'kicked']);
        }

        return response()->json(['status' => 'wait']); 
    }

    public function participants(Practice $practice) {
        
        $participants = [
            'status_practice' => $practice->allowed ? true : false,
            'data' => [],
        ];
        foreach ($practice->userwaiting as $user) {

            $delivered = $user->delivereds()->where('practice_id', $practice->id)->exists();
            $deliveredStatus = $delivered ? "Consegnato" : "Non Consegnato";

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'first_name' => $user->first_name,
                'status' => $user->pivot->status,
                'delivered' => $deliveredStatus,
            ];

            array_push($participants['data'], $userData);
        }
        // Restituisci il risultato in formato JSON
        return response()->json(['participants' => $participants]);
    }
    
    public function empower(Practice $practice){

        if( $practice->user_id == Auth::user()->id ){
        
            $practice->allowed = 1;
            $practice->save();

            foreach ($practice->userwaiting as $user_wait) {

                $user_wait->waitingroom()->updateExistingPivot($practice->id, ['status' => 'execute']);
            }

            return redirect()->back()->with('success', 'Gli studenti hanno iniziato la prova');  
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
}
