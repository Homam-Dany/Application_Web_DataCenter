<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Incident;

class NewIncidentNotification extends Notification
{
    use Queueable;

    public $incident;

    /**
     * Create a new notification instance.
     */
    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        // On utilise uniquement la base de données pour éviter les erreurs de mail en local
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'incident_id' => $this->incident->id,
            'resource_name' => $this->incident->resource->name,
            'subject' => $this->incident->subject,
            'message' => 'Nouveau problème signalé sur ' . $this->incident->resource->name . ' : ' . $this->incident->subject,
            'user_name' => $this->incident->user->name,
            'description' => $this->incident->description,
        ];
    }
}
