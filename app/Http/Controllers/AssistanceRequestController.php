<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssistanceRequest;
use App\Models\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AssistanceRequestController extends Controller
{

    public function index()
    {
        // Visualizza il form di richiesta assistenza
        return view('create-request');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'subject' => 'required|string|max:128',
            'description' => 'required|string|max:512'
        ]);

        //Qui ci potrebbe essere la logica di gestione per decidere a chi assegnare la richiesta di supporto noi semplicemente prenderemo quello che ha meno di fare.
        $adminWithLeastRequests = User::where('roles', 'Admin')
        ->withCount('assistanceRequests')
        ->orderBy('assistance_requests_count')
        ->first();

        $assistanceRequest = new AssistanceRequest();
        $assistanceRequest->subject = $request->input('subject');
        $assistanceRequest->description = $request->input('description');
        $assistanceRequest->user_id = auth()->id(); // ID dell'utente che fa la richiesta
        $assistanceRequest->admin_id = $adminWithLeastRequests->id;
        $assistanceRequest->status = 0; // Stato di default
        $assistanceRequest->save();
    }

    public function show(AssistanceRequest $assistance ){

        $responses = $assistance->responses->sortByDesc('created_at');
        return view('support-admin.show-request', ['assistence' => $assistance, 'responses' => $responses ]);
    }

    public function store_response(Request $request, AssistanceRequest $AssistanceRequest ){

        if( Auth::user()->id == $AssistanceRequest->user_id || Auth::user()->roles == "Admin" ){

            $validator = Validator::make($request->all(), [
                'response' => ['required', 'string', 'max:512', 'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ?!\\"\'\\-\s]+$/'],
            ]);
            
            if ($validator->fails()) {
                
                return redirect()->route('view-request')->withErrors(['errors' => trans("Hai immesso dei caratteri non validi assicurati di non usare caratteri speciali.")]);
            }
            
            $response = new Response;
            $response->response = $request->input('response');
            $response->user_id = Auth::user()->id;
            $response->assistance_request_id = $AssistanceRequest->id;
            $response->created_at = now();

            $response->save();
            
            return redirect()->route('view-request', ['assistance' => $AssistanceRequest]);
        }

        abort('403', "Non autorizzato");
    }

    public function close( AssistanceRequest $assistance ){

        $assistance->status = 1;
        $assistance->save();

        return redirect()->route('dashboard');
    }
}
