<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountRefusedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $reason;

    public function __construct($user, $reason)
    {
        $this->user = $user;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        \Illuminate\Support\Facades\Log::info("\n--------------------------------------------------\nObjet : Demande refusée | Accès Data Center\nPour : " . $this->user->email . "\nMotif : \"$this->reason\"\n--------------------------------------------------");

        return (new MailMessage)
            ->subject('Demande refusée | Accès Data Center')
            ->greeting('Bonjour ' . $this->user->name . ',')
            ->line('Nous avons le regret de vous informer que votre demande de création de compte a été refusée.')
            ->line('**Motif du refus :** ' . $this->reason)
            ->line('Pour toute réclamation, veuillez contacter l\'administrateur.')
            ->salutation('Cordialement, L\'équipe Data Center');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Votre demande a été refusée pour le motif : ' . $this->reason,
            'type' => 'refusal'
        ];
    }
}
