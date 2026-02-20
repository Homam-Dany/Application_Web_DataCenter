@extends('layouts.app')

@push('styles')
    @vite(['resources/css/reservations/premium_reservation.css'])
@endpush

@section('content')
    <div class="res-premium-container">
        <div class="page-header" style="text-align: center; margin-bottom: 40px;">
            <h1 class="page-title">Réserver une <span>Ressource</span></h1>
            <p class="page-subtitle">Interface optimisée pour les ingénieurs Data Center.</p>
        </div>

        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf

            <!-- SECTION 1: ÉQUIPEMENT -->
            <div class="res-section">
                <div class="res-section-title">
                    <i class="fas fa-server"></i> Choisir l'équipement
                </div>
                <select name="resource_id" id="resource_id" required class="res-select-modern"
                    data-availability-url-base="{{ route('reservations.availability', ['resource' => 'RESOURCE_ID']) }}">
                    <option value="">-- Liste des ressources disponibles --</option>
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}">
                            {{ $resource->name }} ({{ $resource->type }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- SECTION 2: DISPONIBILITÉ -->
            <div class="res-section">
                <div class="res-section-title">
                    <i class="far fa-calendar-alt"></i> Disponibilité & Sélection
                </div>

                <div class="res-calendar-wrapper">
                    <div class="res-calendar-main">
                        <div class="res-calendar-header">
                            <button type="button" id="prev-month" class="btn-icon"><i
                                    class="fas fa-chevron-left"></i></button>
                            <div id="month-title" class="res-calendar-month-title">Février 2026</div>
                            <button type="button" id="next-month" class="btn-icon"><i
                                    class="fas fa-chevron-right"></i></button>
                        </div>
                        <div class="res-calendar-grid" id="calendar-grid">
                            <!-- JS Generated -->
                        </div>
                    </div>

                    <div class="res-calendar-legend">
                        <div class="legend-item">
                            <div class="legend-dot" style="background: transparent;"></div> Indisponible
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background: #f8fafc; border: 1px solid #e2e8f0;"></div> Libre
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background: rgba(239, 68, 68, 0.05); border: 1px solid #ef4444;">
                            </div> Réservé
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background: #6366f1;"></div> Sélection
                        </div>
                    </div>
                </div>

                <div class="res-selection-info">
                    <div class="info-box">
                        <span class="info-label">Date de début</span>
                        <span id="start-date-display" class="info-value">---</span>
                        <input type="hidden" name="start_date" id="start_date" required>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Date de fin</span>
                        <span id="end-date-display" class="info-value">---</span>
                        <input type="hidden" name="end_date" id="end_date" required>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: JUSTIFICATION -->
            <div class="res-section">
                <div class="res-section-title">
                    <i class="fas fa-pen"></i> Justification du besoin
                </div>
                <textarea name="justification" rows="4" required class="res-textarea-modern"
                    placeholder="Expliquez pourquoi vous avez besoin de cette ressource...">{{ old('justification') }}</textarea>
            </div>

            <div class="res-actions">
                <button type="submit" class="btn-confirm-premium">
                    <i class="fas fa-check"></i> Confirmer la réservation
                </button>
                <a href="{{ route('dashboard') }}" class="btn-cancel-premium">Annuler</a>
            </div>
        </form>
    </div>

    @push('scripts')
        @vite(['resources/js/reservations/premium_calendar.js'])
    @endpush
@endsection