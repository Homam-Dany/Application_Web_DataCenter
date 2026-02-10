<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\Incident;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EngineerController extends Controller
{
    /**
     * Taux de rafraîchissement des widgets (en minutes)
     */
    const REFRESH_RATE = 5;

    public function dashboard()
    {
        $user = Auth::user();

        // 1. SCOPE : Ressources gérées par cet ingénieur
        $managedResourcesQuery = Resource::where('manager_id', $user->id);
        $managedResourceIds = $managedResourcesQuery->pluck('id');

        // 2. KPIs (Indicateurs Clés de Performance)
        $stats = [
            'total_managed' => $managedResourcesQuery->count(),
            'active_incidents' => Incident::whereIn('resource_id', $managedResourceIds)
                ->where('status', 'ouvert')
                ->count(),
            'pending_requests' => $user->isResponsable()
                ? Reservation::where('status', 'en_attente')->count()
                : Reservation::whereIn('resource_id', $managedResourceIds)
                    ->where('status', 'en_attente')
                    ->count(),
            'maintenance_mode' => $managedResourcesQuery->where('status', 'maintenance')->count(),
        ];

        // 3. Mini-Graphique d'Occupation (Pour les ressources gérées uniquement)
        $totalManaged = $stats['total_managed'];
        $occupiedManaged = Reservation::whereIn('resource_id', $managedResourceIds)
            ->where('status', 'Approuvée')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->distinct('resource_id')
            ->count('resource_id');

        $stats['occupancy_rate'] = $totalManaged > 0
            ? round(($occupiedManaged / $totalManaged) * 100)
            : 0;

        // 4. Données pour la Grille/Rack Vue
        // On récupère toutes les ressources avec leurs incidents actifs pour l'affichage visuel
        $resources = Resource::where('manager_id', $user->id)
            ->with([
                'incidents' => function ($q) {
                    $q->where('status', 'ouvert');
                }
            ])
            ->get();

        // 5. Activité Récente (Logs pertinents pour cet ingénieur)
        // On cherche les logs où l'ingénieur est acteur OU où ses ressources sont concernées (plus complexe, on commence par acteur)
        $recentActivity = Log::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('engineer.dashboard', compact('stats', 'resources', 'recentActivity'));
    }

    public function rackMap()
    {
        $user = Auth::user();
        // Get only resources managed by this user that have a rack position
        $resources = Resource::where('manager_id', $user->id)
            ->whereNotNull('rack_position')
            ->get();

        $rack = [];
        foreach ($resources as $res) {
            $pos = strtoupper($res->rack_position);
            $rack[$pos] = $res;
        }

        return view('engineer.rack_map', compact('rack'));
    }
}
