@extends('layouts.app')

@push('styles')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    @vite(['resources/css/reservations/calendar.css'])
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Calendrier des Réservations</h1>
            <p class="page-subtitle">Vue d'ensemble de vos créneaux validés.</p>
        </div>
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
            <i class="fas fa-list"></i> Vue Liste
        </a>
    </div>

    <div class="calendar-container">
        <div id='calendar'></div>
    </div>

    <script>
        window.calendarEvents = @json($events);
    </script>
    @vite(['resources/js/reservations/calendar.js'])
@endsection