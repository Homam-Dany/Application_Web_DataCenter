<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class AccountActivatedNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $roleLabel = match ($this->user->role) {
            'admin' => 'Administrateur',
            'responsable' => 'Responsable de Ressources',
            'user' => 'Utilisateur standard',
            default => 'Invité',
        };

        // Generate Magic Token
        $token = Str::random(60);
        Cache::put('magic_login_' . $token, $this->user->id, now()->addDays(7));

        // Custom Log for easier testing
        $link = route('login.magic', ['token' => $token]);
        \Illuminate\Support\Facades\Log::info("\n--------------------------------------------------\nObjet : Compte vérifié | Accès Data Center\nPour : " . $this->user->email . "\nMessage : \"Votre compte a été validé. Vous êtes maintenant : $roleLabel.\"\nLien : $link\nNote: \"Cliquez ci-dessus pour accéder directement à votre espace (lien unique valide 7 jours).\"\n--------------------------------------------------");

        return (new MailMessage)
            ->subject('Compte vérifié | Accès Data Center')
            ->greeting('Bonjour ' . $this->user->name . ',')
            ->line('Votre compte a été validé. Vous êtes maintenant : **' . $roleLabel . '**.')
            ->action('Connexion Automatique', route('login.magic', ['token' => $token]))
            ->line('Cliquez ci-dessus pour accéder directement à votre espace (lien unique valide 7 jours).');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Votre compte a été activé avec le rôle : ' . $this->user->role,
            'type' => 'activation'
        ];
    }
}
