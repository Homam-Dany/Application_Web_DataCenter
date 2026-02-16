<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Reservation;
use App\Models\Resource;
use PDF; // Alias for Barryvdh\DomPDF\Facade\Pdf

class ReportController extends Controller
{
    public function downloadMonthlyReport()
    {
        // 1. Récupération des données du mois en cours
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $stats = [
            'period' => $startDate->format('F Y'),
            'total_reservations' => Reservation::whereBetween('created_at', [$startDate, $endDate])->count(),
            'approved_reservations' => Reservation::where('status', 'Approuvée')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'reservations_by_status' => Reservation::whereBetween('created_at', [$startDate, $endDate])
                ->select('status', \DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'total_incidents' => \App\Models\Incident::whereBetween('created_at', [$startDate, $endDate])->count(),
            'resolved_incidents' => \App\Models\Incident::where('status', 'resolu')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'incidents_by_status' => \App\Models\Incident::whereBetween('created_at', [$startDate, $endDate])
                ->select('status', \DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'new_users' => \App\Models\User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending_accounts' => \App\Models\User::where('role', 'guest')->where('is_active', false)->count(),
        ];

        // 1.1 Stats Inventaire
        $resourceStats = [
            'total' => Resource::count(),
            'active' => Resource::where('status', 'disponible')->count(),
            'maintenance' => Resource::where('status', 'maintenance')->count(),
            'racked' => Resource::whereNotNull('rack_position')->count(),
            'occupancy_percentage' => round((Resource::whereNotNull('rack_position')->count() / 42) * 100, 1),
            'by_type' => Resource::select('type', \DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get()
        ];

        // 1.2 Top Utilisateurs & Top Ressources
        $topUsers = \App\Models\User::withCount([
            'reservations' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
        ])
            ->orderByDesc('reservations_count')
            ->take(5)
            ->get();

        $topResources = Resource::withCount([
            'reservations' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
        ])
            ->orderByDesc('reservations_count')
            ->take(5)
            ->get();

        $logs = Log::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(50)
            ->get();

        // 2. Génération du PDF
        $pdf = PDF::loadView('reports.monthly_pdf', compact('stats', 'logs', 'resourceStats', 'topUsers', 'topResources'));

        // 3. Téléchargement
        return $pdf->download('DataCenter_Rapport_' . now()->format('Y_m') . '.pdf');
    }
}
