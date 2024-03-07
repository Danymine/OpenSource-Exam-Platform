<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Delivered;
use App\Models\Practice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;


class Control
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   

        $prova = $request->route('delivered');

        $delivery = Delivered::findOrFail($prova->id);

        if( $delivery ){

            $practice = Practice::withTrashed()->findOrFail($delivery->practice_id);
            if($delivery->user_id == Auth::user()->id || $practice->user_id == Auth::user()->id){

                return $next($request);
            }
        }
            
        return abort('403', "Non autorizzato.");
    }
}
