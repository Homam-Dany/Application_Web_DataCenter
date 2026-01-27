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
        {{-- Bouton d'ajout stylisé --}}
        <a href="{{ route('resources.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter une ressource
        </a>
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
                        <span class="badge {{ $resource->status === 'disponible' ? 'badge-success' : 'badge-warning' }}">
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
                        <form action="{{ route('resources.toggleMaintenance', $resource->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="btn {{ $resource->status === 'maintenance' ? 'btn-success' : 'btn-warning' }} btn-maintenance-toggle">
                                <i
                                    class="fas {{ $resource->status === 'maintenance' ? 'fa-check' : 'fa-wrench' }} icon-margin"></i>
                                {{ $resource->status === 'maintenance' ? 'Remettre en service' : 'Mettre en maintenance' }}
                            </button>
                        </form>

                        <a href="{{ route('resources.edit', $resource->id) }}" class="btn btn-primary btn-edit-resource">
                            <i class="fas fa-cog icon-margin"></i> Modifier
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection