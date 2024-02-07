<?php

namespace App\Http\Controllers;

use App\Models\Delivered;
use App\Models\Answer;
use App\Models\User;
use App\Models\Practice;
use App\Jobs\Publish;
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
        
        $validate = $request->validate([
            'id_delivered' => "numeric|required|min:1",
            'risposta' => "array|nullable",
            'risposta.*' => "numeric",
            'risposta_aperta' => "array|nullable",
            'risposta_aperta.*' => "string|max:255",
            'note' => "array|nullable",
            'note.*' => "string|max:255",
            'note_general' => "string|max:255",
            'correct-file' => 'file|max:5120|mimetypes:application/pdf|nullable'
        ]);

        $delivered = Delivered::findOrFail($validate['id_delivered']);
        $response = Answer::where('delivered_id', $delivered->id)->get()->keyBy('exercise_id'); 
        $practice = Practice::find($delivered->practice_id)->withTrashed()->first();
        $exercises = Practice::find($delivered->practice_id)->exercises()->withTrashed()->get();
        $score_max = $practice->total_score;
        
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

        $score = 0;
        foreach( $exercises as $exercise){

            if( $exercise->type == "Risposta Aperta" ){
                
                $score += $validate["risposta_aperta"][$exercise->id];
                $response[$exercise->id]->score_assign = $validate["risposta_aperta"][$exercise->id];
                if( array_key_exists($exercise->id, $validate["note"]) ){

                    $response[$exercise->id]->note = $validate["note"][$exercise->id];
                }
            }
            else{

                $score += $validate["risposta"][$exercise->id];
                $response[$exercise->id]->score_assign = $validate["risposta"][$exercise->id];
            }

            $response[$exercise->id]->save();
        }
        $delivered->valutation = intval(round($score));
        $delivered->note = $validate['note_general'];
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

    public function public( Practice $practice ){

        $delivered = $practice->delivereds;
        foreach( $delivered as $delivery ){

            if( $delivery->valutation == NULL ){

                return back()->withErrors(["error" => "Ci sono ancora consegne senza valutazione."]);
            }
        }
        
        $users = [];
        foreach( $delivered as $delivery ){

            $user = User::find($delivery->user->id)->first();
            Publish::dispatch($user);
        }

        $practice->public = 1;
        $practice->save();
        
        return redirect()->route('ciao')->withErrors(['error' => 'Pubblicazione avvenuta con successo']);
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
