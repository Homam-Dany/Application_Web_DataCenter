<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Notifications\ReservationExpiryWarningNotification;
use Carbon\Carbon;

class CheckReservationExpiry extends Command
{
    protected $signature = 'reservations:check-expiry';
    protected $description = 'Vérifie les réservations expirant demain et envoie une notification aux utilisateurs';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $reservations = Reservation::whereIn('status', ['Approuvée', 'Active'])
            ->whereDate('end_date', $tomorrow)
            ->get();

        $count = 0;
        foreach ($reservations as $reservation) {
            $reservation->user->notify(new ReservationExpiryWarningNotification($reservation));
            $count++;
        }

        $this->info("{$count} notification(s) d'expiration envoyée(s).");
    }
}
