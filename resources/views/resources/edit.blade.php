@extends('layouts.app')

@push('styles')
    @vite(['resources/css/resources/edit.css'])
@endpush

@section('content')
    <div class="container edit-resource-container">

        <div class="card edit-resource-card">
            <div class="card-body edit-resource-body">
                <div class="page-header edit-resource-header">
                    <div>
                        <h1 class="page-title edit-resource-title">
                            Configuration Système : 
                            <span class="edit-resource-title-accent">{{ $resource->name }}</span>
                        </h1>
                        <p class="edit-resource-subtitle">
                            <i class="fas fa-info-circle"></i>
                            Remplacements globaux de l'administrateur pour les spécifications matérielles.
                        </p>
                    </div>
                </div>

                <form action="{{ route('resources.update', $resource) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="edit-resource-grid">
                        <!-- Hardware Label -->
                        <div>
                            <label class="edit-form-label">
                                <i class="fas fa-tag"></i> Étiquette Matériel
                            </label>
                            <input type="text" name="name" value="{{ $resource->name }}" class="edit-form-input">
                        </div>

                        <!-- Manager -->
                        <div>
                            <label class="edit-form-label">
                                <i class="fas fa-user-tie"></i> Responsable
                            </label>
                            <select name="manager_id" class="edit-form-select">
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ $resource->manager_id == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- CPU -->
                        <div>
                            <label class="edit-form-label">
                                <i class="fas fa-microchip"></i> Coeurs CPU
                            </label>
                            <div style="position: relative;">
                                <input type="number" name="cpu" value="{{ $resource->cpu }}" class="edit-form-input">
                            </div>
                        </div>

                        <!-- RAM -->
                        <div>
                            <label class="edit-form-label">
                                <i class="fas fa-memory"></i> Mémoire RAM (Go)
                            </label>
                            <input type="number" name="ram" value="{{ $resource->ram }}" class="edit-form-input">
                        </div>
                    </div>

                    <div class="edit-resource-footer">
                        <a href="{{ route('resources.manager') }}" class="btn-cancel-edit">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary btn-save-edit">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
