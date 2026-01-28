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
                    Dashboard
                </h1>
                <p class="dashboard-subtitle">Bienvenue sur votre interface de gestion
                    centralisée.</p>
            </div>
        </div>

        {{-- Ligne des statistiques --}}
        <div class="dashboard-stats-grid">

            {{-- 1. Taux d'Occupation (Global) --}}
            <div class="card stat-card-custom">
                <p class="stat-card-label">
                    Taux d'Occupation</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value">{{ $occupancyRate }}%</h2>
                    <div class="stat-card-icon-wrapper stat-card-icon-primary">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="stat-progress-container">
                    <div class="stat-progress-bar" style="width: {{ $occupancyRate }}%;">
                    </div>
                </div>
            </div>

            {{-- 2. Ressources (Total ou Gérées) --}}
            @php
                $resTitle = isset($myResourcesCount) ? 'Unités sous ma Gestion' : 'Ressources Totales';
                $resValue = $myResourcesCount ?? $totalResources;
            @endphp
            <div class="card stat-card-custom stat-card-success-accent">
                <p class="stat-card-label">
                    {{ $resTitle }}
                </p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value">{{ $resValue }}</h2>
                    <div class="stat-card-icon-wrapper stat-card-icon-success">
                        <i class="fas fa-server"></i>
                    </div>
                </div>
            </div>

            {{-- 3. Alertes/Demandes (Selon rôle) --}}
            @php
                $alertColor = '#f59e0b';
                $alertIcon = 'fa-tools';
                $alertTitle = 'En Maintenance';
                $alertValue = $maintenanceCount;

                if (isset($pendingRequests)) {
                    $alertTitle = 'Requêtes en attente';
                    $alertValue = $pendingRequests;
                    $alertIcon = 'fa-clock';
                    $alertColor = '#f59e0b';
                } elseif (isset($myPendingRequests)) {
                    $alertTitle = 'Mes Demandes';
                    $alertValue = $myPendingRequests;
                    $alertIcon = 'fa-hourglass-half';
                    $alertColor = '#f59e0b';
                }
            @endphp
            <div class="card stat-card-custom stat-card-alt-accent" style="--alert-color: {{ $alertColor }};">
                <p class="stat-card-label">
                    {{ $alertTitle }}
                </p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value">{{ $alertValue }}</h2>
                    <div class="stat-card-icon-wrapper" style="background: {{ $alertColor }}15; color: {{ $alertColor }};">
                        <i class="fas {{ $alertIcon }}"></i>
                    </div>
                </div>
            </div>
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