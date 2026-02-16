@extends('layouts.app')

@push('styles')
    @vite(['resources/css/dashboard.css', 'resources/css/engineer/dashboard.css'])
@endpush


@section('content')
    <div class="dashboard-wrapper">
        {{-- HEADER --}}
        <div class="page-header dashboard-header">
            <div>
                <h1 class="dashboard-title">
                    <span
                        style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Dashboard - Espace
                        Responsable</span>
                </h1>
                <p class="dashboard-subtitle">Gestion temps réel de votre parc informatique.</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('reservations.manager') }}" class="btn"
                    style="background: white; color: var(--text-primary); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); font-weight: 600; position: relative;">
                    <i class="fas fa-inbox" style="margin-right: 8px; color: var(--text-secondary);"></i>
                    Demandes
                    @if($stats['pending_requests'] > 0)
                        <span class="notification-badge">{{ $stats['pending_requests'] }}</span>
                    @endif
                </a>
                <a href="{{ route('resources.create') }}" class="btn"
                    style="background: var(--primary-gradient); color: white; border: none; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i> Nouveau Serveur
                </a>
            </div>
        </div>

        {{-- METRICS ROW --}}
        <div class="dashboard-stats-grid">
            {{-- 1. Total Managed --}}
            <div class="card stat-card-custom">
                <p class="stat-card-label">Serveurs Gérés</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value">{{ $stats['total_managed'] }}</h2>
                    <div class="stat-card-icon-wrapper" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fas fa-server"></i>
                    </div>
                </div>
                <div class="stat-progress-container">
                    <div class="stat-progress-bar"
                        style="width: {{ $stats['occupancy_rate'] }}%; background: var(--primary-gradient);"></div>
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 8px; font-weight: 500;">
                    <span style="color: #10b981;">{{ $stats['occupancy_rate'] }}%</span> Occupation
                </p>
            </div>

            {{-- 2. Maintenance --}}
            <div class="card stat-card-custom">
                <p class="stat-card-label">En Maintenance</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value">{{ $stats['maintenance_mode'] }}</h2>
                    <div class="stat-card-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: auto; padding-top: 10px;">
                    Interventions en cours</p>
            </div>

            {{-- 3. Incidents --}}
            <div class="card stat-card-custom">
                <p class="stat-card-label">Incidents Actifs</p>
                <div class="stat-card-body">
                    <h2 class="stat-card-value" style="color: #ef4444;">{{ $stats['active_incidents'] }}</h2>
                    <div class="stat-card-icon-wrapper" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: auto; padding-top: 10px;">Requiert
                    attention immédiate</p>
            </div>
        </div>

        {{-- MAIN GRID --}}
        <div class="engineer-grid">
            {{-- LEFT: VISUAL SERVER GRID --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-th-large" style="color: var(--primary);"></i> Vue Synthétique
                    </h3>
                    <div style="font-size: 0.85rem; font-weight: 500;">
                        <span style="margin-right: 15px; display: inline-flex; align-items: center; gap: 6px;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span> OK
                        </span>
                        <span style="margin-right: 15px; display: inline-flex; align-items: center; gap: 6px;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b;"></span> Maint.
                        </span>
                        <span style="display: inline-flex; align-items: center; gap: 6px;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444;"></span> Incident
                        </span>
                    </div>
                </div>

                <div class="server-grid">
                    @forelse($resources as $res)
                        @php
                            $statusClass = 'status-disponible';
                            $icon = 'fa-server';
                            $iconColor = '#10b981';

                            if ($res->status === 'maintenance') {
                                $statusClass = 'status-maintenance';
                                $icon = 'fa-tools';
                                $iconColor = '#f59e0b';
                            } elseif ($res->incidents->isNotEmpty()) {
                                $statusClass = 'status-incident';
                                $icon = 'fa-exclamation-triangle';
                                $iconColor = '#ef4444';
                            }
                        @endphp
                        <a href="{{ route('resources.edit', $res->id) }}" class="server-card {{ $statusClass }}"
                            title="{{ $res->name }}">
                            <div class="server-icon-wrapper" style="color: {{ $iconColor }}">
                                <i class="fas {{ $icon }}"></i>
                            </div>
                            <div class="server-name">{{ $res->name }}</div>
                            <span class="server-badge">
                                <i class="fas fa-map-marker-alt" style="margin-right: 4px; font-size: 0.7em;"></i>
                                {{ $res->rack_position ?? 'N/A' }}
                            </span>
                        </a>
                    @empty
                        <div style="grid-column: 1/-1; padding: 40px; text-align: center; color: var(--text-secondary);">
                            <div style="font-size: 3rem; margin-bottom: 20px; opacity: 0.2;"><i class="fas fa-server"></i></div>
                            <p>Aucune ressource assignée à votre compte.</p>
                            <a href="{{ route('resources.create') }}" class="btn btn-sm btn-primary"
                                style="margin-top: 15px;">Ajouter une ressource</a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- RIGHT: ACTIVITY & QUICK TOOLS --}}
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-history" style="color: #8b5cf6;"></i> Activité Récente</h3>
                    </div>
                    <div class="activity-feed">
                        @forelse($recentActivity as $log)
                            <div class="feed-item">
                                <div class="feed-icon">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div
                                        style="font-weight: 600; font-size: 0.9rem; color: var(--text-primary); margin-bottom: 2px;">
                                        {{ $log->action }}</div>
                                    <div style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.4;">
                                        {{ Str::limit($log->description, 60) }}
                                    </div>
                                    <div
                                        style="color: var(--text-muted); font-size: 0.75rem; margin-top: 6px; display: flex; align-items: center; gap: 5px;">
                                        <i class="far fa-clock"></i> {{ $log->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p style="padding: 24px; text-align: center; color: var(--text-secondary);">Aucune activité récente.
                            </p>
                        @endforelse
                    </div>
                    @if($recentActivity->count() > 0)
                        <div style="padding: 16px; border-top: 1px solid var(--border-color); text-align: center;">
                            <a href="{{ route('reservations.history') }}"
                                style="color: var(--primary); font-weight: 600; font-size: 0.85rem; text-decoration: none;">Voir
                                tout l'historique</a>
                        </div>
                    @endif
                </div>

                {{-- Quick Tools --}}
                <div class="card" style="overflow: visible;">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-rocket" style="color: #ec4899;"></i> Outils Rapides</h3>
                    </div>
                    <div style="padding: 20px;">
                        <a href="{{ route('resources.export') }}" class="quick-tool-link">
                            <div style="display: flex; align-items: center;">
                                <div class="quick-tool-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                    <i class="fas fa-file-csv"></i>
                                </div>
                                <span style="font-weight: 600;">Export Inventaire</span>
                            </div>
                            <i class="fas fa-arrow-right" style="color: var(--text-muted);"></i>
                        </a>

                        <a href="{{ route('engineer.rack_map') }}" class="quick-tool-link">
                            <div style="display: flex; align-items: center;">
                                <div class="quick-tool-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                    <i class="fas fa-th"></i>
                                </div>
                                <span style="font-weight: 600;">Carte des Racks</span>
                            </div>
                            <i class="fas fa-arrow-right" style="color: var(--text-muted);"></i>
                        </a>

                        <a href="#" class="quick-tool-link" style="margin-bottom: 0;">
                            <div style="display: flex; align-items: center;">
                                <div class="quick-tool-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                                    <i class="fas fa-print"></i>
                                </div>
                                <span style="font-weight: 600;">Imprimer Étiquettes</span>
                            </div>
                            <i class="fas fa-arrow-right" style="color: var(--text-muted);"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection