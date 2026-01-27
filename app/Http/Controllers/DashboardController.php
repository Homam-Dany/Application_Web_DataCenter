<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord avec les statistiques demandées.
     */
    public function index()
    {
        $user = Auth::user();

        // Si l'utilisateur est admin, on le redirige vers son dashboard dédié
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 1. CALCUL DU TAUX D'OCCUPATION RÉEL
        $totalResources = Resource::where('status', '!=', 'désactivée')->count();

        // Ressources occupées = ayant une réservation 'Approuvée' en cours à cet instant
        $occupiedResources = Reservation::where('status', 'Approuvée')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->distinct('resource_id')
            ->count('resource_id');

        $occupancyRate = $totalResources > 0 ? round(($occupiedResources / $totalResources) * 100, 1) : 0;

        // 2. PRÉPARATION DES DONNÉES DE BASE
        $data = [
            'occupancyRate' => $occupancyRate,
            'totalResources' => $totalResources,
            // 'maintenanceCount' => Resource::where('status', 'maintenance')->count(), // Assuming 'maintenance' status exists or using boolean
            'maintenanceCount' => Resource::where('en_maintenance', true)->orWhere('status', 'maintenance')->count(),
        ];

        // 3. STATISTIQUES SELON LE RÔLE
        if ($user->isAdmin()) {
            $data['totalUsers'] = User::count();
            // $data['pendingAccounts'] = User::where('is_active', false)->count(); // If is_active exists
            $data['totalReservations'] = Reservation::count();
        } elseif ($user->isResponsable()) {
            $managedIds = Resource::where('manager_id', $user->id)->pluck('id');
            $data['myResourcesCount'] = $managedIds->count();
            $data['pendingRequests'] = Reservation::whereIn('resource_id', $managedIds)
                ->where('status', 'en_attente')
                ->count();
        } else {
            $data['myActiveReservations'] = Reservation::where('user_id', $user->id)
                ->where('status', 'Approuvée')
                ->where('end_date', '>=', now())
                ->count();
            $data['myPendingRequests'] = Reservation::where('user_id', $user->id)
                ->where('status', 'en_attente')
                ->count();
        }

        // Return view with data
        return view('dashboard', $data);
    }
}