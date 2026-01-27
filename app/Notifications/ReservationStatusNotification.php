<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reservation;

class ReservationStatusNotification extends Notification
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     * On injecte l'objet réservation pour accéder au statut et au motif de refus.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Canaux d'envoi : Mail pour l'alerte externe et Database pour l'interface Web.
     */
    public function via($notifiable)
    {
        // En local (et pour éviter les erreurs SMTP), on utilise uniquement la base de données
        return ['database'];
    }

    /**
     * Construction de l'e-mail envoyé à l'utilisateur.
     */
    public function toMail($notifiable)
    {
        $status = $this->reservation->status;
        $url = route('reservations.index');

        $mail = (new MailMessage)
            ->subject("Mise à jour de votre réservation : {$status}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le statut de votre demande pour la ressource **{$this->reservation->resource->name}** a été mis à jour.");

        // Si la demande est refusée, on affiche le motif en rouge (error) dans le mail
        if ($status === 'Refusée') {
            $mail->error()
                ->line("Votre demande a été refusée pour le motif suivant :")
                ->line("**{$this->reservation->rejection_reason}**");
        } else {
            $mail->success()
                ->line("Votre demande a été approuvée avec succès.")
                ->line("Période : Du {$this->reservation->start_date->format('d/m/Y')} au {$this->reservation->end_date->format('d/m/Y')}");
        }

        return $mail->action('Consulter mes réservations', $url)
            ->line('Merci d’utiliser la plateforme IDAI Data Center.');
    }

    /**
     * Structure des données stockées en base de données (JSON).
     * Ces données sont lues par votre fichier resources/views/notifications/index.blade.php.
     */
    public function toArray($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'resource_name' => $this->reservation->resource->name,
            'status' => $this->reservation->status,
            'message' => "Le statut de votre réservation pour {$this->reservation->resource->name} est désormais : {$this->reservation->status}.",
            // Donnée cruciale : le motif de refus saisi par le responsable
            'rejection_reason' => $this->reservation->rejection_reason,
            // On conserve la justification originale pour que l'utilisateur sache de quelle demande on parle
            'justification' => $this->reservation->justification,
        ];
    }
}