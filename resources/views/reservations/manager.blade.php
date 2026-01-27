@extends('layouts.app')

@push('styles')
    @vite(['resources/css/reservations/manager.css'])
@endpush

@section('content')
    <div class="container res-manager-container">
        <h1 class="res-manager-title">Gestion des <span>Demandes</span></h1>

        @if(session('success'))
            <div class="res-manager-alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="res-manager-list">
            @forelse($pendingReservations as $res)
                <div class="res-manager-card">
                    <div class="res-card-header">
                        <h2 class="res-card-title">{{ $res->resource->name }}</h2>
                        <p class="res-card-subtitle">Demandeur : <strong>{{ $res->user->name }}</strong></p>

                        {{-- Affichage clair de la justification pour le responsable --}}
                        <div class="res-justification-box">
                            <strong class="res-justification-label">Justification du client :</strong>
                            <p class="res-justification-text">
                                "{{ $res->justification }}"
                            </p>
                        </div>
                    </div>

                    <div class="res-actions-section">
                        <form action="{{ route('reservations.decide', ['id' => $res->id, 'action' => 'accepter']) }}"
                            method="POST" class="accept-form">
                            @csrf
                            <button type="submit" class="btn-accept-res">
                                ✅ ACCEPTER LA DEMANDE
                            </button>
                        </form>

                        <form action="{{ route('reservations.decide', ['id' => $res->id, 'action' => 'refuser']) }}"
                            method="POST">
                            @csrf
                            <label for="reason_{{ $res->id }}" class="refuse-form-label">
                                Justification du refus obligatoire :
                            </label>

                            <textarea id="reason_{{ $res->id }}" name="rejection_reason" required
                                placeholder="Saisissez la raison du refus..." class="refuse-textarea"></textarea>

                            @error('rejection_reason')
                                <p class="refuse-error">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="btn-refuse-res">
                                ❌ CONFIRMER LE REFUS
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="empty-res-notice">Aucune demande en attente.</p>
            @endforelse
        </div>
    </div>
@endsection
@endsection