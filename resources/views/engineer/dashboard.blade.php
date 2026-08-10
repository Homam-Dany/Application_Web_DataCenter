@extends('layouts.app')

@push('styles')
    @vite(['resources/css/dashboard.css', 'resources/css/engineer/dashboard.css'])
@endpush

@section('content')
<!-- Animated Cyber Background -->
<div class="cyber-background"></div>

<div class="pd-container" style="position: relative; z-index: 10;">
    {{-- HEADER --}}
    <div class="pd-header" style="border-bottom: 2px solid var(--cyber-panel-border); padding-bottom: 1rem;">
        <div>
            <h1 class="pd-title">NODE.ENGINEER</h1>
            <p class="pd-subtitle">>> Initializing hardware maintenance protocols...</p>
        </div>
        <div style="display: flex; gap: 15px; align-items: center;">
            <a href="{{ route('reservations.manager') }}" class="cyber-btn cyber-btn-secondary">
                <i class="fas fa-inbox" style="margin-right: 8px;"></i> [Demandes]
            </a>
            <a href="{{ route('resources.create') }}" class="cyber-btn">
                <i class="fas fa-plus" style="margin-right: 8px;"></i> [Deploy Node]
            </a>
        </div>
    </div>

    {{-- METRICS ROW --}}
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

        {{-- 2. Managed --}}
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-text); margin: 0 0 10px 0; font-size: 1rem;">NODES.TOTAL</h4>
                <div style="font-size: 2rem; font-weight: 900;">{{ $stats['total_managed'] }}</div>
            </div>
            <div class="cyber-radial" style="--val: 100; background: conic-gradient(rgba(255,255,255,0.2) 100%, transparent 0);">
                <i class="fas fa-server" style="color: var(--cyber-text); font-size: 1.5rem; position: relative; z-index: 10;"></i>
            </div>
        </div>

        {{-- 3. Maintenance --}}
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-warning); margin: 0 0 10px 0; font-size: 1rem;">SYS.MAINT</h4>
                <div style="font-size: 2rem; font-weight: 900; color: var(--cyber-warning);">{{ $stats['maintenance_mode'] }}</div>
            </div>
            <div class="cyber-radial warning" style="--val: {{ $stats['total_managed'] > 0 ? ($stats['maintenance_mode'] / $stats['total_managed'] * 100) : 0 }};">
                <i class="fas fa-tools" style="color: var(--cyber-warning); font-size: 1.5rem; position: relative; z-index: 10;"></i>
            </div>
        </div>

        {{-- 4. Alert --}}
        <div class="pd-card" style="display: flex; align-items: center; justify-content: space-between; border-color: rgba(255,0,60,0.5);">
            <div>
                <h4 style="font-family: monospace; color: var(--cyber-accent); margin: 0 0 10px 0; font-size: 1rem;">SYS.ALERT</h4>
                <div style="font-size: 2rem; font-weight: 900; color: var(--cyber-accent); text-shadow: var(--cyber-glow-danger);">{{ $stats['blocked_count'] }}</div>
            </div>
            <div class="cyber-radial danger" style="--val: {{ $stats['total_managed'] > 0 ? ($stats['blocked_count'] / $stats['total_managed'] * 100) : 0 }};">
                <i class="fas fa-radiation" style="color: var(--cyber-accent); font-size: 1.5rem; position: relative; z-index: 10; animation: cyberAlert 2s infinite;"></i>
            </div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="pd-grid pd-grid-main">
        {{-- LEFT: VISUAL SERVER GRID (MATRIX) --}}
        <div class="pd-card" style="border-top: 3px solid var(--cyber-primary);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                <h3 class="pd-card-title" style="margin: 0; border: none; padding: 0;"><i class="fas fa-th"></i> HARDWARE.MATRIX.LIVE</h3>
                <div style="font-family: monospace; font-size: 0.8rem; display: flex; gap: 15px;">
                    <span style="color: var(--cyber-success);"><i class="fas fa-circle"></i> ONLINE</span>
                    <span style="color: var(--cyber-warning);"><i class="fas fa-circle"></i> MAINT</span>
                    <span style="color: var(--cyber-accent);"><i class="fas fa-circle" style="animation: cyberPulse 1s infinite;"></i> ALERT</span>
                </div>
            </div>

            <div class="cyber-server-grid">
                @forelse($resources as $res)
                    @php
                        $statusClass = 'status-ok';
                        $icon = 'fa-server';
                        if ($res->status === 'maintenance') {
                            $statusClass = 'status-maint';
                            $icon = 'fa-tools';
                        } elseif ($res->incidents->isNotEmpty()) {
                            $statusClass = 'status-alert';
                            $icon = 'fa-skull-crossbones';
                        }
                    @endphp
                    <a href="{{ route('resources.edit', $res->id) }}" class="cyber-server-node {{ $statusClass }}" title="{{ $res->name }}">
                        <i class="fas {{ $icon }} node-icon"></i>
                        <div class="node-name">{{ $res->name }}</div>
                        <div style="font-size: 0.6rem; color: var(--cyber-text-muted); margin-top: 2px;">{{ $res->rack_position ?? 'N/A' }}</div>
                    </a>
                @empty
                    <div style="grid-column: 1/-1; padding: 40px; text-align: center; color: var(--cyber-text-muted); font-family: monospace;">
                        > MATRIX EMPTY. NO NODES DETECTED.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: ACTIVITY & QUICK TOOLS --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            {{-- Quick Tools Terminal --}}
            <div class="pd-card" style="background: var(--cyber-panel);">
                <h3 class="pd-card-title" style="color: var(--cyber-secondary);"><i class="fas fa-terminal"></i> BIN.TOOLS</h3>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('resources.export') }}" class="cyber-btn" style="width: 100%; justify-content: flex-start;">
                        > EXEC ./export_db.sh
                    </a>
                    <a href="{{ route('engineer.rack_map') }}" class="cyber-btn cyber-btn-secondary" style="width: 100%; justify-content: flex-start;">
                        > EXEC ./view_racks.sh
                    </a>
                </div>
            </div>

            <div class="pd-card" style="flex: 1; display: flex; flex-direction: column; background: var(--cyber-panel);">
                <h3 class="pd-card-title"><i class="fas fa-bolt"></i> SYS.LOG</h3>
                <ul class="cyber-list" style="flex: 1; overflow-y: auto; max-height: 250px;">
                    @forelse($recentActivity as $log)
                        <li>
                            <div class="cyber-list-time">[{{ $log->created_at->format('H:i') }}]</div>
                            <div class="cyber-list-content">
                                <div class="cyber-list-action" style="color: var(--cyber-secondary);">{{ $log->action }}</div>
                                <div class="cyber-list-detail">{{ \Illuminate\Support\Str::limit($log->description, 40) }}</div>
                            </div>
                        </li>
                    @empty
                        <div style="padding: 24px; text-align: center; color: var(--cyber-text-muted); font-family: monospace;">> NO RECENT ACTIVITY.</div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection