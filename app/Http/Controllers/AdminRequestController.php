<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Models\AssistanceRequest;


class AdminRequestController extends Controller
{
    public function index()
    {
        $requests = AssistanceRequest::all(); 
        return view('index-request', compact('requests'));
    }
}
