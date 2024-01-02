<?php

namespace App\Http\Controllers;

use App\Models\WaitingRoom;
use Illuminate\Http\Request;
use App\Models\Practice;

class WaitingRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $practice = Practice::find($id);
        return view('waiting-room', ['practices' => $practice]);
    }

    public function status(int $id){

        $practice = Practice::find($id);

        if (!$practice) {
            // Se la pratica non esiste, restituisci un errore 404
            return response()->json(['error' => 'Pratica non trovata'], 404);
        }

        // Ottieni il valore di 'allowed'
        $allowed = $practice->allowed;

        // Restituisci il risultato in formato JSON
        return response()->json(['status' => $allowed]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WaitingRoom $waitingRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WaitingRoom $waitingRoom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaitingRoom $waitingRoom)
    {
        //
    }
}
