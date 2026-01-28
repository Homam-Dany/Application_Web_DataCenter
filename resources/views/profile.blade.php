@extends('layouts.app')

@push('styles')
    @vite(['resources/css/profile.css'])
@endpush

@section('content')
    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <div class="status-alert">
            <i class="fas fa-check-circle"></i>
            <span>
                @if (session('status') === 'profile-updated')
                    Vos informations de profil ont été mises à jour avec succès.
                @else
                    Votre mot de passe a été modifié avec succès.
                @endif
            </span>
        </div>
    @endif

    <div class="page-header profile-header">
        <div>
            <h1 class="page-title">Mon Profil <span>Détails</span></h1>
            <p class="page-subtitle">Gérez vos informations personnelles et la sécurité de votre compte.</p>
        </div>
    </div>

    <div class="profile-container">

        {{-- SECTION 1 : INFORMATIONS PERSONNELLES --}}
        <div class="card">
            <header class="profile-card-header">
                <h2 class="profile-card-title">
                    <i class="fas fa-user-circle"></i>
                    Informations du Profil
                </h2>
                <p class="profile-card-subtitle">
                    Mettez à jour vos informations de contact et votre adresse e-mail.
                </p>
            </header>

            <div class="profile-form-wrapper">
                <form method="post" action="{{ route('profile.update') }}" class="profile-form">
                    @csrf
                    @method('patch')

                    {{-- Nom --}}
                    <div class="form-group-profile">
                        <label for="name" class="form-label-profile">Nom complet</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                            autocomplete="name" class="form-input-profile">
                        @error('name')
                            <p class="form-error-profile">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group-profile">
                        <label for="email" class="form-label-profile">Adresse E-mail</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                            autocomplete="username" class="form-input-profile">
                        @error('email')
                            <p class="form-error-profile">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save" style="margin-right: 8px;"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- SECTION 2 : SÉCURITÉ DU COMPTE --}}
        <div class="card">
            <header class="profile-card-header">
                <h2 class="profile-card-title">
                    <i class="fas fa-shield-alt"></i>
                    Sécurité du Compte
                </h2>
                <p class="profile-card-subtitle">
                    Assurez-vous que votre compte utilise un mot de passe long et complexe.
                </p>
            </header>

            <div class="profile-form-wrapper">
                <form method="post" action="{{ route('password.update') }}" class="profile-form">
                    @csrf
                    @method('put')

                    {{-- Mot de passe actuel --}}
                    <div class="form-group-profile">
                        <label for="current_password" class="form-label-profile">Mot de passe actuel</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                            class="form-input-profile">
                        @error('current_password', 'updatePassword')
                            <p class="form-error-profile">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nouveau mot de passe --}}
                    <div class="form-group-profile">
                        <label for="password" class="form-label-profile">Nouveau mot de passe</label>
                        <input id="password" name="password" type="password" autocomplete="new-password"
                            class="form-input-profile">
                        @error('password', 'updatePassword')
                            <p class="form-error-profile">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmation --}}
                    <div class="form-group-profile">
                        <label for="password_confirmation" class="form-label-profile">Confirmer le mot de passe</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" class="form-input-profile">
                        @error('password_confirmation', 'updatePassword')
                            <p class="form-error-profile">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key" style="margin-right: 8px;"></i> Mettre à jour le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- SECTION 3 : SUPPRESSION DU COMPTE --}}
        <div class="card card-danger">
            <header class="profile-card-header">
                <h2 class="profile-card-title">
                    <i class="fas fa-user-minus"></i>
                    Suppression du Compte
                </h2>
                <p class="profile-card-subtitle">
                    La suppression du compte est irréversible. Toutes vos données seront effacées.
                </p>
            </header>

            <div>
                {{-- Bouton pour révéler la section de confirmation --}}
                <button type="button" class="btn btn-danger btn-delete-reveal" id="btn-delete-reveal"
                    style="{{ $errors->userDeletion->isNotEmpty() ? 'display: none;' : '' }}">
                    <i class="fas fa-trash-alt" style="margin-right: 8px;"></i> Supprimer mon compte
                </button>

                {{-- Section cachée de confirmation --}}
                <div id="delete-confirmation-section" class="delete-confirmation-section"
                    style="display: {{ $errors->userDeletion->isNotEmpty() ? 'block' : 'none' }};">
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')

                        <h2 class="delete-confirm-title">
                            <i class="fas fa-exclamation-triangle"></i>
                            Confirmation requise
                        </h2>

                        <p class="delete-confirm-text">
                            Êtes-vous sûr ? Une fois votre compte supprimé, toutes ses ressources et données seront
                            définitivement effacées. Veuillez confirmer avec votre mot de passe.
                        </p>

                        <div class="profile-form-wrapper">
                            <div class="form-group-profile" style="margin-bottom: 1.5rem;">
                                <label for="password" class="delete-pwd-label">Confirmer mot de passe</label>
                                <input id="password" name="password" type="password" placeholder="Votre mot de passe"
                                    class="form-input-delete">
                                @error('password', 'userDeletion')
                                    <p class="form-error-profile" style="font-weight: 600;">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="delete-actions">
                                <button type="button" id="btn-cancel-delete" class="btn-cancel-delete">
                                    Annuler
                                </button>

                                <button type="submit" class="btn btn-danger btn-confirm-delete">
                                    Confirmer la suppression
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/profile.js'])
    @endpush
@endsection