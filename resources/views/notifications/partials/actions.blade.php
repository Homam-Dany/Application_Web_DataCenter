{{-- resources/views/notifications/partials/actions.blade.php --}}

@if(isset($notification->data['reservation_id']))
    @php
        $reservation = \App\Models\Reservation::find($notification->data['reservation_id']);
    @endphp

    @if($reservation && $reservation->status === 'en_attente' && (auth()->user()->role === 'admin' || auth()->user()->role === 'responsable'))
        <div style="margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 15px;">
            <a href="{{ route('reservations.manager') }}" class="btn btn-primary"
                style="text-decoration: none; display: inline-block;">
                <i class="fas fa-external-link-alt"></i> Gérer dans Demandes
            </a>
        </div>
    @endif
@endif

@if(isset($notification->data['incident_id']))
    @php
        $incident = \App\Models\Incident::find($notification->data['incident_id']);
    @endphp

    @if($incident && $incident->status === 'ouvert' && (auth()->user()->role === 'admin' || auth()->user()->role === 'responsable'))
        <div style="margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 15px;">
            <a href="{{ route('incidents.manager') }}" class="btn"
                style="text-decoration: none; display: inline-block; background-color: #f59e0b; color: white;">
                <i class="fas fa-tools"></i> Gérer l'incident
            </a>
        </div>
    @endif
@endif

@if(isset($notification->data['type']) && $notification->data['type'] === 'inscription')
    @if(auth()->user()->role === 'admin')
        <div style="margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 15px;">
            <a href="{{ route('admin.users') }}" class="btn"
                style="text-decoration: none; display: inline-block; background-color: #8b5cf6; color: white;">
                <i class="fas fa-user-check"></i> Gérer les utilisateurs
            </a>
        </div>
    @endif
@endif