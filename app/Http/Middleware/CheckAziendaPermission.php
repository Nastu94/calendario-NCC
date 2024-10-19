<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAziendaPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Assicurati che l'utente sia autenticato e abbia il permesso Azienda
        if (Auth::check() && Auth::user()->permessi->contains('nome', 'Azienda')) {
            return $next($request);
        }

        // Reindirizza l'utente se non ha il permesso Azienda
        return redirect('dashboard')->with('error', 'Accesso non autorizzato.');
    }
}
