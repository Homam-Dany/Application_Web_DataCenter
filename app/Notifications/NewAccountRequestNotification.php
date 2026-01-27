<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewAccountRequestNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Envoi par mail ET stocké en base
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle demande d\'ouverture de compte')
            ->line('Un nouvel utilisateur, ' . $this->user->name . ', demande l\'accès au Data Center.')
            ->action('Gérer les utilisateurs', route('admin.users'))
            ->line('Veuillez valider son compte et lui attribuer un rôle.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Nouvelle demande d\'inscription : ' . $this->user->name,
            'user_id' => $this->user->id,
            'type' => 'inscription'
        ];
    }
}