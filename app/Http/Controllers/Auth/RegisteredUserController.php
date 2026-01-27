<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
// Importation pour les notifications
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewAccountRequestNotification;

class RegisteredUserController extends Controller
{
    public function create(): RedirectResponse
    {
        return redirect()->route('login')->with('panel', 'register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guest',
            'is_active' => false,
        ]);

        event(new Registered($user));

        // --- ALERTER LES ADMINISTRATEURS ---
        try {
            $admins = User::where('role', 'admin')->get();
            if ($admins->count() > 0) {
                // On tente l'envoi
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewAccountRequestNotification($user));
            }
        } catch (\Exception $e) {
            // En cas d'erreur SMTP (535), on ignore l'erreur pour ne pas bloquer l'utilisateur
            // L'admin verra quand même la demande dans son tableau de bord car elle est en base de données
        }

        return redirect()->route('resources.index')->with('success', 'Votre demande d\'inscription a été déposée avec succès. L\'administrateur examinera vos informations et vous enverra une réponse par mail une fois votre compte activé.');
    }
}