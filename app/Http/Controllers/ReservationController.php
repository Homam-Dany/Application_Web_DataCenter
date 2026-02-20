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

    public function calendar()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->whereIn('status', ['Approuvée', 'Active', 'en_attente'])
            ->with('resource')
            ->get();

        $events = [];

        foreach ($reservations as $res) {
            $color = match ($res->status) {
                'Approuvée' => '#10b981', // Success
                'Active' => '#10b981',
                'en_attente' => '#f59e0b', // Warning
                default => '#6b7280',
            };

            $events[] = [
                'title' => $res->resource->name,
                'start' => $res->start_date->format('Y-m-d'),
                'end' => $res->end_date->addDay()->format('Y-m-d'), // +1 day for FullCalendar exclusivity
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'status' => $res->status
                ]
            ];
        }

        return view('reservations.calendar', compact('events'));
    }

    /**
     * Vue Responsable/Admin : Consulter les demandes à gérer (Point 3.2)
     */
    public function managerIndex()
    {
        $user = Auth::user();

        // 1. PENDING REQUESTS
        $pendingReservations = Reservation::whereHas('resource', function ($query) use ($user) {
            if (!$user->isAdmin() && !$user->isResponsable()) {
                $query->where('manager_id', $user->id);
            }
        })
            ->where('status', 'en_attente')
            ->with(['resource', 'user'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. HISTORY
        $historyReservations = Reservation::whereHas('resource', function ($query) use ($user) {
            if (!$user->isAdmin() && !$user->isResponsable()) {
                $query->where('manager_id', $user->id);
            }
        })
            ->whereIn('status', ['Approuvée', 'Refusée', 'Terminée', 'Annulée'])
            ->with(['resource', 'user'])
            ->orderBy('updated_at', 'desc')
            ->take(50) // Limit history for performance
            ->get();

        // 3. CALENDAR EVENTS
        $calendarReservations = Reservation::whereHas('resource', function ($query) use ($user) {
            if (!$user->isAdmin() && !$user->isResponsable()) {
                $query->where('manager_id', $user->id);
            }
        })
            ->whereIn('status', ['Approuvée', 'Active'])
            ->with(['resource', 'user'])
            ->get();

        $events = [];
        foreach ($calendarReservations as $res) {
            $events[] = [
                'title' => $res->resource->name . ' (' . $res->user->name . ')',
                'start' => $res->start_date->format('Y-m-d'),
                'end' => $res->end_date->addDay()->format('Y-m-d'),
                'backgroundColor' => '#10b981',
                'borderColor' => '#10b981',
                'url' => route('resources.show', $res->resource_id), // Optional click action
                'extendedProps' => [
                    'user' => $res->user->name,
                    'status' => $res->status
                ]
            ];
        }

        return view('reservations.manager', compact('pendingReservations', 'historyReservations', 'events'));
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
                $query->where(function ($q) use ($request) {
                    $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                        ->orWhere(function ($inner) use ($request) {
                            $inner->where('start_date', '<=', $request->start_date)
                                ->where('end_date', '>=', $request->end_date);
                        });
                });
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

        // Autorisation : Manager de la ressource OU Admin OU Responsable
        if (auth()->id() !== $reservation->resource->manager_id && !auth()->user()->isAdmin() && !auth()->user()->isResponsable()) {
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
            if (!auth()->user()->isAdmin() && !auth()->user()->isResponsable()) {
                $query->where('manager_id', Auth::id());
            }
        })
            ->whereIn('status', ['Approuvée', 'Refusée', 'Terminée'])
            ->with(['resource', 'user'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('reservations.history', compact('reservations'));
    }

    /**
     * Fetch availability for a specific resource (AJAX)
     */
    public function availability(Resource $resource)
    {
        $reservations = Reservation::where('resource_id', $resource->id)
            ->whereIn('status', ['Approuvée', 'Active'])
            ->get();

        $data = $reservations->map(function ($res) {
            return [
                'start_date' => $res->start_date->format('Y-m-d'),
                'end_date' => $res->end_date->format('Y-m-d'),
            ];
        });

        return response()->json($data);
    }
}