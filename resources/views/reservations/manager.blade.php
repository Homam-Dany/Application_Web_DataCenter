@extends('layouts.app')

@push('styles')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    @vite(['resources/css/dashboard.css', 'resources/css/reservations/manager_hub.css'])
@endpush

@section('content')
    {{-- Content remains same until script --}}
    <div class="hub-container">
        {{-- Body content preserved --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Hub Réservations</h1>
                <p class="page-subtitle">Centralisez la gestion de vos demandes et planning.</p>
            </div>
            <div>
            </div>
        </div>

        <div class="hub-tabs">
            <button class="hub-tab-btn active" onclick="switchTab('requests')">
                <i class="fas fa-inbox"></i> Demandes
                @if($pendingReservations->count() > 0)
                    <span class="badge"
                        style="background: #ef4444; color: white; margin-left: 5px; padding: 2px 6px; border-radius: 50%; font-size: 0.7rem;">{{ $pendingReservations->count() }}</span>
                @endif
            </button>
            <button class="hub-tab-btn" onclick="switchTab('calendar')">
                <i class="fas fa-calendar-alt"></i> Calendrier
            </button>
            <button class="hub-tab-btn" onclick="switchTab('history')">
                <i class="fas fa-history"></i> Historique
            </button>
        </div>

        <div id="requests" class="hub-content active">
            @forelse($pendingReservations as $res)
                <div class="req-card">
                    <div class="req-info" style="flex: 1;">
                        <h3>{{ $res->resource->name }}</h3>
                        <p><i class="fas fa-user-circle"></i> <strong>{{ $res->user->name }}</strong> &bull;
                            {{ $res->user->email }}
                        </p>
                        <p><i class="fas fa-clock"></i> Du {{ $res->start_date->format('d/m/Y') }} au
                            {{ $res->end_date->format('d/m/Y') }} ({{ $res->start_date->diffInDays($res->end_date) }} jours)
                        </p>

                        <div class="req-justification">
                            "{{ $res->justification }}"
                        </div>
                    </div>

                    <div class="req-actions">
                        <form action="{{ route('reservations.decide', ['id' => $res->id, 'action' => 'accepter']) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn-accept" style="width: 100%;">
                                <i class="fas fa-check"></i> Accepter
                            </button>
                        </form>

                        <button type="button" class="btn-refuse"
                            onclick="document.getElementById('reject-form-{{ $res->id }}').style.display = 'block'; this.style.display = 'none';">
                            <i class="fas fa-times"></i> Refuser
                        </button>

                        <form id="reject-form-{{ $res->id }}"
                            action="{{ route('reservations.decide', ['id' => $res->id, 'action' => 'refuser']) }}" method="POST"
                            style="display: none;">
                            @csrf
                            <textarea name="rejection_reason" placeholder="Motif du refus..." required
                                style="width: 100%; border-radius: 6px; border: 1px solid #ccc; padding: 8px; margin-bottom: 5px;"></textarea>
                            <button type="submit" class="btn-refuse" style="width: 100%;">Confirmer Refus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 50px; color: var(--text-secondary);">
                    <i class="fas fa-check-circle" style="font-size: 3rem; color: #10b981; margin-bottom: 20px;"></i>
                    <p>Aucune demande en attente. Tout est à jour !</p>
                </div>
            @endforelse
        </div>

        <div id="calendar" class="hub-content">
            <div id='calendar-wrapper'></div>
        </div>

        <div id="history" class="hub-content">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Ressource</th>
                        <th>Utilisateur</th>
                        <th>Dates</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historyReservations as $res)
                        <tr>
                            <td style="font-weight: 600;">{{ $res->resource->name }}</td>
                            <td>{{ $res->user->name }}</td>
                            <td>{{ $res->start_date->format('d/m') }} - {{ $res->end_date->format('d/m/Y') }}</td>
                            <td><span class="status-pill status-{{ $res->status }}">{{ $res->status }}</span></td>
                            <td>{{ $res->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Aucun historique.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.reservationEvents = @json($events);
    </script>
    @vite(['resources/js/reservations/manager_hub.js'])
@endsection