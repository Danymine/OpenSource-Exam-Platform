<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Delivered;
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

        if( $delivery->user_id == Auth::user()->id or $delivery->practice->user_id == Auth::user()->id){

            return $next($request);
        }
        
        return redirect('/errore');
    }
}
