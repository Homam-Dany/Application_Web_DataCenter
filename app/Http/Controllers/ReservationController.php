<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Log;
use App\Models\User;
use App\Notifications\ReservationStatusNotification;
use App\Notifications\NewReservationNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Espace personnel : Suivre ses propres demandes (Point 2.3)
     */
    public function index(Request $request)
    {
        $query = Reservation::where('user_id', Auth::id())->with('resource');

        if ($request->filled('resource')) {
            $query->whereHas('resource', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->resource . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('start_date', '<=', $request->date)
                ->whereDate('end_date', '>=', $request->date);
        }

        $allReservations = $query->orderBy('start_date', 'desc')->get();

        foreach ($allReservations as $res) {
            if ($res->status === 'Approuvée' && $res->end_date->isPast()) {
                $res->update(['status' => 'Terminée']);
            }
        }

        return view('reservations.index', compact('allReservations'));
    }

    /**
     * Vue Responsable/Admin : Consulter les demandes à gérer (Point 3.2)
     */
    public function managerIndex()
    {
        $resources = Resource::where('manager_id', Auth::id())->get();

        $pendingReservations = Reservation::whereHas('resource', function ($query) {
            // Un admin voit tout, un responsable voit ses ressources
            if (!auth()->user()->isAdmin()) {
                $query->where('manager_id', Auth::id());
            }
        })
            ->where('status', 'en_attente')
            ->with(['resource', 'user'])
            ->get();

        return view('reservations.manager', compact('resources', 'pendingReservations'));
    }

    public function create()
    {
        $resources = Resource::where('status', 'disponible')->get();
        return view('reservations.create', compact('resources'));
    }

    /**
     * Enregistrer une nouvelle demande (Point 2.2)
     */
    public function store(Request $request)
    {
        // Validation du champ justification envoyé par le client
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) use ($request) {
                    $start = \Carbon\Carbon::parse($request->start_date);
                    $end = \Carbon\Carbon::parse($value);
                    if ($start->diffInDays($end) > 15) {
                        $fail('La réservation est limitée à 15 jours maximum pour garantir un partage équitable.');
                    }
                },
            ],
            'justification' => 'required|string|min:10|max:1000',
        ]);

        $resource = Resource::find($request->resource_id);
        if ($resource->status !== 'disponible') {
            return back()->withErrors(['status' => 'Cette ressource est indisponible.']);
        }

        $hasConflict = Reservation::where('resource_id', $request->resource_id)
            ->where('status', 'Approuvée')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })->exists();

        if ($hasConflict) {
            return back()->withErrors(['conflit' => 'Ressource déjà réservée pour ces dates.'])->withInput();
        }

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'resource_id' => $request->resource_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'justification' => $request->justification,
            'status' => 'en_attente',
        ]);

        // Notification des responsables
        $responsables = User::whereIn('role', ['responsable', 'admin'])->get();
        foreach ($responsables as $resp) {
            try {
                $resp->notify(new NewReservationNotification($reservation));
            } catch (\Exception $e) {
            }
        }

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'Demande Réservation',
            'description' => "Demande pour {$resource->name}"
        ]);

        return redirect()->route('reservations.index')->with('success', 'Demande transmise avec succès.');
    }

    /**
     * Approuver ou refuser (Point 3.3)
     */
    public function decide(Request $request, $id, $action)
    {
        $reservation = Reservation::findOrFail($id);

        // Autorisation : Manager de la ressource OU Admin
        if (auth()->id() !== $reservation->resource->manager_id && !auth()->user()->isAdmin()) {
            abort(403, "Vous n'êtes pas autorisé à prendre cette décision.");
        }

        if ($action === 'accepter') {
            $reservation->update(['status' => 'Approuvée', 'rejection_reason' => null]);
            $msg = "Demande acceptée avec succès.";
        } else {
            // Récupération du motif de refus depuis le textarea 'rejection_reason'
            $motif = $request->input('rejection_reason');

            if (!$motif || strlen(trim($motif)) < 5) {
                return back()->withErrors(['rejection_reason' => 'Le motif du refus est obligatoire (5 caractères min).'])->withInput();
            }

            $reservation->update([
                'status' => 'Refusée',
                'rejection_reason' => $motif
            ]);
            $msg = "Le refus a été enregistré avec succès.";
        }

        // Notification de l'utilisateur de la décision finale
        try {
            $reservation->user->notify(new ReservationStatusNotification($reservation));
        } catch (\Exception $e) {
        }

        // Marquer la notification du gestionnaire comme lue pour cette demande
        auth()->user()->unreadNotifications
            ->where('data.reservation_id', (int) $id)
            ->markAsRead();

        return back()->with('success', $msg);
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        if (auth()->id() !== $reservation->user_id) {
            abort(403);
        }
        $reservation->delete();
        return back()->with('success', 'Réservation annulée.');
    }

    public function history()
    {
        $reservations = Reservation::whereHas('resource', function ($query) {
            if (!auth()->user()->isAdmin()) {
                $query->where('manager_id', Auth::id());
            }
        })
            ->whereIn('status', ['Approuvée', 'Refusée', 'Terminée'])
            ->with(['resource', 'user'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('reservations.history', compact('reservations'));
    }
}