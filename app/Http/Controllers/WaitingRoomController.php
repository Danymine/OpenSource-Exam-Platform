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
    public function show(string $key)
    {
        $practice = Practice::where('key', '=', $key)->first();
        return view('waiting-room', ['practices' => $practice]);
    }

    public function status(Practice $test){

        if (!$test) {
            // Se la pratica non esiste, restituisci un errore 404
            return response()->json(['error' => 'Pratica non trovata'], 404);
        }

        // Ottieni il valore di 'allowed'
        $allowed = $test->allowed;

        // Restituisci il risultato in formato JSON
        return response()->json(['status' => $allowed]);
    }

    public function participants(Practice $test){

        $array = [];
        foreach ($test->userwaiting as $user) {

            array_push($array, $user->name);
        }
        // Restituisci il risultato in formato JSON
        return response()->json(['user' => $array]);
    }

    public function empower(Practice $test){

        $test->allowed = 1;
        $test->save();

        $test->userwaiting()->detach();

        return redirect()->back()->with('success', 'Gli studenti hanno iniziato la prova');  
    }
}
