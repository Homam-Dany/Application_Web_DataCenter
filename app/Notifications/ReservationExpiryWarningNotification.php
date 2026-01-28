<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reservation;

class ReservationExpiryWarningNotification extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $daysLeft = 1;
        return [
            'reservation_id' => $this->reservation->id,
            'resource_name' => $this->reservation->resource->name,
            'status' => 'expire_soon',
            'message' => "Votre réservation pour la ressource {$this->reservation->resource->name} se termine demain ({$this->reservation->end_date->format('d/m/Y')}).",
            'end_date' => $this->reservation->end_date->toDateTimeString(),
        ];
    }
}
