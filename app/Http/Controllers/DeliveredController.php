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
        if( $practice->user_id == Auth::user()->id ){

            $delivered = Delivered::where('practice_id', $practice->id)->get();
            return view('delivereds.view-delivereds', ['delivereds' => $delivered, 'practice' => $practice]);
        }
        
        abort('403', "Non autorizzato");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function save(Request $request, Delivered $delivered)
    {
        $validateData = $request->validate([
            'correct' => 'required|array|size:' . $delivered->practice->exercises()->count(),
            'correct.*' => 'numeric|min:0',
            'note' => 'required|array|size:' . $delivered->practice->exercises()->count(),
            'note_general' => 'nullable|string|max:255',
            'file-correct' => 'nullable|file|mimes:pdf,image/*',
        ]);


        $answers = $delivered->answers;
        foreach($answers as $answer){

            $answer->score_assign = $validateData['correct'][$answer->id];
            $answer->note = $validateData['note'][$answer->id];

            $answer->save();
        }

        $delivered->valutation =  array_sum($validateData['correct']);  //Sommo gli elementi del vettore
        $delivered->note = $validateData['note_general'];

        if ($request->hasFile('file-correct')) {

            $file = $request->file('file-correct');
        
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

        $delivered->save();

        return redirect()->route('view-delivered', ['practice' => $delivered->practice]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show( Delivered $delivered ){

        $response = Answer::where('delivered_id', $delivered->id)
        ->get()
        ->groupBy('exercise_id');
        $exercises = Practice::find($delivered->practice_id)->exercises()->withTrashed()->get();
        return view('delivereds.valuta-esame', ['delivered' => $delivered, 'response' => $response, 'exercises' => $exercises]);
    }

    public function print(Delivered $delivered){

        // Creazione di un nuovo oggetto Dompdf
        $dompdf = new Dompdf();

        // Intestazione del PDF con il nome dello studente e il titolo della pratica
        $header = '
            <div style="text-align: center; margin-bottom: 20px;">
                <h2>' . $delivered->user->name . ' ' . $delivered->user->first_name . '</h2>
                <h3>' . $delivered->practice->title . '</h3>
            </div>
            <hr style="border-top: 1px solid #000;">
        ';

        // Creazione del corpo del PDF con le risposte agli esercizi
        $body = '';
        $i = 1;
        foreach ($delivered->practice->exercises as $exercise) {
            $response = $delivered->answers->where('exercise_id', $exercise->id)->first();
            if ($response) {
                $body .= '
                    <div style="margin-bottom: 20px;">
                        <h4>' . $i . '. ' . $exercise->question . '</h4>
                        <p style="color: red;">' . $response->response . '</p>
                    </div>
                ';
                $i++;
            }
        }

        // HTML completo del PDF con intestazione e corpo
        $html = '<html><body>' . $header . $body . '</body></html>';

        // Caricamento dell'HTML nel Dompdf
        $dompdf->loadHtml($html);

        // Impostazione delle dimensioni della pagina e del layout
        $dompdf->setPaper('A4', 'portrait');

        // Rendering del PDF
        $dompdf->render();

        // Restituzione del PDF come stream per il download
        return $dompdf->stream('Correction_' . $delivered->user->name . '.pdf');
    }


    public function printCorrect( Delivered $delivered )
    {
        $fileName = $delivered->practice->title . ".pdf";
        return response()->download($delivered->path, $fileName);
    }

    public function public( Practice $practice ){

        $delivereds = $practice->delivereds;
        foreach( $delivereds as $delivery ){

            if( $delivery->valutation === NULL ){

                return back()->withErrors(["error" => "Ci sono ancora consegne senza valutazione."]);
            }
        }
        
        $users = [];
        foreach( $delivereds as $delivery ){

            $user = User::find($delivery->user->id)->first();
            Publish::dispatch($user);
        }

        $practice->public = 1;
        $practice->save();
        
        return redirect()->route('dashboard')->with('success', "Valutazioni pubblicate con successo");
    }
}
