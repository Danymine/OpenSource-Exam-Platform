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
use Dompdf\Options;
use Illuminate\Support\Facades\View;
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
        $practice = Practice::withTrashed()->find($delivered->practice_id);
        $validateData = $request->validate([
            'correct' => 'required|array|size:' . $practice->exercises()->count(),
            'correct.*' => 'numeric|min:0',
            'note' => 'required|array|size:' . $practice->exercises()->count(),
            'note.*' => 'string|max:255|nullable',
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

                return back()->withError('error', trans("Problemi con il file"));
            }
        } 

        $delivered->save();

        return redirect()->route('view-delivered', ['practice' => $practice]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show( Delivered $delivered ){

        $response = Answer::where('delivered_id', $delivered->id)
        ->get()
        ->groupBy('exercise_id');
        $practice = Practice::withTrashed()->find($delivered->practice_id);
        $exercises = $practice->exercises()->withTrashed()->get();
        return view('delivereds.valuta-esame', ['delivered' => $delivered, 'practice' => $practice, 'response' => $response, 'exercises' => $exercises]);
    }

    public function print(Delivered $delivered){

        // Creazione di un nuovo oggetto Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf();

        $practice = Practice::withTrashed()->find($delivered->practice_id);

        // Dati da passare alla vista blade
        $data = [
            'exercises' => $practice->exercises()->withTrashed()->get(),
            'response' => Answer::where('delivered_id', $delivered->id)->get()->groupBy('exercise_id'),
            'delivered' => $delivered
        ];

        // Render della vista blade come HTML
        $html = View::make('DownloadDelivered1', $data)->render();

        // Caricamento dell'HTML nel Dompdf
        $dompdf->loadHtml($html);

        // Impostazione delle dimensioni della pagina e del layout
        $dompdf->setPaper('A4', 'portrait');

        // Rendering del PDF
        $dompdf->render();

        // Restituzione del PDF come stream per il download
        return $dompdf->stream($practice->title . "_" . $delivered->user->name . '.pdf');
    }

    public function printDeliveredWithCorrect(Delivered $delivered){

        // Creazione di un nuovo oggetto Dompdf con le opzioni predefinite
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        $practice = Practice::withTrashed()->find($delivered->practice_id);
        // Dati da passare alla vista blade
        $data = [
            'exercises' => $practice->exercises()->withTrashed()->get(),
            'response' => Answer::where('delivered_id', $delivered->id)->get()->groupBy('exercise_id'),
            'notes' => $delivered->note,
            'valutation' => $delivered->valutation,
            'type' => $practice->type,
            'totalScore' => $practice->total_score,
            'delivered' => $delivered
        ];

        // Render della vista blade come HTML
        $html = View::make('DownloadDelivered', $data)->render();

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
        $practice = Practice::withTrashed()->find($delivered->practice_id);
        $fileName = $practice->title . ".pdf";
        return response()->download($delivered->path, $fileName);
    }

    public function public( Practice $practice ){

        $delivereds = $practice->delivereds;
        foreach( $delivereds as $delivery ){

            if( $delivery->valutation === NULL ){

                return back()->withErrors(["error" => trans("Ci sono ancora consegne senza valutazione.")]);
            }
        }
        
        $users = [];
        foreach( $delivereds as $delivery ){

            $user = User::find($delivery->user->id)->first();
            Publish::dispatch($user);
        }

        $practice->public = 1;
        $practice->save();
        
        return redirect()->route('dashboard')->with('success', trans("Valutazioni pubblicate con successo"));
    }

    public function show_total( Delivered $delivered ){

        $response = Answer::where('delivered_id', $delivered->id)
        ->get()
        ->groupBy('exercise_id');
        $practice = Practice::withTrashed()->find($delivered->practice_id);
        $exercises = $practice->exercises()->withTrashed()->get();


        return view('delivereds.delivered-show', ['delivered' => $delivered, 'practice' => $practice, 'response' => $response, 'exercises' => $exercises]);

    }
}