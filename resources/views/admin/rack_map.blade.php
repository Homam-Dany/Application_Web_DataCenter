@extends('layouts.app')

@push('styles')
    @vite(['resources/css/admin/rack_map.css'])
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-th" style="color: var(--primary); margin-right: 10px;"></i> Visual Rack Map
            </h1>
            <p class="page-subtitle">Visualisation physique de la baie principale (42U).</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn" style="background: #e5e7eb; color: #374151;">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="rack-container">
        <!-- Rack 42U -->
        <div class="rack-cabinet-wrapper">
            <!-- Decorative element -->
            <div style="position: absolute; top: 0; left: 10%; width: 80%; height: 100%; pointer-events: none; border-left: 2px dashed #1f2937; border-right: 2px dashed #1f2937; opacity: 0.2;"></div>
            
            <div class="rack-cabinet">
                @for ($u = 42; $u >= 1; $u--)
                    @php 
                        $key = 'U' . $u;
                        $res = $rack[$key] ?? null;
                    @endphp
                    <div class="rack-unit-row">
                        <!-- U Label -->
                        <div class="rack-unit-label">{{ $u }}</div>
                        
                        <!-- Slot -->
                        <div class="rack-slot {{ $res ? ($res->status == 'maintenance' ? 'occupied-maintenance' : 'occupied-active') : '' }}">
                            
                            @if($res)
                                <div class="server-label">
                                    <div class="status-dot {{ $res->status == 'maintenance' ? 'maintenance' : 'active' }}"></div>
                                    <span class="server-name">{{ $res->name }}</span>
                                </div>
                                
                                <!-- Tooltip -->
                                <div class="rack-tooltip">
                                    <strong class="tooltip-title">{{ $res->name }}</strong>
                                    <div class="tooltip-content">
                                        <div class="tooltip-row"><span>ID:</span> <span>#{{ $res->id }}</span></div>
                                        <div class="tooltip-row"><span>Type:</span> <span>{{ $res->type }}</span></div>
                                        <div class="tooltip-row"><span>Status:</span> <span class="{{ $res->status == 'maintenance' ? 'tooltip-status-maint' : 'tooltip-status-active' }}">{{ $res->status }}</span></div>
                                    </div>
                                    <div style="margin-top: 0.5rem; font-size: 10px; color: #9ca3af;">Position: U{{ $u }}</div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Right Rail -->
                        <div class="rack-rails"></div>
                    </div>
                @endfor
            </div>
            
            <div class="rack-label">RACK-01 (Primary)</div>
        </div>
        
        <!-- Legend / Info -->
        <div style="flex: 1;">
            <div class="legend-container">
                <h2 class="legend-title">Légende</h2>
                <div class="legend-items">
                    <div class="legend-item"><div class="legend-dot-success"></div> Actif / Disponible</div>
                    <div class="legend-item"><div class="legend-dot-warning"></div> Maintenance</div>
                    <div class="legend-item"><div class="legend-box-empty"></div> Emplacement Vide</div>
                </div>
                
                <div class="info-box">
                    <p>
                        <strong>Note :</strong> Pour placer un serveur dans la baie, éditez la ressource et assignez-lui une position (ex: "U10").
                        Les serveurs multi-U ne sont pas encore visualisés en pleine hauteur.
                    </p>
                </div>

                <!-- Quick allocation list -->
                <h3 style="font-weight: 700; margin-bottom: 0.75rem; color: var(--text-primary);">Serveurs non-rackés</h3>
                <div style="border: 1px solid var(--border-color); border-radius: 0.5rem; overflow: hidden;">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3">Liste disponible dans la gestion des ressources...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
