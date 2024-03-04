<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssistanceRequest;
use Illuminate\Support\Facades\Auth;

class AssistanceRequestController extends Controller
{

    public function index()
    {
        // Visualizza il form di richiesta assistenza
        return view('create-request');
    }

    public function store(Request $request)
    {

        // Validazione dei dati del form
        $request->validate([
            'name' => 'required|string',
            'description' => 'required',
        ]);

        // Creazione della richiesta di assistenza
        AssistanceRequest::create([
            'name' => $request->input('name'),
            'roles'=> Auth::user()->roles,
            'description' => $request->input('description'),
        ]);

        return redirect()->route('dashboard')->with('success', 'Richiesta di assistenza inviata con successo.');
    }

    public function show(AssistanceRequest $assistance ){

        return view('show-request', ['assistence' => $assistance ]);
    }
}
