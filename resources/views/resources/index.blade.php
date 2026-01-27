@extends('layouts.app')

@push('styles')
    @vite(['resources/css/resources/index.css'])
@endpush

@section('content')
    <div class="page-header resource-catalog-header">
        <div>
            <h1 class="page-title">Catalogue des <span>Ressources</span></h1>
            <p class="page-subtitle resource-catalog-subtitle">Consultez la disponibilité en temps réel du Data Center.</p>
        </div>
    </div>

    <!-- Layout switched to single column for Wide Cards to match previous experience -->
    <div class="resource-list-vertical">
        @foreach($resources as $resource)
            <div class="card">
                <div class="card-body">
                    <!-- HEADER: Name & Status -->
                    <div class="resource-card-header">
                        <div>
                            <h3 class="card-title resource-card-title">{{ $resource->name }}</h3>
                            <p class="resource-card-meta">
                                <i class="fas fa-server"></i>
                                {{ $resource->type }} &bullet; {{ $resource->category }}
                            </p>
                        </div>
                        @php
                            $badgeClass = match ($resource->status) {
                                'disponible' => 'badge-success',
                                'maintenance' => 'badge-warning',
                                'réservé' => 'badge-info',
                                default => 'badge-danger'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ ucfirst($resource->status) }}
                        </span>
                    </div>

                    <!-- SPECS -->
                    <div class="resource-card-specs">
                        <div class="spec-item">
                            <i class="fas fa-microchip"></i>
                            <span class="spec-value">{{ $resource->cpu }}</span> <span class="spec-label">Cores</span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-memory"></i>
                            <span class="spec-value">{{ $resource->ram }}</span> <span class="spec-label">Go RAM</span>
                        </div>
                    </div>

                    <!-- ACTIONS AREA -->
                    <div class="resource-actions-area">
                        <!-- RESERVATION ACTION -->
                        @auth
                            @if(auth()->user()->role === 'user')
                                @if($resource->status === 'disponible')
                                    <a href="{{ route('reservations.create', ['resource' => $resource->id]) }}"
                                        class="btn btn-primary btn-full-width">
                                        <i class="fas fa-calendar-plus"></i> Réserver
                                    </a>
                                @else
                                    <button class="btn btn-unavailable" disabled>
                                        <i class="fas fa-ban"></i> Indisponible
                                    </button>
                                @endif
                            @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'responsable')
                                <div class="admin-action-container">
                                    <a href="{{ route('resources.manager') }}" class="btn btn-success btn-full-width">
                                        <i class="fas fa-cog"></i> Gérer
                                    </a>
                                    <p class="resource-card-meta" style="margin-top: 5px; font-size: 0.8rem;">Mode consultation</p>
                                </div>
                            @endif
                        @else
                            <div class="guest-login-notice">
                                <a href="{{ route('login') }}" class="guest-login-link">
                                    Connectez-vous pour réserver
                                </a>
                            </div>
                        @endauth

                        <!-- INCIDENT REPORTING FORM -->
                        @auth
                            @if(auth()->user()->role === 'user')
                                <div class="incident-report-container">
                                    <h4 class="incident-report-title">
                                        <i class="fas fa-exclamation-triangle"></i> Signaler un incident
                                    </h4>
                                    <form action="{{ route('incidents.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="resource_id" value="{{ $resource->id }}">

                                        <div class="incident-form-group">
                                            <input type="text" name="subject" placeholder="Sujet de l'incident" required
                                                class="incident-input">
                                        </div>

                                        <div class="incident-form-group-last">
                                            <textarea name="description" placeholder="Décrivez le problème en détail..." required
                                                class="incident-textarea"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-incident-submit">
                                            <i class="fas fa-paper-plane"></i> Envoyer le signalement
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection