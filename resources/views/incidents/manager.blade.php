@extends('layouts.app')

@push('styles')
    @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
    <div class="page-header" style="margin-bottom: 1rem;">
        <div>
            <h1 class="page-title">Modération des <span>Alertes Techniques</span></h1>
            <p class="page-subtitle" style="margin-bottom: 1rem;">Suivi des problèmes signalés par les utilisateurs internes
                sur vos ressources.</p>
        </div>
    </div>


    <div class="card" style="overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <tr>
                    <th
                        style="padding: 16px 24px; font-weight: 600; color: #374151; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        Ressource</th>
                    <th
                        style="padding: 16px 24px; font-weight: 600; color: #374151; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        Utilisateur</th>
                    <th
                        style="padding: 16px 24px; font-weight: 600; color: #374151; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        Détails de l'incident</th>
                    <th
                        style="padding: 16px 24px; font-weight: 600; color: #374151; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        Statut</th>
                    <th
                        style="padding: 16px 24px; font-weight: 600; color: #374151; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; text-align: center;">
                        Action</th>
                </tr>
            </thead>
            <tbody style="background-color: white;">
                @forelse($incidents as $incident)
                    <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;"
                        onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='white'">
                        <td style="padding: 20px 24px;">
                            <span
                                style="color: var(--primary); font-weight: 700; font-size: 0.9rem;">{{ $incident->resource->name }}</span>
                        </td>
                        <td style="padding: 20px 24px;">
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 600; color: #111827;">{{ $incident->user->name }}</span>
                                <small style="color: #6b7280;">{{ $incident->user->email }}</small>
                            </div>
                        </td>
                        <td style="padding: 20px 24px;">
                            <strong
                                style="color: #1f2937; display: block; margin-bottom: 4px;">{{ $incident->subject }}</strong>
                            <p style="color: #4b5563; font-size: 0.9rem; line-height: 1.5; margin: 0;">
                                {{ $incident->description }}
                            </p>
                        </td>
                        <td style="padding: 20px 24px;">
                            @php
                                $isOuvert = $incident->status === 'ouvert';
                            @endphp
                            <span class="badge {{ $isOuvert ? 'badge-danger' : 'badge-success' }}">
                                {{ strtoupper($incident->status) }}
                            </span>
                        </td>
                        <td style="padding: 20px 24px; text-align: center;">
                            @if($incident->status === 'ouvert')
                                <form action="{{ route('incidents.resolve', $incident) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-primary"
                                        style="padding: 8px 16px; font-size: 0.85rem; border-radius: 6px; font-family: inherit; font-weight: 600;">
                                        Résoudre
                                    </button>
                                </form>
                            @else
                                <span
                                    style="color: #10b981; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-check-circle"></i> Traité
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 60px; text-align: center; color: #6b7280;">
                            <div style="font-size: 3rem; margin-bottom: 16px; color: #9CA3AF;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p style="font-size: 1.1rem; margin: 0;">Aucun incident technique à modérer pour vos ressources.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection