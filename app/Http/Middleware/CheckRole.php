<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Si le compte est désactivé, on déconnecte et on bloque
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Votre compte est désactivé.']);
        }

        // Vérification du rôle
        if (!in_array($user->role, $roles)) {
            abort(403, "Vous n'avez pas les droits nécessaires.");
        }

        return $next($request);
    }
}