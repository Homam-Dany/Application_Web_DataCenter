@extends('layouts.app')

@section('title', 'Tableau de bord')

@push('styles')
    @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
    <div class="dashboard-wrapper">
        <div class="page-header dashboard-header">
            <div>
                <h1 class="dashboard-title">
                    Dashboard - Espace Ingénieur
                </h1>
                <p class="dashboard-subtitle">Bienvenue sur votre interface de gestion
                    centralisée.</p>
            </div>
        </div>

        {{-- Ligne des statistiques --}}
        <div class="dashboard-stats-grid">

            {{-- 1. Taux d'Occupation --}}
            <div class="card stat-card-custom">
                <p class="stat-card-label">Occupation</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value">{{ $occupancyRate }}%</h2>
                    <div class="stat-card-icon-wrapper stat-card-icon-primary">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="stat-progress-container">
                    <div class="stat-progress-bar" style="width: {{ $occupancyRate }}%;"></div>
                </div>
            </div>

            {{-- 2. Total --}}
            @php
                $resTitle = isset($myResourcesCount) ? 'Gérés' : 'Ressources';
                $resValue = $myResourcesCount ?? $totalResources;
            @endphp
            <div class="card stat-card-custom">
                <p class="stat-card-label">{{ $resTitle }}</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value">{{ $resValue }}</h2>
                    <div class="stat-card-icon-wrapper" style="background: rgba(100, 116, 139, 0.1); color: #64748b;">
                        <i class="fas fa-server"></i>
                    </div>
                </div>
                <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 10px;">Total unités</p>
            </div>

            {{-- 3. Disponible --}}
            <div class="card stat-card-custom stat-card-success-accent">
                <p class="stat-card-label">Disponible</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value" style="color: #10b981;">{{ $availableCount }}</h2>
                    <div class="stat-card-icon-wrapper stat-card-icon-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <p style="font-size: 0.75rem; color: #10b981; margin-top: 10px; font-weight: 700;">Prêt à l'usage</p>
            </div>

            {{-- 4. Maintenance --}}
            <div class="card stat-card-custom" style="border-left-color: #f59e0b;">
                <p class="stat-card-label">Maintenance</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value" style="color: #f59e0b;">{{ $maintenanceCount }}</h2>
                    <div class="stat-card-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <p style="font-size: 0.75rem; color: #f59e0b; margin-top: 10px; font-weight: 700;">En entretien</p>
            </div>

            {{-- 5. Bloqué --}}
            <div class="card stat-card-custom" style="border-left-color: #ef4444;">
                <p class="stat-card-label">Bloqué</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value" style="color: #ef4444;">{{ $blockedCount }}</h2>
                    <div class="stat-card-icon-wrapper" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
                <p style="font-size: 0.75rem; color: #ef4444; margin-top: 10px; font-weight: 700;">Désactivé</p>
            </div>

            {{-- 6. Alertes/Demandes --}}
            @php
                $alertColor = '#8b5cf6';
                $alertIcon = 'fa-clock';
                $alertTitle = 'Activités';
                $alertValue = 0;

                if (isset($pendingRequests)) {
                    $alertTitle = 'Demandes';
                    $alertValue = $pendingRequests;
                    $alertIcon = 'fa-inbox';
                } elseif (isset($myPendingRequests)) {
                    $alertTitle = 'Mes Demandes';
                    $alertValue = $myPendingRequests;
                    $alertIcon = 'fa-hourglass-half';
                }
            @endphp
            @if($alertValue > 0)
                <div class="card stat-card-custom stat-card-alt-accent" style="--alert-color: {{ $alertColor }};">
                    <p class="stat-card-label">{{ $alertTitle }}</p>
                    <div class="stat-card-body">
                        <h2 class="stat-card-value">{{ $alertValue }}</h2>
                        <div class="stat-card-icon-wrapper" style="background: {{ $alertColor }}15; color: {{ $alertColor }};">
                            <i class="fas {{ $alertIcon }}"></i>
                        </div>
                    </div>
                    <p style="font-size: 0.75rem; color: {{ $alertColor }}; margin-top: 10px; font-weight: 700;">En attente</p>
                </div>
            @endif
        </div>

        {{-- Résumé / Actions --}}
        <div class="card dashboard-info-card">
            <h3 class="info-card-title">
                <i class="fas fa-info-circle" style="color: var(--primary);"></i> État de votre compte
            </h3>
            <p class="info-card-text">
                Bienvenue dans le système de gestion du Data Center.
                @if(auth()->user()->role === 'user')
                    Vous avez actuellement <strong>{{ $myActiveReservations ?? 0 }}</strong> réservations actives.
                @elseif(auth()->user()->role === 'responsable')
                    Vous gérez <strong>{{ $myResourcesCount ?? 0 }}</strong> ressources stratégiques.
                @endif
                Utilisez le menu supérieur pour accéder au catalogue ou signaler un incident.
            </p>
        </div>
    </div>
@endsection