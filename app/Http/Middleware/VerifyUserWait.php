<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\URL;


class VerifyUserWait
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        /*
        Middleware che verifica se un utente ha lasciato una waiting room e quindi sarà rimosso anche dal database
        */
        
        $practice = Practice::where('key', '=', $request->key)->first();
        if( $practice != NULL and $practice->practice_date == now()){

            if($practice->allowed == 1){

                return $next($request);
            }
        }
        
        abort('403', "Non autorizzato");
    }
}
