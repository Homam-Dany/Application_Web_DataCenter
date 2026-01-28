<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\NewIncidentNotification;
use App\Notifications\IncidentResolvedNotification;

class IncidentController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'subject' => 'required|string|max:150',
            'description' => 'required|string',
        ]);

        $incident = Incident::create([
            'user_id' => auth()->id(),
            'resource_id' => $request->resource_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'status' => 'ouvert', // Statut par défaut
        ]);

        // Notification des responsables et admin
        $responsables = User::whereIn('role', ['responsable', 'admin'])->get();
        foreach ($responsables as $resp) {
            try {
                // Seul le responsable de la ressource spécifique recevra la notif (filtré dans la vue ou ici)
                // Pour simplifier on suit la logique de ReservationController
                $resp->notify(new NewIncidentNotification($incident));
            } catch (\Exception $e) {
            }
        }

        Log::create([
            'user_id' => auth()->id(),
            'action' => 'Signalement',
            'description' => "Incident signalé sur la ressource #{$request->resource_id}"
        ]);

        return back()->with('success', 'Incident signalé avec succès !');
    }

    public function index()
    {
        $user = Auth::user();
        $query = Incident::with(['user', 'resource']);

        // FILTRAGE : Si c'est un responsable, il ne voit que ses ressources (Point 3.4)
        if ($user->role === 'responsable') {
            $query->whereHas('resource', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }
        // Si c'est un admin, il voit tout par défaut

        // Séparer les incidents ouverts et résolus pour l'affichage (Historique)
        $incidents = $query->latest()->get();

        $openIncidents = $incidents->where('status', 'ouvert');
        $resolvedIncidents = $incidents->where('status', '!=', 'ouvert');

        return view('incidents.manager', compact('openIncidents', 'resolvedIncidents'));
    }

    public function resolve(Incident $incident)
    {
        // Sécurité : Seul le responsable de la ressource ou l'admin peut résoudre
        if (Auth::id() !== $incident->resource->manager_id && Auth::user()->role !== 'admin') {
            abort(403, "Vous n'avez pas les droits sur cette ressource.");
        }

        $incident->update(['status' => 'resolu']);

        // Notification de l'utilisateur qui a signalé le problème
        try {
            $incident->user->notify(new IncidentResolvedNotification($incident));
        } catch (\Exception $e) {
        }

        // Marquer la notification du responsable comme lue pour cet incident
        auth()->user()->unreadNotifications
            ->where('data.incident_id', (int) $incident->id)
            ->markAsRead();

        Log::create([
            'user_id' => auth()->id(),
            'action' => 'Incident Résolu',
            'description' => "L'incident #{$incident->id} sur {$incident->resource->name} a été marqué comme résolu."
        ]);

        return back()->with('success', 'L’incident a été marqué comme résolu.');
    }
}