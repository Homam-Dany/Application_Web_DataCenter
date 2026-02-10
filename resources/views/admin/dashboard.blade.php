@extends('layouts.app')

@push('styles')
    @vite(['resources/css/dashboard.css', 'resources/css/admin/dashboard.css'])
@endpush

@section('content')
    <div class="dashboard-wrapper">
        {{-- HEADER --}}
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">
                    <span>Admin Center</span>
                </h1>
                <p class="dashboard-subtitle">Supervision globale et gestion du système.</p>
            </div>

            <div class="header-actions">
                <a href="{{ route('reservations.manager') }}" class="btn btn-header-secondary">
                    <i class="fas fa-inbox"></i>
                    Demandes
                    @if($stats['pending_requests'] > 0)
                        <span class="notification-badge">{{ $stats['pending_requests'] }}</span>
                    @endif
                </a>

                <a href="{{ route('reports.monthly') }}" class="btn btn-header-outline">
                    <i class="fas fa-file-pdf"></i> Rapport
                </a>

                <a href="{{ route('admin.rack_map') }}" class="btn"
                    style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; font-weight: 600; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);">
                    <i class="fas fa-th" style="margin-right: 8px;"></i> Visual Map
                </a>

                <div class="admin-badge">
                    <i class="fas fa-shield-alt"></i> ADM
                </div>
            </div>
        </div>

        {{-- METRICS ROW --}}
        <div class="dashboard-stats-grid">
            {{-- 1. Occupation --}}
            <div class="stat-card-custom">
                <p class="stat-card-label">Taux d'Occupation</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value" id="stat-occupancy">{{ $stats['occupancy_rate'] }}%</h2>
                    <div class="stat-card-icon-wrapper" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="stat-progress-container"
                    style="margin-top: 15px; background: rgba(0,0,0,0.05); height: 6px; border-radius: 3px; overflow: hidden;">
                    <div class="stat-progress-fill"
                        style="width: {{ $stats['occupancy_rate'] }}%; background: #3b82f6; height: 100%;"></div>
                </div>
            </div>

            {{-- 2. Total Unités --}}
            <div class="stat-card-custom">
                <p class="stat-card-label">Ressources Total</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value" id="stat-total">{{ $stats['total_resources'] }}</h2>
                    <div class="stat-card-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-server"></i>
                    </div>
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: auto; padding-top: 10px;">
                    Matériel physique et virtuel
                </p>
            </div>

            {{-- 3. Maintenance --}}
            <div class="stat-card-custom">
                <p class="stat-card-label">Maintenance</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value" id="stat-maintenance">{{ $maintenanceCount }}</h2>
                    <div class="stat-card-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: auto; padding-top: 10px;">
                    Interventions en cours
                </p>
            </div>

            {{-- 4. Comptes en Attente --}}
            <div class="stat-card-custom">
                <p class="stat-card-label">Utilisateurs</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value">{{ $stats['total_users'] }}</h2>
                    <div class="stat-card-icon-wrapper" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                @if($stats['pending_accounts'] > 0)
                    <p style="font-size: 0.8rem; color: #ef4444; margin-top: auto; padding-top: 10px; font-weight: 600;"
                        id="stat-pending-wrapper">
                        <i class="fas fa-exclamation-circle"></i> <span
                            id="stat-pending">{{ $stats['pending_accounts'] }}</span> en attente validation
                    </p>
                @else
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: auto; padding-top: 10px;"
                        id="stat-pending-wrapper">
                        Tous les comptes sont à jour
                    </p>
                @endif
            </div>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="reference-grid">
            {{-- LEFT COLUMN: Charts --}}
            <div class="charts-section">
                {{-- Row of 2 Small Charts --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title"><i class="fas fa-chart-donut" style="color: var(--primary);"></i>
                                Disponibilité</h3>
                        </div>
                        <div style="height: 200px; position: relative;">
                            <canvas id="occupancyChart" data-active="{{ $stats['active_reservations'] }}"
                                data-total="{{ $stats['total_resources'] }}">
                            </canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title"><i class="fas fa-exclamation-circle" style="color: var(--danger);"></i>
                                Incidents</h3>
                        </div>
                        <div style="height: 200px; position: relative;">
                            <canvas id="incidentsChart" data-labels="{{ json_encode($incidentsByStatus->pluck('status')) }}"
                                data-values="{{ json_encode($incidentsByStatus->pluck('total')) }}">
                            </canvas>
                        </div>
                    </div>
                </div>

                {{-- Full Width Bar Chart --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title"><i class="fas fa-boxes" style="color: var(--success);"></i> Inventaire
                            Matériel</h3>
                    </div>
                    <div style="height: 250px; position: relative;">
                        <canvas id="inventoryChart" data-labels="{{ json_encode($resourcesByType->pluck('type')) }}"
                            data-values="{{ json_encode($resourcesByType->pluck('total')) }}">
                        </canvas>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Logs & Quick Activity --}}
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="logs-card">
                    <div class="logs-header">
                        <h3 class="chart-title"><i class="fas fa-history" style="color: #64748b;"></i> Audit Logs</h3>
                        <a href="{{ route('admin.logs') }}"
                            style="font-size: 0.85rem; font-weight: 600; color: var(--primary); text-decoration: none;">Voir
                            tout <i class="fas fa-arrow-right"></i></a>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Qui</th>
                                    <th>Quoi</th>
                                    <th>Quand</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLogs as $log)
                                    @php
                                        $actionColor = match ($log->action) {
                                            'Signalement' => 'var(--warning)',
                                            'Incident Résolu' => 'var(--success)',
                                            'Demande Réservation' => 'var(--primary)',
                                            'Gestion Admin' => '#8b5cf6',
                                            'Admin: Mise à jour' => '#06b6d4',
                                            default => 'var(--primary)'
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600; font-size: 0.9rem;">
                                                {{ $log->user->name ?? 'Système' }}
                                            </div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                {{ $log->user->role ?? '' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                style="font-weight: 700; color: {{ $actionColor }}; font-size: 0.8rem;">{{ $log->action }}</span>
                                        </td>
                                        <td style="color: var(--text-secondary); font-size: 0.8rem; white-space: nowrap;">
                                            {{ $log->created_at->format('d/m H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/admin/dashboard.js'])
@endpush