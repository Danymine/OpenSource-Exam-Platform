<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Practice;
use Carbon\Carbon;

class Allowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        /*
        Middleware che verifica che la Key inserita dall'utente in primis esista e in più verifica che la data nella quale l'utente stia cercando di accedere
        corrisponda effettivamente con la data nella quale il docente ha programmato la prova.
        */
        
        $practice = Practice::where('key', '=', $request->key)->first();
        date_default_timezone_set('Europe/Rome');
        if( $practice != NULL and $practice->practice_date == now()->toDateString()){

            if($practice->allowed == 1){

                return $next($request);
            }
        }
        return redirect('/errore2');
    }
}
