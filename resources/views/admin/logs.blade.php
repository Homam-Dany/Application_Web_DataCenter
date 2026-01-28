@extends('layouts.app')

@push('styles')
    @vite(['resources/css/admin/logs.css'])
@endpush

@section('content')
    <div class="logs-container">
        {{-- En-tête de la page --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Journal de <span>Traçabilité</span></h1>
                <p class="page-subtitle">Audit complet des actions effectuées sur la plateforme.</p>
            </div>
            <div class="admin-badge">
                <i class="fas fa-history"></i> {{ $logs->total() }} événements
            </div>
        </div>

        {{-- Barre de Filtres --}}
        <div class="card filter-card">
            <form action="{{ route('admin.logs') }}" method="GET" class="filter-form">
                {{-- Filtre Action --}}
                <div class="filter-group-action">
                    <label class="stat-label"
                        style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.05em; color: var(--text-secondary);">
                        <i class="fas fa-filter" style="color: var(--primary); font-size: 0.9rem;"></i> Filtrer par action
                    </label>
                    <div style="position: relative;">
                        <select name="action" id="logActionSelect" class="form-control">
                            <option value="">Toutes les actions</option>
                            <option value="Signalement" {{ request('action') == 'Signalement' ? 'selected' : '' }}>Signalement
                            </option>
                            <option value="Incident Résolu" {{ request('action') == 'Incident Résolu' ? 'selected' : '' }}>
                                Incident Résolu</option>
                            <option value="Demande Réservation" {{ request('action') == 'Demande Réservation' ? 'selected' : '' }}>Demande Réservation</option>
                            <option value="Gestion Admin" {{ request('action') == 'Gestion Admin' ? 'selected' : '' }}>Gestion
                                Admin</option>
                            <option value="Admin: Mise à jour" {{ request('action') == 'Admin: Mise à jour' ? 'selected' : '' }}>Admin: Mise à jour</option>
                        </select>
                    </div>
                </div>

                {{-- Recherche Utilisateur --}}
                <div class="filter-group-search">
                    <label class="stat-label"
                        style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.05em; color: var(--text-secondary);">
                        <i class="fas fa-search" style="color: var(--primary); font-size: 0.9rem;"></i> Recherche rapide
                    </label>
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon-inside"></i>
                        <input type="text" id="logSearch" onkeyup="filterLogs()" class="form-control search-input-field"
                            placeholder="Rechercher un utilisateur, une action...">
                    </div>
                </div>

                <div style="flex: 0;">
                    <a href="{{ route('admin.logs') }}" class="btn btn-reset-filters">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        {{-- Table des Logs --}}
        <div class="card" style="padding: 0; overflow: hidden;">
            <table id="logTable" class="table">
                <thead>
                    <tr>
                        <th>Utilisateurs</th>
                        <th>Action réalisée</th>
                        <th>Date & Heure</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
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
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div
                                        style="width: 32px; height: 32px; background: var(--bg-hover); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); font-weight: 700; font-size: 0.8rem;">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div style="font-weight: 600; font-size: 0.95rem;">
                                        {{ $log->user->name ?? 'Système' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 6px;">
                                    <span
                                        style="font-weight: 700; color: {{ $actionColor }}; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.3px;">
                                        {{ $log->action }}
                                    </span>
                                    <span style="color: var(--text-muted);">•</span>
                                    <span style="font-size: 0.9rem;">
                                        {{ $log->description }}
                                    </span>
                                </div>
                            </td>
                            <td style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 500;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="far fa-clock"></i>
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 60px;">
                                <div
                                    style="width: 60px; height: 60px; background: var(--bg-hover); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: var(--text-muted); font-size: 1.5rem;">
                                    <i class="fas fa-history"></i>
                                </div>
                                <p style="color: var(--text-secondary); font-weight: 500;">Aucune action enregistrée.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 25px; display: flex; justify-content: center;">
            <div class="custom-pagination">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 25px; display: flex; justify-content: center;">
            <div class="custom-pagination">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/admin/logs.js'])
@endpush