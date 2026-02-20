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

    <div class="resource-catalog-controls">
        <div class="filter-bar-intelligent">
            <!-- Search -->
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="resourceSearch" placeholder="Nom, modèle, type..." class="search-bar-compact"
                    autocomplete="off">
            </div>

            <div class="filter-divider"></div>

            <!-- Type Select -->
            <div class="filter-select-wrapper">
                <label>Type</label>
                <select id="typeFilter" class="filter-select-custom">
                    <option value="all">Tout</option>
                    <option value="serveur">Serveur</option>
                    <option value="switch">Switch</option>
                    <option value="physique">Physique</option>
                    <option value="virtuelle">Virtuelle</option>
                </select>
            </div>

            <div class="filter-divider"></div>

            <!-- Status Select -->
            <div class="filter-select-wrapper">
                <label>État</label>
                <select id="statusFilter" class="filter-select-custom">
                    <option value="all">Tout</option>
                    <option value="disponible">Disponible</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="désactivée">Bloqué</option>
                </select>
            </div>

            <div class="filter-divider"></div>

            <!-- Counter -->
            <div id="resultsCounterBadge" class="results-badge">
                {{ $resources->count() }} ressources
            </div>
        </div>
    </div>

    <div class="resource-grid" id="resourceContainer">
        @foreach($resources as $resource)
            <div class="resource-card-wrapper" data-name="{{ strtolower($resource->name) }}"
                data-type="{{ strtolower($resource->type) }}" data-category="{{ strtolower($resource->category) }}"
                data-status="{{ strtolower($resource->status) }}">
                <div class="premium-resource-card">
                    <!-- Card Top Area -->
                    <div class="card-top">
                        <div class="resource-icon-box">
                            <i
                                class="fas {{ $resource->type === 'Serveur' ? 'fa-server' : ($resource->type === 'Switch' ? 'fa-network-wired' : 'fa-microchip') }}"></i>
                        </div>
                        @php
                            $statusLabel = match ($resource->status) {
                                'disponible' => 'Disponible',
                                'maintenance' => 'En Maintenance',
                                'réservé' => 'Réservé',
                                'désactivée' => 'Bloqué',
                                default => ucfirst($resource->status)
                            };
                            $statusState = match ($resource->status) {
                                'disponible' => 'state-success',
                                'maintenance' => 'state-warning',
                                'réservé' => 'state-info',
                                'désactivée' => 'state-danger',
                                default => 'state-danger'
                            };
                        @endphp
                        <div class="status-floating-pill {{ $statusState }}">
                            <span class="status-dot"></span> {{ $statusLabel }}
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-content">
                        <div class="category-tag">{{ $resource->category }}</div>
                        <h3 class="resource-title">{{ $resource->name }}</h3>
                        <p class="resource-type-meta">{{ $resource->type }}</p>

                        <div class="resource-specs-grid">
                            <div class="spec-node">
                                <i class="fas fa-microchip"></i>
                                <span class="val">{{ $resource->cpu }}</span>
                                <span class="lab">Cores</span>
                            </div>
                            <div class="spec-node">
                                <i class="fas fa-memory"></i>
                                <span class="val">{{ $resource->ram }}</span>
                                <span class="lab">Go RAM</span>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div class="card-actions-wrapper">
                            @auth
                                @if(auth()->user()->role === 'user')
                                    @if($resource->status === 'disponible')
                                        <a href="{{ route('reservations.create', ['resource' => $resource->id]) }}"
                                            class="btn-premium btn-reserve">
                                            <i class="fas fa-calendar-plus"></i> Réserver
                                        </a>
                                    @else
                                        <button class="btn-premium btn-locked" disabled>
                                            <i class="fas fa-lock"></i> Indisponible
                                        </button>
                                    @endif
                                @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'responsable')
                                    <a href="{{ route('resources.manager') }}" class="btn-premium btn-manage">
                                        <i class="fas fa-tools"></i> Gérer
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-premium btn-guest">
                                    Connexion
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('resourceSearch');
                const typeFilter = document.getElementById('typeFilter');
                const statusFilter = document.getElementById('statusFilter');
                const resultsCounterBadge = document.getElementById('resultsCounterBadge');
                const resourceCards = document.querySelectorAll('.resource-card-wrapper');
                const container = document.getElementById('resourceContainer');

                function updateFilters() {
                    const searchTerm = searchInput.value.toLowerCase().trim();
                    const activeType = typeFilter.value;
                    const activeStatus = statusFilter.value;
                    let visibleCount = 0;

                    resourceCards.forEach(card => {
                        const name = card.dataset.name;
                        const type = card.dataset.type;
                        const category = card.dataset.category;
                        const status = card.dataset.status;

                        // 1. Text Search Match
                        const matchesText = name.includes(searchTerm) || type.includes(searchTerm) || category.includes(searchTerm);

                        // 2. Type Facet Match
                        let matchesType = true;
                        if (activeType === 'serveur') matchesType = type.includes('serveur');
                        else if (activeType === 'switch') matchesType = type.includes('switch');
                        else if (activeType === 'physique') matchesType = category.toLowerCase().includes('physique');
                        else if (activeType === 'virtuelle') matchesType = category.toLowerCase().includes('virtuelle');

                        // 3. Status Facet Match
                        let matchesStatus = true;
                        if (activeStatus !== 'all') {
                            matchesStatus = (status === activeStatus);
                        }

                        if (matchesText && matchesType && matchesStatus) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Update results counter
                    resultsCounterBadge.innerText = `${visibleCount} ressource${visibleCount !== 1 ? 's' : ''}`;

                    // Handle no results
                    let noResultMsg = document.getElementById('noResultsMessage');
                    if (visibleCount === 0) {
                        if (!noResultMsg) {
                            noResultMsg = document.createElement('div');
                            noResultMsg.id = 'noResultsMessage';
                            noResultMsg.className = 'no-results-message';
                            noResultMsg.innerHTML = '<i class="fas fa-search-minus"></i> Aucune ressource ne correspond.';
                            container.after(noResultMsg);
                        }
                    } else if (noResultMsg) {
                        noResultMsg.remove();
                    }
                }

                // Listeners
                searchInput.addEventListener('input', updateFilters);
                typeFilter.addEventListener('change', updateFilters);
                statusFilter.addEventListener('change', updateFilters);
            });
        </script>
    @endpush
@endsection