<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Incident;

class IncidentResolvedNotification extends Notification
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
            'message' => 'L’incident sur la ressource ' . $this->incident->resource->name . ' a été résolu.',
            'status' => 'resolu',
        ];
    }
}
