@extends('layouts.app')

@push('styles')
    @vite(['resources/css/admin/rack_map.css']) {{-- Reusing admin styles --}}
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-th" style="color: var(--primary); margin-right: 10px;"></i> Ma Baie (Rack Map)
            </h1>
            <p class="page-subtitle">Visualisation de vos équipements dans la baie.</p>
        </div>
        <a href="{{ route('engineer.dashboard') }}" class="btn" style="background: #e5e7eb; color: #374151;">
            <i class="fas fa-arrow-left"></i> Retour Dashboard
        </a>
    </div>

    <div class="rack-container">
        <!-- Rack 42U -->
        <div class="rack-cabinet-wrapper">
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
                            @elseif(false) 
                                {{-- Placeholder for slots occupied by OTHERS (could query DB for generic 'occupied' status if we wanted to be fancy, but keep simple for now) --}}
                                <div style="width: 100%; height: 100%; background: repeating-linear-gradient(45deg, #eee, #eee 10px, #f5f5f5 10px, #f5f5f5 20px);"></div>
                            @endif
                        </div>
                        
                        <!-- Right Rail -->
                        <div class="rack-rails"></div>
                    </div>
                @endfor
            </div>
            
            <div class="rack-label">RACK-01 (Vue Personnelle)</div>
        </div>
        
        <!-- Legend / Info -->
        <div style="flex: 1;">
            <div class="legend-container">
                <h2 class="legend-title">Légende</h2>
                <div class="legend-items">
                    <div class="legend-item"><div class="legend-dot-success"></div> Vos Serveurs Actifs</div>
                    <div class="legend-item"><div class="legend-dot-warning"></div> Vos Serveurs en Maintenance</div>
                    <div class="legend-item"><div class="legend-box-empty"></div> Emplacement Vide / Autrui</div>
                </div>
                
                <div class="info-box">
                    <p>
                        <strong>Note :</strong> Seules vos ressources avec une position définie (ex: U10) apparaissent ici.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
