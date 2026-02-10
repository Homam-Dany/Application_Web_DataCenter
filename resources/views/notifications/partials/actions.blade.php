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