@extends('layouts.app')

@section('content')
    {{-- En-tête de la page --}}
    <div class="page-header" style="margin-bottom: 2rem;">
        <div>
            <h1 class="page-title">Historique des <span>Notifications</span></h1>
            <p class="page-subtitle">Retrouvez ici toutes vos alertes récentes et passées.</p>
        </div>

        <div style="display: flex; gap: 1rem;">
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-double"></i> Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
    </div>

    @php
        $unreadNotifications = $notifications->where('read_at', null);
        $readNotifications = $notifications->where('read_at', '!=', null);
    @endphp

    {{-- 1. Notifications NON LUES --}}
    <div id="unread-section">
        @forelse($unreadNotifications as $notification)
            @php
                $status = $notification->data['status'] ?? 'Info';
                $borderColor = match (true) {
                    ($status === 'Refusée') => '#ef4444',
                    ($status === 'Approuvée' || $status === 'resolu') => '#10b981',
                    (isset($notification->data['incident_id']) || $status === 'ouvert' || $status === 'expire_soon') => '#f59e0b',
                    default => '#3b82f6'
                };
            @endphp

            <div class="card notification-card notification-unread"
                style="margin-bottom: 1.5rem; border-left: 4px solid {{ $borderColor }}; transition: all 0.3s ease;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div style="flex-grow: 1;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <span class="badge badge-info" style="font-size: 0.65rem; padding: 2px 8px;">Nouveau</span>
                            <span
                                style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">
                                {{ $status }}
                            </span>
                        </div>
                        <p
                            style="margin: 0; font-weight: 700; color: var(--text-primary); font-size: 1.05rem; margin-bottom: 8px;">
                            {{ $notification->data['message'] ?? 'Mise à jour système' }}
                        </p>

                        @include('notifications.partials.content', ['notification' => $notification, 'status' => $status])

                        <div style="display: flex; align-items: center; gap: 6px; margin-top: 12px;">
                            <i class="far fa-clock" style="color: #94a3b8; font-size: 0.85rem;"></i>
                            <small style="color: #64748b; font-weight: 500;">
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>

                @include('notifications.partials.actions', ['notification' => $notification])
            </div>
        @empty
            @if($readNotifications->isEmpty())
                <div class="card"
                    style="text-align: center; padding: 4rem 2rem; border: 2px dashed #e2e8f0; background: transparent; box-shadow: none;">
                    <div
                        style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="far fa-bell" style="font-size: 2rem; color: #94a3b8;"></i>
                    </div>
                    <h3 style="color: #64748b; margin: 0 0 10px; font-weight: 600;">Tout est calme ici</h3>
                    <p style="color: #94a3b8; margin: 0;">Aucune nouvelle notification pour le moment.</p>
                </div>
            @endif
        @endforelse
    </div>

    {{-- 2. BOUTON TOGGLE HISTORIQUE --}}
    @if($readNotifications->isNotEmpty())
        <div style="text-align: center; margin: 2rem 0;">
            <button id="toggle-history-btn" class="btn"
                style="background: var(--bg-card); color: var(--primary); border: 1px solid var(--border-color); padding: 10px 25px; border-radius: 30px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-history" style="margin-right: 8px;"></i>
                <span id="toggle-text">Voir l'historique ({{ $readNotifications->count() }})</span>
            </button>
        </div>

        {{-- 3. Notifications LUES (Masquées par défaut) --}}
        <div id="history-section" style="display: none; opacity: 0; transition: opacity 0.4s ease;">
            <h2
                style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px; text-align: center;">
                Anciennes notifications
            </h2>

            @foreach($readNotifications as $notification)
                @php
                    $status = $notification->data['status'] ?? 'Info';
                    $borderColor = match (true) {
                        ($status === 'Refusée') => '#ef4444',
                        ($status === 'Approuvée' || $status === 'resolu') => '#10b981',
                        (isset($notification->data['incident_id']) || $status === 'ouvert') => '#f59e0b',
                        default => '#3b82f6'
                    };
                @endphp

                <div class="card notification-card notification-read"
                    style="margin-bottom: 1.5rem; border-left: 4px solid {{ $borderColor }}; opacity: 0.7; transform: scale(0.98);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="flex-grow: 1;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <span
                                    style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">
                                    {{ $status }}
                                </span>
                            </div>
                            <p
                                style="margin: 0; font-weight: 700; color: var(--text-primary); font-size: 1.05rem; margin-bottom: 8px;">
                                {{ $notification->data['message'] ?? 'Mise à jour système' }}
                            </p>

                            @include('notifications.partials.content', ['notification' => $notification, 'status' => $status])

                            <div style="display: flex; align-items: center; gap: 6px; margin-top: 12px;">
                                <i class="far fa-clock" style="color: #94a3b8; font-size: 0.85rem;"></i>
                                <small style="color: #64748b; font-weight: 500;">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif


    @push('scripts')
        @vite(['resources/js/notifications/index.js'])
    @endpush
@endsection