<?php

namespace App\Http\Controllers;

use App\Models\Delivered;
use App\Models\Answer;
use App\Models\Practice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

class DeliveredController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Practice $practice )
    {
        
        $delivered = Delivered::where('practice_id', $practice->id)->get();
        return view('view-delivereds', ['delivereds' => $delivered]);
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
    public function save(Request $request)
    {
        
        $delivered = $request->input('id');
        $delivered = Delivered::findOrFail($delivered);
        $score_max = $delivered->practice->total_score;
        
        // Validazione dei dati
        $validate = $request->validate([
            'id' => 'numeric|required|min:1',
            'valutation' => 'numeric|required|min:0|max:' . $score_max,
            'note' => 'max:255|nullable',
            'correct-file' => 'required|file|max:5120|mimetypes:application/pdf', 
        ]);
        
        // Se la validazione ha successo, significa che tutti i dati sono corretti
        // Quindi possiamo procedere con il caricamento del file

        if ($request->hasFile('correct-file')) {

            $file = $request->file('correct-file');
        
            // Verifica se ci sono errori durante il caricamento del file
            if ($file->getError() == UPLOAD_ERR_OK) {

                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Directory di destinazione
                $location = 'uploads';
                $file->move($location, $filename);
        
                $delivered->path = $location .'/'. $filename;
                
            } else {

                return back()->withError('msg', "Problemi con il file");
            }
        } 

        $delivered->valutation = $validate['valutation'];
        $delivered->note = $validate['note'];

        $delivered->save();

        return redirect()->route('view-delivered', ['practice' => $delivered->practice]);
    }

    /**
     * Display the specified resource.
     */
    public function show( Delivered $delivered ){

        $response = Answer::where('delivered_id', $delivered->id)->get()->keyBy('exercise_id');
        $exercises = Practice::find($delivered->practice_id)->exercises()->withTrashed()->get();
        return view('valuta-esame', ['delivered' => $delivered, 'response' => $response, 'exercises' => $exercises]);
    }

    public function print( Delivered $delivered )
    {

        // Creazione di un nuovo oggetto Dompdf
        $dompdf = new Dompdf();

        $html = '<h1>'  . $delivered->user->name . "</h1>"; //Title 
        $response = Answer::where('delivered_id', $delivered->id)->get()->keyBy('exercise_id');
        $exercises = Practice::find($delivered->practice_id)->exercises()->withTrashed()->get();

        $body = "";
        $i = 1;
        foreach( $exercises as $exercise ){

            $body .= "<div>
                        <h3>" . $i . ")" .  $exercise->question . "</h3>";
            if(isset($response[$exercise->id])){

                $body .= "<h4 style=\"color: red;\">" . $response[$exercise->id]->response . "</h4>";
            }
            
            $body .= "</div>";
            $i++;
        }

        $html .= $body;

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        if( Auth::user()->roles == "Teacher" ){

            return $dompdf->stream("" . $delivered->user->name . ".pdf");
        }
        else if( Auth::user()->roles == "Student" ){
            
            return $dompdf->stream("" . $delivered->practice->title . ".pdf");
        }
    }

    public function printCorrect( Delivered $delivered )
    {
        $fileName = $delivered->practice->title . ".pdf";
        return response()->download($delivered->path, $fileName);
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
