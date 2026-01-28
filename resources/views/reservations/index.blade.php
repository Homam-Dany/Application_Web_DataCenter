@extends('layouts.app')

@push('styles')
    @vite(['resources/css/reservations/index.css'])
@endpush

@section('content')
    <div class="page-header res-index-header">
        <div>
            <h1 class="page-title">Mon Historique <span>Data Center</span></h1>
            <p class="page-subtitle res-index-subtitle">Consultez et filtrez vos réservations passées et en cours.</p>
        </div>
        <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-new-res">
            <i class="fas fa-plus-circle"></i> Nouvelle Réservation
        </a>
    </div>

    <div class="card filter-card">
        <div class="card-body">
            <form action="{{ route('reservations.index') }}" method="GET" class="filter-form">

                <div class="filter-group-wide">
                    <label class="filter-label">
                        <i class="fas fa-server"></i> Ressource
                    </label>
                    <input type="text" name="resource" value="{{ request('resource') }}" placeholder="Ex: Serveur AI..."
                        class="filter-input">
                </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-filter"></i> État
                    </label>
                    <select name="status" class="filter-select">
                        <option value="">Tous les états</option>
                        <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente
                        </option>
                        <option value="Approuvée" {{ request('status') == 'Approuvée' ? 'selected' : '' }}>Approuvée</option>
                        <option value="Refusée" {{ request('status') == 'Refusée' ? 'selected' : '' }}>Refusée</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Terminée" {{ request('status') == 'Terminée' ? 'selected' : '' }}>Terminée</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">
                        <i class="far fa-calendar-alt"></i> Date
                    </label>
                    <input type="date" name="date" value="{{ request('date') }}" class="filter-input">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary btn-new-res">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="{{ route('reservations.index') }}" class="btn-reset-filter">
                        <i class="fas fa-undo icon-margin"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="res-grid">
        @forelse($allReservations as $res)
            <div class="card res-card-item">
                <div class="card-body">
                    <div class="res-card-header-flex">
                        <div>
                            <h3 class="res-card-title-text">{{ $res->resource->name }}</h3>
                            <p class="res-card-period">
                                <i class="far fa-clock"></i> <strong>Période :</strong> Du
                                {{ $res->start_date->format('d/m/Y') }} au {{ $res->end_date->format('d/m/Y') }}
                            </p>
                        </div>

                        @php
                            $badgeClass = 'badge-warning'; // Fallback
                            $statusLabel = $res->status;

                            if ($res->status == 'Approuvée' && now()->between($res->start_date, $res->end_date)) {
                                $badgeClass = 'badge-success';
                                $statusLabel = 'Active';
                            } elseif ($res->status == 'Approuvée') {
                                $badgeClass = 'badge-success';
                            } elseif ($res->status == 'Terminée') {
                                $badgeClass = 'badge-secondary';
                            } elseif ($res->status == 'Refusée') {
                                $badgeClass = 'badge-danger';
                            } elseif ($res->status == 'en_attente') {
                                $badgeClass = 'badge-warning';
                            }
                        @endphp

                        <span class="badge {{ $badgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="res-info-segment">
                        <strong class="res-info-label">Justification fournie :</strong>
                        <p class="res-info-text">"{{ $res->justification }}"</p>
                    </div>

                    {{-- Motif de refus --}}
                    @if($res->status == 'Refusée' && $res->rejection_reason)
                        <div class="res-rejection-segment">
                            <strong class="res-rejection-label">Motif du refus :</strong>
                            <p class="res-rejection-text">"{{ $res->rejection_reason }}"</p>
                        </div>
                    @endif

                    <div class="res-card-actions">
                        <a href="#" title="Signaler un incident" class="btn-report-problem"
                            data-resource-id="{{ $res->resource->id }}">
                            <i class="fas fa-exclamation-triangle"></i> Signaler un problème
                        </a>

                        @if($res->status == 'en_attente')
                            <form action="{{ route('reservations.destroy', $res->id) }}" method="POST"
                                onsubmit="return confirm('Voulez-vous vraiment annuler cette demande ?')" style="display: flex;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-cancel-reservation">
                                    <i class="fas fa-times-circle"></i> Annuler la demande
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card res-empty-state">
                <i class="fas fa-search res-empty-icon"></i>
                <p class="res-empty-title">Aucune réservation ne correspond à vos critères.</p>
                <a href="{{ route('reservations.index') }}" class="res-clear-filters">Effacer les filtres</a>
            </div>
        @endforelse
    </div>

    {{-- Modal de Signalement d'Incident --}}
    <div id="incidentModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-exclamation-triangle" style="color: #f97316;"></i> Signaler un problème
                </h3>
                <button type="button" class="close-modal" id="closeIncidentModal">&times;</button>
            </div>
            <form action="{{ route('incidents.store') }}" method="POST">
                @csrf
                <input type="hidden" name="resource_id" id="modal_resource_id">

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="filter-label">Sujet de l'incident</label>
                    <input type="text" name="subject" placeholder="Ex: Panne réseau, Surchauffe..." required
                        class="form-control">
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="filter-label">Description détaillée</label>
                    <textarea name="description" placeholder="Décrivez le problème rencontré sur cette ressource..."
                        required class="form-control" style="min-height: 120px; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; gap: 15px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="cancelIncidentBtn"
                        style="background: #e2e8f0; color: #475569;">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Envoyer le signalement
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/reservations/index.js'])
@endpush