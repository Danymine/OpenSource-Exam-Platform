<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $segment = $request->segment(1);

        // Controlla se il segmento della lingua è supportato
        $supportedLocales = ['en', 'it'];
        if (!in_array($segment, $supportedLocales)) {
            return redirect('/' . config('app.fallback_locale'));
        }

        // Imposta la lingua dell'applicazione
        App::setLocale($segment);

        // Imposta la lingua predefinita per gli URL generati
        URL::defaults(['locale' => $segment]);

        return $next($request);
    }
}
