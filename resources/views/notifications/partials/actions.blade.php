{{-- resources/views/notifications/partials/actions.blade.php --}}

@if(isset($notification->data['reservation_id']))
    @php
        $reservation = \App\Models\Reservation::find($notification->data['reservation_id']);
    @endphp

    @if($reservation && $reservation->status === 'en_attente' && in_array(auth()->user()->role, ['admin', 'responsable']))
        <div style="margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 20px;">
            <form id="decision-form-{{ $reservation->id }}" data-url="{{ url('/reservations/decide') }}" action=""
                method="POST">
                @csrf

                <div id="rejection-area-{{ $reservation->id }}" style="display: none; margin-bottom: 15px;">
                    <label
                        style="color: #ef4444; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 8px;">
                        Motif du refus (Obligatoire) :
                    </label>
                    <textarea name="rejection_reason" id="reason-input-{{ $reservation->id }}"
                        placeholder="Expliquez pourquoi la demande est refusée..." class="form-control"
                        style="width: 100%; min-height: 80px; resize: none;"></textarea>
                </div>

                <div style="display: flex; gap: 15px;">
                    <button type="button" class="btn btn-success btn-decision-accept" data-id="{{ $reservation->id }}">
                        <i class="fas fa-check"></i> ACCEPTER
                    </button>

                    <button type="button" id="refuse-btn-{{ $reservation->id }}" class="btn btn-danger btn-decision-refuse"
                        data-id="{{ $reservation->id }}">
                        <i class="fas fa-times"></i> REFUSER
                    </button>
                </div>
            </form>
        </div>
    @endif
@endif