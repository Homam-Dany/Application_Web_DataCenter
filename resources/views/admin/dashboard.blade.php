@extends('layouts.app')

@push('styles')
    @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
    <div class="dashboard-container">
        {{-- En-tête de la page --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Aperçu en temps réel de l'état du Data Center et des activités système.</p>
            </div>
            <div class="admin-badge">
                <i class="fas fa-shield-alt"></i> Mode Administrateur
            </div>
        </div>

        {{-- Ligne des compteurs --}}
        <div class="stats-grid">
            {{-- Taux d'Occupation --}}
            <div class="stat-card primary">
                <p class="stat-label">Taux d'Occupation</p>
                <div class="stat-card-header">
                    <h2 class="stat-value">{{ $stats['occupancy_rate'] }}%</h2>
                    <div class="stat-icon-wrapper stat-icon-primary">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="stat-progress-bg">
                    <div class="stat-progress-fill" style="width: {{ $stats['occupancy_rate'] }}%;"></div>
                </div>
            </div>

            {{-- Total des Unités --}}
            <div class="stat-card success">
                <p class="stat-label">Total des Unités</p>
                <div class="stat-card-header">
                    <h2 class="stat-value">{{ $stats['total_resources'] }}</h2>
                    <div class="stat-icon-wrapper stat-icon-success">
                        <i class="fas fa-server"></i>
                    </div>
                </div>
            </div>

            {{-- En Maintenance --}}
            <div class="stat-card warning">
                <p class="stat-label">En Maintenance</p>
                <div class="stat-card-header">
                    <h2 class="stat-value">{{ $maintenanceCount }}</h2>
                    <div class="stat-icon-wrapper stat-icon-warning">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
            </div>

            {{-- Comptes en Attente --}}
            <div class="stat-card danger">
                <p class="stat-label">Comptes en Attente</p>
                <div class="stat-card-header">
                    <h2 class="stat-value">{{ $stats['pending_accounts'] }}</h2>
                    <div class="stat-icon-wrapper stat-icon-danger">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graphiques --}}
        <div class="charts-grid">
            <div class="card">
                <h3 class="card-title card-title-with-icon">
                    <i class="fas fa-chart-pie" style="color: var(--primary);"></i> Disponibilité du Cluster
                </h3>
                <div class="chart-canvas-container">
                    <canvas id="occupancyChart" data-active="{{ $stats['active_reservations'] }}"
                        data-total="{{ $stats['total_resources'] }}">
                    </canvas>
                </div>
            </div>
            </canvas>
        </div>
    </div>
    <div class="card">
        <h3 class="card-title" style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-boxes" style="color: var(--success);"></i> Distribution du Matériel
        </h3>
        <div style="height: 250px;">
            <canvas id="inventoryChart" data-labels="{{ json_encode($resourcesByType->pluck('type')) }}"
                data-values="{{ json_encode($resourcesByType->pluck('total')) }}">
            </canvas>
        </div>
    </div>
    </div>

    {{-- Tableau des Logs --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="card-header"
            style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px;">
            <h3 class="card-title" style="margin: 0;">Derniers Logs d'Audit</h3>
            <a href="{{ route('admin.logs') }}" class="btn"
                style="color: var(--primary); font-size: 0.85rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                Voir tous les logs <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateurs</th>
                        <th>Action réalisée</th>
                        <th>Date & Heure</th>
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
                                <div style="font-weight: 600;">{{ $log->user->name ?? 'Système' }}</div>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: {{ $actionColor }};">{{ $log->action }}</span>
                                <span style="color: var(--text-muted); margin: 0 5px;">•</span>
                                {{ $log->description }}
                            </td>
                            <td style="color: var(--text-secondary); font-size: 0.9rem;">
                                {{ $log->created_at->format('d/m H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/admin/dashboard.js'])
@endpush