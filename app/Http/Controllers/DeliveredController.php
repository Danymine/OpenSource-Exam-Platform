<?php

namespace App\Http\Controllers;

use App\Models\Delivered;
use App\Models\Answer;
use Illuminate\Http\Request;

class DeliveredController extends Controller
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
    public function show(int $delivered)
    {

        $delivery = Delivered::find($delivered);
        $response = Answer::where('delivered_id', '=', $delivery->id)->get();
        return view('mostra-esame', ['delivered' => $delivery, 'response' => $response]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivered $delivered)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivered $delivered)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivered $delivered)
    {
        //
    }
}
