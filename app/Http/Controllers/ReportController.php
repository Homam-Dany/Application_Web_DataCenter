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
            'total_incidents' => \App\Models\Incident::whereBetween('created_at', [$startDate, $endDate])->count(),
            'resolved_incidents' => \App\Models\Incident::where('status', 'Résolu')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_users' => \App\Models\User::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        // 1.1 Stats Inventaire
        $resourceStats = [
            'total' => Resource::count(),
            'active' => Resource::where('status', 'disponible')->count(),
            'maintenance' => Resource::where('status', 'maintenance')->count(),
            'racked' => Resource::whereNotNull('rack_position')->count(),
            'occupancy_percentage' => round((Resource::whereNotNull('rack_position')->count() / 42) * 100, 1)
        ];

        // 1.2 Top Utilisateurs
        $topUsers = \App\Models\User::withCount('reservations')
            ->orderByDesc('reservations_count')
            ->take(5)
            ->get();

        $logs = Log::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(50)
            ->get();

        // 2. Génération du PDF
        $pdf = PDF::loadView('reports.monthly_pdf', compact('stats', 'logs', 'resourceStats', 'topUsers'));

        // 3. Téléchargement
        return $pdf->download('DataCenter_Rapport_' . now()->format('Y_m') . '.pdf');
    }
}
