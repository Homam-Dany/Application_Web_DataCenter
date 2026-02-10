<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\User;
use App\Models\Resource;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function rackMap()
    {
        $resources = Resource::whereNotNull('rack_position')->get();
        // Transformation simple : ['U10' => $resource, 'U11' => $resource]
        $rack = [];
        foreach ($resources as $res) {
            $pos = strtoupper($res->rack_position); // U12
            // Gestion simplifiée: on suppose 1 U pour l'instant ou format U10-U12
            $rack[$pos] = $res;
        }
        return view('admin.rack_map', compact('rack'));
    }

    /**
     * Dashboard Global avec Statistiques (Point 4.3 de l'énoncé)
     */
    public function dashboard()
    {
        $totalResources = Resource::count();
        // On considère une ressource occupée si elle a une réservation "Approuvée" ou "Active"
        $occupiedResources = Reservation::whereIn('status', ['Approuvée', 'Active'])
            ->distinct('resource_id')
            ->count('resource_id');

        $stats = [
            'total_users' => User::count(),
            'total_resources' => $totalResources,
            'active_reservations' => $occupiedResources,
            'pending_accounts' => User::where('role', 'guest')->where('is_active', false)->count(),
            'pending_requests' => Reservation::where('status', 'en_attente')->count(),
            'total_logs' => Log::count(),
        ];

        // Calcul du taux d'occupation global (Point 4.3)
        $stats['occupancy_rate'] = $totalResources > 0
            ? round(($occupiedResources / $totalResources) * 100)
            : 0;

        $resourcesByType = Resource::select('type', DB::raw('count(*) as total'))->groupBy('type')->get();

        // [NEW] Statistiques des incidents par statut
        $incidentsByStatus = \App\Models\Incident::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $maintenanceCount = Resource::where('status', 'maintenance')->count();
        $recentLogs = Log::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'resourcesByType', 'incidentsByStatus', 'maintenanceCount', 'recentLogs'));
    }

    public function apiStats()
    {
        $totalResources = Resource::count();
        $occupiedResources = Reservation::whereIn('status', ['Approuvée', 'Active'])
            ->distinct('resource_id')
            ->count('resource_id');

        $stats = [
            'total_users' => User::count(),
            'total_resources' => $totalResources,
            'active_reservations' => $occupiedResources,
            'pending_accounts' => User::where('role', 'guest')->where('is_active', false)->count(),
            'occupancy_rate' => $totalResources > 0 ? round(($occupiedResources / $totalResources) * 100) : 0,
            'maintenance_count' => Resource::where('status', 'maintenance')->count(),
        ];

        $incidentsByStatus = \App\Models\Incident::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'stats' => $stats,
            'incidents' => $incidentsByStatus
        ]);
    }

    /**
     * Export des Utilisateurs (CSV)
     */
    public function exportUsers()
    {
        $fileName = 'users_' . date('Y-m-d_H-i') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Nom', 'Email', 'Rôle', 'Actif', 'Inscrit le']);

            foreach (User::cursor() as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->is_active ? 'Oui' : 'Non',
                    $user->created_at->format('d/m/Y H:i')
                ]);
            }
            fclose($handle);
        }, $fileName);
    }

    /**
     * Gestion des Utilisateurs (Point 4.1 et 4.5)
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    /**
     * Consultation des Logs globaux (Traçabilité demandée)
     */
    public function logs(Request $request)
    {
        $query = Log::with('user');

        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }

        $logs = $query->latest()->paginate(25)->withQueryString();
        return view('admin.logs', compact('logs'));
    }

    /**
     * Activation/Désactivation et Rôles (Point 4.5)
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'role' => 'nullable|in:guest,user,responsable,admin',
            'is_active' => 'nullable|boolean',
        ]);

        $oldStatus = $user->is_active;

        if ($request->has('role'))
            $user->role = $request->role;
        if ($request->has('is_active'))
            $user->is_active = $request->is_active;
        if ($request->has('rejection_reason'))
            $user->rejection_reason = $request->rejection_reason;

        $user->save();

        // Log de l'action admin
        $actionDescription = "Profil mis à jour pour {$user->email} (Rôle: {$user->role}, Actif: {$user->is_active})";
        if ($request->has('rejection_reason') && $request->rejection_reason) {
            $actionDescription .= " - Refusé pour : " . $request->rejection_reason;
        }

        Log::create([
            'user_id' => auth()->id(),
            'action' => 'Gestion Admin',
            'description' => $actionDescription
        ]);

        // Envoi d'email en cas d'activation
        if (!$oldStatus && $user->is_active) {
            try {
                $user->notify(new \App\Notifications\AccountActivatedNotification($user));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("SMTP Notification Error: " . $e->getMessage());
            }
        }

        // Envoi d'email en cas de refus
        if ($request->has('rejection_reason') && $request->rejection_reason) {
            try {
                $user->notify(new \App\Notifications\AccountRefusedNotification($user, $request->rejection_reason));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("SMTP Notification Error (Refusal): " . $e->getMessage());
            }
        }


        return redirect()->back()->with('success', 'Modifications système enregistrées.');
    }

    public function destroyUser(User $user)
    {
        // On ne supprime pas son propre compte
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Impossible de supprimer votre propre compte.');
        }

        $email = $user->email;
        $user->delete();

        Log::create([
            'user_id' => auth()->id(),
            'action' => 'Suppression Compte',
            'description' => "Compte supprimé : $email"
        ]);

        return redirect()->back()->with('success', 'Compte utilisateur supprimé définitivement.');
    }
}