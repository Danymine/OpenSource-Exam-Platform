<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Practice;

class Allowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {      
        $practice = Practice::where('key', '=', $request->key)->first();
        //Necessario aggiungere un controllo aggiuntivo sulla data anche qui
        if( $practice != NULL){

            if($practice->allowed == 1){

                return $next($request);
            }
        }
        return redirect('/errore2');
    }
}
