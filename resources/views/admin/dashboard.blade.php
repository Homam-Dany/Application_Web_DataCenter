@extends('layouts.app')

@push('styles')
    @vite(['resources/css/dashboard.css', 'resources/css/admin/dashboard.css'])
    <!-- Rely on resources/css/premium-dashboards.css which is included in app.blade.php -->
@endpush

@section('content')
<!-- Animated Cyber Background -->
<div class="cyber-background"></div>

<div class="pd-container" style="position: relative; z-index: 10;">
    {{-- HEADER --}}
    <div class="pd-header" style="border-bottom: 2px solid var(--cyber-panel-border); padding-bottom: 1rem;">
        <div>
            <h1 class="pd-title">SYSTEM.NOC_ADMIN</h1>
            <p class="pd-subtitle">>> Accessing root supervision matrix...</p>
        </div>

        <div style="display: flex; gap: 15px; align-items: center;">
            <a href="{{ route('reservations.manager') }}" class="cyber-btn">
                <i class="fas fa-inbox" style="margin-right: 8px;"></i> [Demandes]
            </a>

            <a href="{{ route('reports.monthly') }}" class="cyber-btn cyber-btn-secondary">
                <i class="fas fa-file-pdf" style="margin-right: 8px;"></i> [Rapport]
            </a>
            
            <div style="background: rgba(255, 0, 60, 0.1); color: var(--cyber-accent); padding: 8px 15px; border: 1px solid var(--cyber-accent); font-family: monospace; font-weight: bold; letter-spacing: 2px; box-shadow: var(--cyber-glow-danger);">
                <i class="fas fa-shield-alt"></i> SEC_LEVEL: ALPHA
            </div>
        </div>
    </div>

    {{-- TOP METRICS ROW --}}
    <div class="pd-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 2rem;">
        {{-- 1. Occupation Radial --}}
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-primary); margin: 0 0 10px 0; font-size: 1rem;">SYS.LOAD</h4>
                <div style="font-size: 2rem; font-weight: 900; text-shadow: var(--cyber-glow-primary);">{{ $stats['occupancy_rate'] }}%</div>
            </div>
            <div class="cyber-radial" style="--val: {{ $stats['occupancy_rate'] }};">
                <i class="fas fa-microchip" style="color: var(--cyber-primary); font-size: 1.5rem; position: relative; z-index: 10;"></i>
            </div>
        </div>

        {{-- 2. Disponible --}}
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-success); margin: 0 0 10px 0; font-size: 1rem;">SYS.AVAILABLE</h4>
                <div style="font-size: 2rem; font-weight: 900; color: var(--cyber-success); text-shadow: var(--cyber-glow-success);">{{ $availableCount }}</div>
            </div>
            <div class="cyber-radial success" style="--val: {{ $availableCount > 0 ? 100 : 0 }};">
                <i class="fas fa-check" style="color: var(--cyber-success); font-size: 1.5rem; position: relative; z-index: 10;"></i>
            </div>
        </div>

        {{-- 3. Maintenance --}}
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-warning); margin: 0 0 10px 0; font-size: 1rem;">SYS.MAINT</h4>
                <div style="font-size: 2rem; font-weight: 900; color: var(--cyber-warning);">{{ $maintenanceCount }}</div>
            </div>
            <div class="cyber-radial warning" style="--val: {{ $stats['total_resources'] > 0 ? ($maintenanceCount / $stats['total_resources'] * 100) : 0 }};">
                <i class="fas fa-tools" style="color: var(--cyber-warning); font-size: 1.5rem; position: relative; z-index: 10;"></i>
            </div>
        </div>

        {{-- 4. Alert --}}
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between; border-color: rgba(255,0,60,0.5);">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-accent); margin: 0 0 10px 0; font-size: 1rem;">SYS.ALERT</h4>
                <div style="font-size: 2rem; font-weight: 900; color: var(--cyber-accent); text-shadow: var(--cyber-glow-danger);">{{ $blockedCount }}</div>
            </div>
            <div class="cyber-radial danger" style="--val: {{ $stats['total_resources'] > 0 ? ($blockedCount / $stats['total_resources'] * 100) : 0 }};">
                <i class="fas fa-exclamation-triangle" style="color: var(--cyber-accent); font-size: 1.5rem; position: relative; z-index: 10; animation: cyberAlert 2s infinite;"></i>
            </div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="pd-grid pd-grid-main">
        
        {{-- LEFT COLUMN --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            {{-- Charts Row --}}
            <div class="pd-grid" style="grid-template-columns: 1fr 1fr;">
                <div class="pd-card">
                    <h3 class="pd-card-title"><i class="fas fa-wave-square"></i> NETWORK.TRAFFIC</h3>
                    <div class="pd-chart-container" style="height: 200px;">
                        <canvas id="occupancyChart" data-active="{{ $stats['active_reservations'] }}" data-total="{{ $stats['total_resources'] }}"></canvas>
                    </div>
                </div>
                <div class="pd-card">
                    <h3 class="pd-card-title"><i class="fas fa-radiation"></i> INCIDENT.LOG</h3>
                    <div class="pd-chart-container" style="height: 200px;">
                        <canvas id="incidentsChart" data-labels="{{ json_encode($incidentsByStatus->pluck('status')) }}" data-values="{{ json_encode($incidentsByStatus->pluck('total')) }}"></canvas>
                    </div>
                </div>
            </div>
            
            {{-- Inventory Block --}}
            <div class="pd-card" style="border-top: 3px solid var(--cyber-secondary);">
                <h3 class="pd-card-title" style="color: var(--cyber-secondary);"><i class="fas fa-database"></i> HARDWARE.MATRIX</h3>
                <div class="pd-chart-container" style="height: 250px;">
                    <canvas id="inventoryChart" data-labels="{{ json_encode($resourcesByType->pluck('type')) }}" data-values="{{ json_encode($resourcesByType->pluck('total')) }}"></canvas>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: TERMINAL LOGS --}}
        <div class="pd-card" style="display: flex; flex-direction: column; background: var(--cyber-panel);">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(59,130,246,0.2); padding-bottom: 10px; margin-bottom: 15px;">
                <h3 style="margin: 0; font-family: monospace; font-size: 1rem; color: var(--cyber-primary);">
                    > tail -f /var/log/syslog
                </h3>
                <a href="{{ route('admin.logs') }}" style="color: var(--cyber-primary); font-family: monospace; text-decoration: none;">[EXPAND]</a>
            </div>
            
            <ul class="cyber-list" style="flex: 1; overflow-y: auto; max-height: 550px;">
                @foreach($recentLogs as $log)
                    @php
                        $color = match ($log->action) {
                            'Signalement' => 'var(--cyber-warning)',
                            'Incident Résolu' => 'var(--cyber-success)',
                            'Demande Réservation' => 'var(--cyber-primary)',
                            default => 'var(--cyber-text)'
                        };
                    @endphp
                    <li>
                        <div class="cyber-list-time">[{{ $log->created_at->format('H:i:s') }}]</div>
                        <div class="cyber-list-content">
                            <div class="cyber-list-action" style="color: {{ $color }};">{{ $log->action }}</div>
                            <div class="cyber-list-detail">USR: {{ $log->user->name ?? 'ROOT' }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/admin/dashboard.js'])
    <!-- Chart.js Dark Mode Overrides -->
    <script>
        // We will override chart colors in JS if needed, but Chart.js transparent background works well.
        Chart.defaults.color = '#6b7599';
        Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
    </script>
@endpush