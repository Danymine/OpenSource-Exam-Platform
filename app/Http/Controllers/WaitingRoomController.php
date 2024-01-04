<?php

namespace App\Http\Controllers;

use App\Models\WaitingRoom;
use Illuminate\Http\Request;
use App\Models\Practice;

class WaitingRoomController extends Controller
{
    public function index()
    {
        
    }
    public function show($key)
    {
        $practice = Practice::where('key', '=', $key)->first();
        return view('waiting-room', ['practices' => $practice]);
    }

    public function status($key){

        $practice = Practice::where('key', '=', $key)->first();

        if (!$practice) {
            // Se la pratica non esiste, restituisci un errore 404
            return response()->json(['error' => 'Pratica non trovata'], 404);
        }

        // Ottieni il valore di 'allowed'
        $allowed = $practice->allowed;

        // Restituisci il risultato in formato JSON
        return response()->json(['status' => $allowed]);
    }

    public function participants($key){

        $practice = Practice::where('key', $key)->with('userwaiting')->firstOrFail();
        $array = [];
       
        $array = [];
        foreach ($practice->userwaiting as $user) {

            array_push($array, $user->name);
        }
        // Restituisci il risultato in formato JSON
        return response()->json(['user' => $array]);
    }

    public function empower($key){

        $practice = Practice::where('key', '=', $key)->first();

        $practice->allowed = 1;
        $practice->save();

        $practice->userwaiting()->detach();

        return redirect()->back()->with('success', 'Gli studenti hanno iniziato la prova');  
    }
}
