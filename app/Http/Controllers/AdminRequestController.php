<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Models\Request;


class AdminRequestController extends Controller
{
    public function index()
    {
        $requests = Request::all(); 
        return view('index-request', compact('requests'));
    }
}
