@extends('layouts.app')

@push('styles')
    @vite(['resources/css/dashboard.css', 'resources/css/resources/manager.css'])
@endpush

@section('content')
    <div class="page-header manager-header manager-view">
        <div>
            <h1 class="page-title">Mon Parc <span>Informatique</span></h1>
            <p class="page-subtitle manager-subtitle">Gérez la disponibilité technique de vos ressources en temps réel.</p>
        </div>
        {{-- Boutons d'action --}}
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('resources.export') }}" class="btn" style="background: #10b981; color: white; border: none;">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('resources.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une ressource
            </a>
        </div>
    </div>

    <div class="resource-list manager-view">
        @foreach($resources as $resource)
            <div class="card">
                <div class="card-body">
                    <!-- HEADER: Name & Status -->
                    <div class="resource-card-header">
                        <div>
                            <h3 class="card-title resource-title">{{ $resource->name }}</h3>
                            <p class="resource-meta">
                                <i class="fas fa-server"></i>
                                {{ $resource->category ?? 'Serveur' }} • {{ $resource->type ?? 'Physique' }}
                            </p>
                        </div>
                        @php
                            $badgeClass = match ($resource->status) {
                                'disponible' => 'badge-success',
                                'maintenance' => 'badge-warning',
                                'désactivée' => 'badge-danger',
                                default => 'badge-info'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ strtoupper($resource->status) }}
                        </span>
                    </div>

                    <!-- SPECS -->
                    <div class="resource-specs">
                        <div class="spec-item">
                            <i class="fas fa-microchip"></i>
                            <span class="spec-value">{{ $resource->cpu }}</span>
                            <span class="spec-label">Cores</span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-memory"></i>
                            <span class="spec-value">{{ $resource->ram }}</span>
                            <span class="spec-label">Go RAM</span>
                        </div>
                    </div>

                    <!-- ACTIONS AREA -->
                    <div class="resource-actions">
                        @if($resource->status === 'désactivée')
                            <form action="{{ route('resources.toggleDeactivate', $resource->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-maintenance-toggle">
                                    <i class="fas fa-power-off icon-margin"></i> Activer la ressource
                                </button>
                            </form>
                        @else
                            <div style="display: flex; gap: 8px; flex-grow: 1;">
                                <form action="{{ route('resources.toggleMaintenance', $resource->id) }}" method="POST"
                                    style="flex-grow: 1;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn {{ $resource->status === 'maintenance' ? 'btn-success' : 'btn-warning' }} btn-maintenance-toggle"
                                        style="width: 100%;">
                                        <i
                                            class="fas {{ $resource->status === 'maintenance' ? 'fa-check' : 'fa-wrench' }} icon-margin"></i>
                                        {{ $resource->status === 'maintenance' ? 'Réparé' : 'Maintenance' }}
                                    </button>
                                </form>

                                <form action="{{ route('resources.toggleDeactivate', $resource->id) }}" method="POST"
                                    style="flex-grow: 1;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger"
                                        style="width: 100%; border-radius: 12px; font-weight: 700;">
                                        <i class="fas fa-ban icon-margin"></i> Bloquer
                                    </button>
                                </form>
                            </div>
                        @endif

                        <a href="{{ route('resources.print_qr', $resource->id) }}" target="_blank" class="btn btn-secondary"
                            title="Imprimer QR Code" style="margin-left: 8px;">
                            <i class="fas fa-qrcode"></i>
                        </a>

                        <a href="{{ route('resources.edit', $resource->id) }}" class="btn btn-primary btn-edit-resource"
                            style="margin-left: 8px;">
                            <i class="fas fa-cog icon-margin"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection