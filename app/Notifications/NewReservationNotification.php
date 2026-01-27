<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class NewReservationNotification extends Notification
{
    use Queueable;

    public $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'message' => 'Nouvelle demande de réservation pour ' . $this->reservation->resource->name,
            'user_name' => $this->reservation->user->name,
            // AJOUT : On stocke la justification dans la notification
            'justification' => $this->reservation->justification,
        ];
    }
}