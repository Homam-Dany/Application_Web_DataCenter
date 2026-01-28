@extends('layouts.app')

@push('styles')
    @vite(['resources/css/reservations/history.css'])
@endpush

@section('content')
    <div class="main-content">
        <h1>Historique des Décisions</h1>
        <p class="history-subtitle">Retrouvez ici toutes les demandes que vous avez déjà traitées.</p>

        <div class="card history-card">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Ressource</th>
                        <th>Utilisateur</th>
                        <th>Date Décision</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $res)
                        <tr>
                            <td>{{ $res->resource->name }}</td>
                            <td>{{ $res->user->name }}</td>
                            <td>{{ $res->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge {{ $res->status == 'Approuvée' ? 'badge-approved' : 'badge-rejected' }}">
                                    {{ $res->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection