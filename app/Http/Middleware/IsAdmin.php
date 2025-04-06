<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Gérer une requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté et est un admin
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Rediriger vers la page d'accueil avec un message d'erreur
        return redirect('/')->with('error', 'Vous n\'avez pas accès à cette page.');
    }
}
