@extends('layouts.app')

@push('styles')
    @vite(['resources/css/incidents/manager.css'])
@endpush

@push('scripts')
    @vite(['resources/js/incidents/manager.js'])
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Modération des <span>Alertes Techniques</span></h1>
            <p class="page-subtitle">Suivi des problèmes signalés par les utilisateurs internes sur vos ressources.</p>
        </div>
    </div>


    {{-- INCIDENTS EN COURS (ACTIFS) --}}
    <div class="card card-incidents">
        <table class="incidents-table">
            <thead>
                <tr>
                    <th>Ressource</th>
                    <th>Utilisateur</th>
                    <th>Détails de l'incident</th>
                    <th>Statut</th>
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($openIncidents as $incident)
                    <tr class="incident-row">
                        <td>
                            <span class="resource-name">{{ $incident->resource->name }}</span>
                        </td>
                        <td>
                            <div class="user-info">
                                <span class="user-name">{{ $incident->user->name }}</span>
                                <small class="user-email">{{ $incident->user->email }}</small>
                            </div>
                        </td>
                        <td>
                            <strong class="incident-subject">{{ $incident->subject }}</strong>
                            <p class="incident-description">{{ $incident->description }}</p>
                        </td>
                        <td>
                            <span class="badge badge-danger">
                                {{ strtoupper($incident->status) }}
                            </span>
                        </td>
                        <td class="action-cell">
                            <form action="{{ route('incidents.resolve', $incident) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-primary btn-resolve">
                                    Résoudre
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="empty-text">Aucun incident technique en cours pour vos ressources.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- SECTION HISTORIQUE (TOGGLE) --}}
    @if($resolvedIncidents->count() > 0)
        <div class="history-controls">
            <button id="toggle-history-btn" class="btn-toggle-history">
                <i class="fas fa-history"></i> Voir l'historique
            </button>
        </div>

        <div id="history-section" class="history-section">
            <h2 class="history-title">Historique des Incidents Résolus</h2>

            <div class="card card-incidents">
                <table class="incidents-table">
                    <thead>
                        <tr>
                            <th>Ressource</th>
                            <th>Utilisateur</th>
                            <th>Détails de l'incident</th>
                            <th>Statut</th>
                            <th style="text-align: center;">État</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resolvedIncidents as $incident)
                            <tr class="incident-row">
                                <td>
                                    <span class="resource-name">{{ $incident->resource->name }}</span>
                                </td>
                                <td>
                                    <div class="user-info">
                                        <span class="user-name">{{ $incident->user->name }}</span>
                                        <small class="user-email">{{ $incident->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <strong class="incident-subject">{{ $incident->subject }}</strong>
                                    <p class="incident-description">{{ $incident->description }}</p>
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        {{ strtoupper($incident->status) }}
                                    </span>
                                </td>
                                <td class="action-cell">
                                    <span class="status-resolved">
                                        <i class="fas fa-check-circle"></i> Traité
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection