@extends('layouts.app')

@push('styles')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <style>
        .calendar-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            min-height: 600px;
        }

        .fc-event {
            cursor: pointer;
            border: none;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
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
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour'
                },
                events: @json($events),
                eventClick: function (info) {
                    alert('Réservation: ' + info.event.title + '\nStatut: ' + info.event.extendedProps.status);
                }
            });
            calendar.render();
        });
    </script>
@endsection