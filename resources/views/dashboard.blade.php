@extends('layouts.app')

@section('title', 'Tableau de bord')

@push('styles')
    @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
    <div style="padding: 20px;">
        <div class="page-header"
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: #1e293b; font-size: 1.875rem; font-weight: 800; margin: 0; letter-spacing: -0.025em;">
                    Dashboard
                </h1>
                <p style="color: #64748b; margin-top: 0.5rem; font-size: 1rem;">Bienvenue sur votre interface de gestion
                    centralisée.</p>
            </div>
        </div>

        {{-- Ligne des statistiques --}}
        <div class="stats-grid"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 30px;">

            {{-- 1. Taux d'Occupation (Global) --}}
            <div class="card"
                style="border-left: 4px solid var(--primary); padding: 1.5rem; background: white; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border-radius: 12px;">
                <p
                    style="color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 10px;">
                    Taux d'Occupation</p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 2.2rem; color: #1e293b; font-weight: 800; margin: 0;">{{ $occupancyRate }}%</h2>
                    <div
                        style="background: #eef2ff; color: var(--primary); width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div style="width: 100%; background: #f1f5f9; height: 6px; border-radius: 3px; margin-top: 15px;">
                    <div
                        style="width: {{ $occupancyRate }}%; background: var(--primary); height: 100%; border-radius: 3px;">
                    </div>
                </div>
            </div>

            {{-- 2. Ressources (Total ou Gérées) --}}
            @php
                $resTitle = isset($myResourcesCount) ? 'Unités sous ma Gestion' : 'Ressources Totales';
                $resValue = $myResourcesCount ?? $totalResources;
            @endphp
            <div class="card"
                style="border-left: 4px solid #10b981; padding: 1.5rem; background: white; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border-radius: 12px;">
                <p
                    style="color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 10px;">
                    {{ $resTitle }}
                </p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 2.2rem; color: #1e293b; font-weight: 800; margin: 0;">{{ $resValue }}</h2>
                    <div
                        style="background: #f0fdf4; color: #10b981; width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
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
            <div class="card"
                style="border-left: 4px solid {{ $alertColor }}; padding: 1.5rem; background: white; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border-radius: 12px;">
                <p
                    style="color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 10px;">
                    {{ $alertTitle }}
                </p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 2.2rem; color: #1e293b; font-weight: 800; margin: 0;">{{ $alertValue }}</h2>
                    <div
                        style="background: {{ $alertColor }}15; color: {{ $alertColor }}; width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas {{ $alertIcon }}"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Résumé / Actions --}}
        <div class="card"
            style="padding: 1.5rem; background: white; border-radius: 16px; box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05); border: 1px solid #f1f5f9;">
            <h3
                style="color: #1e293b; margin-bottom: 1rem; font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-info-circle" style="color: var(--primary);"></i> État de votre compte
            </h3>
            <p style="color: #64748b; line-height: 1.6;">
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