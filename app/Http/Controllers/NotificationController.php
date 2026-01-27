<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // "Resurfacing" : On vérifie si des notifications "lues" sont en fait des tâches en attente
        Auth::user()->notifications->each(function ($notification) {
            // Pour les réservations
            if ($notification->read_at !== null && isset($notification->data['reservation_id'])) {
                $reservation = \App\Models\Reservation::find($notification->data['reservation_id']);
                if ($reservation && $reservation->status === 'en_attente') {
                    $notification->markAsUnread();
                }
            }
            // Pour les incidents
            if ($notification->read_at !== null && isset($notification->data['incident_id'])) {
                $incident = \App\Models\Incident::find($notification->data['incident_id']);
                if ($incident && $incident->status === 'ouvert') {
                    $notification->markAsUnread();
                }
            }
        });

        // Maintenant on récupère tous les notifications (lues et non lues)
        $notifications = Auth::user()->notifications()->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead()
    {
        Auth::user()->unreadNotifications->each(function ($notification) {
            // Logique de filtrage : Ne pas marquer comme lu si une action est requise
            $shouldKeepUnread = false;

            // Check Reservation
            if (isset($notification->data['reservation_id'])) {
                $reservation = \App\Models\Reservation::find($notification->data['reservation_id']);
                if ($reservation && $reservation->status === 'en_attente') {
                    $shouldKeepUnread = true;
                }
            }

            // Check Incident
            if (isset($notification->data['incident_id'])) {
                $incident = \App\Models\Incident::find($notification->data['incident_id']);
                if ($incident && $incident->status === 'ouvert') {
                    $shouldKeepUnread = true;
                }
            }

            if (!$shouldKeepUnread) {
                $notification->markAsRead();
            }
        });

        return back()->with('success', 'Les notifications informatives ont été marquées comme lues.');
    }
}