<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RichiestaAssistenza; 

class RichiestaAssistenzaController extends Controller
{
    // Metodo per visualizzare il form di richiesta assistenza
    public function createForm()
    {
        return view('form');
    }

    // Metodo per salvare la richiesta di assistenza nel database
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'ruolo' => 'required|in:professore,studente',
            'problema' => 'required|string',
        ]);

        RichiestaAssistenza::create([
            'nome' => $request->input('nome'),
            'ruolo' => $request->input('ruolo'),
            'problema' => $request->input('problema'),
        ]);

        return redirect()->route('dashboard')->with('success', 'Richiesta di assistenza inviata con successo!');
    }
}
