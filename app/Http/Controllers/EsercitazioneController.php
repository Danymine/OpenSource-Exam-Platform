<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class EsercitazioneController extends Controller
{
    public function crea()
    {
        return view('CreaEsercitazione');
    }

    public function genera(Request $request)
    {
        // Ottieni i dati dal form
        $numeroDomande = $request->input('numero_domande');
        $punteggioMassimo = $request->input('punteggio_massimo');
        $randomizzazione = $request->has('randomizzazione');

        // Qui puoi usare i dati ricevuti per generare l'esercitazione
        // Implementa la logica di generazione degli esercizi personalizzati
        // Ad esempio, puoi chiamare un metodo del tuo modello per ottenere gli esercizi
        // E poi randomizzarli se l'opzione di randomizzazione è attiva

        return view('Esercitazione')->with([
            'esercitazione' => $esercitazioneGenerata,
        ]);
    }

    private function generakey(){

        $alfabeto =  array_merge(range('a', 'z'), range('A', 'Z'));
        $codice = "";
        $trovato = false;
        do{
            for( $i = 0; $i < 6; $i++ ){

                $rand = rand(0, 1);
                if( $rand == 0 ){

                    //Genero un numero
                    $number = rand(0, 9);
                    $codice .= $number;
                }
                else{

                    //Genero un carattere
                    $char = rand(0, count($alfabeto));
                    $codice .= $alfabeto[$char];
                }
            }
             
            //Controllo se è presente un esame con lo stesso codice. Questa logica dovrebbe avere circa 52 Miliardi di combinazioni A9f456
            $result = DB::table('esames')->where('codice', $codice)->get();
            if( $result->count() == 0){

                $trovato = true;
            }
        }
        while( $trovato != true);
        
        return $codice;
    }
}
