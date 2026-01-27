<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Gère l'accès en fonction des rôles.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Si l'utilisateur n'est pas connecté, redirection
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Vérification du compte actif
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Votre compte est en attente de validation.']);
        }

        // 3. Normalisation et vérification
        $userRole = strtolower($user->role);
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, "Accès interdit : Rôle actuel [{$user->role}] insuffisant.");
        }

        return $next($request);
    }
}