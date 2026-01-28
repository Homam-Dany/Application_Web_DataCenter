@extends('layouts.app')

@push('styles')
    @vite(['resources/css/reservations/create.css'])
@endpush

@section('content')
    <div class="page-header res-create-header">
        <div>
            <h1 class="page-title">Réserver une <span>Ressource</span></h1>
            <p class="page-subtitle res-create-subtitle">Planifiez votre allocation de ressources Data Center en quelques
                secondes.</p>
        </div>
    </div>

    <div class="card res-create-card">
        <div class="card-body">

            @if ($errors->any())
                <div class="res-error-container">
                    <ul class="res-error-list">
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf

                <!-- Resource Selection -->
                <div class="res-form-group">
                    <label class="res-form-label">
                        <i class="fas fa-server"></i> Choisir l'équipement
                    </label>
                    <select name="resource_id" id="resource_id" required class="res-form-select">
                        <option value="">-- Liste des ressources disponibles --</option>
                        @foreach($resources as $resource)
                            <option value="{{ $resource->id }}" data-cpu="{{ $resource->cpu }}" data-ram="{{ $resource->ram }}"
                                data-storage="{{ $resource->storage_capacity }}Go" data-os="{{ $resource->os }}">
                                {{ $resource->name }} ({{ $resource->type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Technical Specs Preview (Hidden by default) -->
                <div id="preview-card" style="display: none;">
                    <h4 class="preview-title">
                        <i class="fas fa-info-circle"></i> Spécifications techniques :
                    </h4>
                    <div class="preview-grid">
                        <div class="preview-item">
                            <i class="fas fa-desktop"></i> OS: <span id="p-os" class="preview-value"></span>
                        </div>
                        <div class="preview-item">
                            <i class="fas fa-microchip"></i> CPU: <span id="p-cpu" class="preview-value"></span> Cores
                        </div>
                        <div class="preview-item">
                            <i class="fas fa-memory"></i> RAM: <span id="p-ram" class="preview-value"></span> GB
                        </div>
                        <div class="preview-item">
                            <i class="fas fa-hdd"></i> Disque: <span id="p-storage" class="preview-value"></span>
                        </div>
                    </div>
                </div>

                <div class="date-range-grid">
                    <div>
                        <label class="res-form-label">
                            <i class="far fa-calendar-alt"></i> Date de début
                        </label>
                        <input type="date" name="start_date" id="start_date" required class="res-form-input">
                    </div>
                    <div>
                        <label class="res-form-label">
                            <i class="far fa-calendar-check"></i> Date de fin
                        </label>
                        <input type="date" name="end_date" id="end_date" required class="res-form-input">
                    </div>
                </div>

                <div class="res-form-group">
                    <label class="res-form-label">
                        <i class="fas fa-pen-alt"></i> Justification du besoin
                    </label>
                    <textarea name="justification" rows="3" required
                        placeholder="Expliquez pourquoi vous avez besoin de cette ressource..."
                        class="res-form-textarea">{{ old('justification') }}</textarea>
                </div>

                <div class="res-form-actions">
                    <button type="submit" class="btn btn-primary btn-confirm-res">
                        <i class="fas fa-check-circle"></i> Confirmer la réservation
                    </button>
                    <a href="{{ route('resources.index') }}" class="btn-cancel-res">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/reservations/create.js'])
    @endpush
@endsection