@extends('layouts.app')

@section('title', 'Tableau de bord')

@push('styles')
    @vite(['resources/css/dashboard.css'])
@endpush

@section('content')
<!-- Animated Cyber Background -->
<div class="cyber-background"></div>

<div class="pd-container" style="position: relative; z-index: 10;">
    {{-- HEADER --}}
    <div class="pd-header" style="border-bottom: 2px solid var(--cyber-panel-border); padding-bottom: 1rem;">
        <div>
            <h1 class="pd-title">USER.TERMINAL</h1>
            <p class="pd-subtitle">>> Welcome to the Global Data Grid.</p>
        </div>
        <div>
            <a href="{{ route('resources.index') }}" class="cyber-btn" style="box-shadow: 0 0 20px rgba(0, 240, 255, 0.4);">
                <i class="fas fa-search" style="margin-right: 8px;"></i> [BROWSE CATALOG]
            </a>
        </div>
    </div>

    {{-- METRICS ROW --}}
    <div class="pd-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 2rem;">
        {{-- 1. Occupation --}}
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-primary); margin: 0 0 10px 0; font-size: 1rem;">GRID.LOAD</h4>
                <div style="font-size: 2rem; font-weight: 900; text-shadow: var(--cyber-glow-primary);">{{ $occupancyRate }}%</div>
            </div>
            <div class="cyber-radial" style="--val: {{ $occupancyRate }};">
                <i class="fas fa-chart-pie" style="color: var(--cyber-primary); font-size: 1.5rem; position: relative; z-index: 10;"></i>
            </div>
        </div>

        {{-- 2. Total --}}
        @php
            $resTitle = isset($myResourcesCount) ? 'NODES.OWNED' : 'NODES.TOTAL';
            $resValue = $myResourcesCount ?? $totalResources;
        @endphp
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-text); margin: 0 0 10px 0; font-size: 1rem;">{{ $resTitle }}</h4>
                <div style="font-size: 2rem; font-weight: 900;">{{ $resValue }}</div>
            </div>
            <div class="cyber-radial" style="--val: 100; background: conic-gradient(rgba(255,255,255,0.2) 100%, transparent 0);">
                <i class="fas fa-server" style="color: var(--cyber-text); font-size: 1.5rem; position: relative; z-index: 10;"></i>
            </div>
        </div>

        {{-- 3. Disponible --}}
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-success); margin: 0 0 10px 0; font-size: 1rem;">NODES.READY</h4>
                <div style="font-size: 2rem; font-weight: 900; color: var(--cyber-success); text-shadow: var(--cyber-glow-success);">{{ $availableCount }}</div>
            </div>
            <div class="cyber-radial success" style="--val: 100;">
                <i class="fas fa-check-circle" style="color: var(--cyber-success); font-size: 1.5rem; position: relative; z-index: 10;"></i>
            </div>
        </div>

        {{-- 4. Maintenance / Bloqué --}}
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between; border-color: rgba(255,184,0,0.5);">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-warning); margin: 0 0 10px 0; font-size: 1rem;">NODES.LOCKED</h4>
                <div style="font-size: 2rem; font-weight: 900; color: var(--cyber-warning);">{{ $maintenanceCount + $blockedCount }}</div>
            </div>
            <div class="cyber-radial warning" style="--val: 100;">
                <i class="fas fa-lock" style="color: var(--cyber-warning); font-size: 1.5rem; position: relative; z-index: 10;"></i>
            </div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="pd-grid pd-grid-main">
        
        {{-- LEFT COLUMN: Charts & Info --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            {{-- Welcome Banner --}}
            <div class="pd-card" style="background: rgba(0,240,255,0.1); border: 1px solid var(--cyber-primary); box-shadow: var(--cyber-glow-primary);">
                <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 10px 0; color: var(--cyber-primary); text-transform: uppercase;"><i class="fas fa-globe-americas"></i> Access Granted.</h3>
                <p style="font-family: monospace; line-height: 1.6; color: var(--cyber-text);">
                    > Connection established to Mainframe.<br>
                    > Active Reservations detected: <span style="color: var(--cyber-primary); font-weight: bold; font-size: 1.2rem;">{{ $myActiveReservations ?? 0 }}</span><br>
                    > You are clear to deploy new instances or monitor current operations.
                </p>
                <div style="margin-top: 20px;">
                    <a href="{{ route('reservations.index') }}" class="cyber-btn cyber-btn-secondary">
                        [MY_RESERVATIONS]
                    </a>
                </div>
            </div>

            @if(auth()->user()->role === 'user' && isset($userReservationsByStatus))
            <div class="pd-card">
                <h3 class="pd-card-title"><i class="fas fa-chart-pie"></i> REQ.STATUS_MATRIX</h3>
                <div class="pd-chart-container" style="height: 250px;">
                    <canvas id="userStatusChart" data-labels="{{ json_encode($userReservationsByStatus->pluck('status')) }}" data-values="{{ json_encode($userReservationsByStatus->pluck('total')) }}"></canvas>
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT COLUMN: Recent Activity --}}
        <div class="pd-card" style="display: flex; flex-direction: column; background: var(--cyber-panel);">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(59,130,246,0.2); padding-bottom: 10px; margin-bottom: 15px;">
                <h3 class="pd-card-title" style="margin: 0;"><i class="fas fa-history"></i> REQ.HISTORY</h3>
            </div>
            
            @if(isset($recentReservations) && $recentReservations->isEmpty())
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--cyber-text-muted); min-height: 200px; font-family: monospace;">
                    > NO HISTORY FOUND.
                </div>
            @elseif(isset($recentReservations))
                <ul class="cyber-list" style="flex: 1; overflow-y: auto;">
                    @foreach($recentReservations as $res)
                        @php
                            $statusColor = match($res->status) {
                                'Approuvée' => 'var(--cyber-success)',
                                'Rejetée' => 'var(--cyber-accent)',
                                default => 'var(--cyber-warning)'
                            };
                        @endphp
                        <li>
                            <div class="cyber-list-time">[{{ $res->start_date->format('d/m') }}]</div>
                            <div class="cyber-list-content">
                                <div class="cyber-list-action">{{ $res->resource->name ?? 'UNKNOWN_NODE' }}</div>
                                <div class="cyber-list-detail">TO: {{ $res->end_date->format('d/m/y') }}</div>
                            </div>
                            <div style="font-family: monospace; font-size: 0.8rem; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}; padding: 2px 8px;">
                                {{ strtoupper($res->status) }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
    @if(auth()->user()->role === 'user')
        @vite(['resources/js/dashboard.js'])
    @endif
    <script>
        if(typeof Chart !== 'undefined') {
            Chart.defaults.color = '#6b7599';
            Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
        }
    </script>
@endpush