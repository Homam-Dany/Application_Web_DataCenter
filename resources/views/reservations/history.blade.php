@extends('layouts.app')

@section('content')
<div class="main-content">
    <h1>Historique des Décisions</h1>
    <p style="color: var(--text-muted);">Retrouvez ici toutes les demandes que vous avez déjà traitées.</p>

    <div class="card" style="margin-top: 2rem;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Ressource</th>
                    <th style="padding: 1rem;">Utilisateur</th>
                    <th style="padding: 1rem;">Date Décision</th>
                    <th style="padding: 1rem;">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $res)
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 1rem;">{{ $res->resource->name }}</td>
                    <td style="padding: 1rem;">{{ $res->user->name }}</td>
                    <td style="padding: 1rem;">{{ $res->updated_at->format('d/m/Y H:i') }}</td>
                    <td style="padding: 1rem;">
                        <span class="badge" style="background: {{ $res->status == 'Approuvée' ? '#10b981' : '#ef4444' }}; color: white; padding: 4px 8px; border-radius: 4px;">
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