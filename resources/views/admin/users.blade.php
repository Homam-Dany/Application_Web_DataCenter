@extends('layouts.app')

@push('styles')
    @vite(['resources/css/admin/users.css'])
@endpush

@section('content')
    <div class="users-container">
        {{-- En-tête de la page --}}
        <div class="page-header dashboard-header">
            <div>
                <h1 class="dashboard-title">
                    Gestion des <span style="color: var(--primary);">Utilisateurs</span>
                </h1>
                <p class="dashboard-subtitle">Validez les nouveaux comptes et gérez les accès au Data Center.</p>
            </div>
            <div class="header-summary">
                <span class="header-summary-text">
                    <i class="fas fa-users" style="margin-right: 8px; color: var(--primary);"></i> {{ count($users) }} membres au total
                </span>
            </div>
        </div>

        {{-- SECTION 1 : DEMANDES D'OUVERTURE DE COMPTE (EN ATTENTE) --}}
        @php 
            $pendingUsers = $users->where('role', 'guest')->where('is_active', false)->where('rejection_reason', null); 
        @endphp

        <div style="margin-bottom: 40px;">
            <h2 class="section-title-pending">
                <span class="status-dot-pending"></span>
                Demandes d'ouverture de compte <span class="count-badge-pending">{{ count($pendingUsers) }}</span>
            </h2>

            <div class="card card-pending {{ count($pendingUsers) > 0 ? 'has-items' : 'no-items' }}">
                @if(count($pendingUsers) > 0)
                    <table class="pending-table">
                        <thead>
                            <tr>
                                <th>Candidat</th>
                                <th>Attribuer un Rôle & Valider</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingUsers as $guest)
                                <tr class="user-row-pending">
                                    <td style="padding: 15px 20px;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div class="user-avatar-pending">
                                                {{ strtoupper(substr($guest->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong class="user-name-text">{{ $guest->name }}</strong><br>
                                                <small class="user-email-text">{{ $guest->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        <form action="{{ route('admin.users.update', $guest) }}" method="POST" class="action-form-pending">
                                            @csrf @method('PATCH')
                                            <select name="role" required class="role-select">
                                                <option value="" disabled selected>Choisir le rôle...</option>
                                                <option value="user">Ingénieur Réseau</option>
                                                <option value="responsable">Responsable Technique</option>
                                                <option value="admin">Administrateur</option>
                                            </select>
                                            <input type="hidden" name="is_active" value="1">
                                            <button type="submit" class="btn btn-activate">
                                                <i class="fas fa-check-circle"></i> Activer
                                            </button>
                                            <button type="button" onclick="openRejectionModal('{{ $guest->id }}', '{{ $guest->name }}')" class="btn btn-refuse">
                                                <i class="fas fa-times-circle"></i> Refuser
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state-container">
                        <div class="empty-state-icon">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <p class="empty-state-text">Aucune demande en attente pour le moment.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- SECTION 2 : UTILISATEURS ACTIFS & GESTION GÉNÉRALE --}}
        <h2 style="color: #1e293b; font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;">
            <i class="fas fa-user-shield" style="color: var(--primary); margin-right: 8px;"></i> Gestion des accès existants
        </h2>
        
        <div class="card" style="background: white; padding: 0; border-radius: 16px; box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05); overflow: hidden; border: 1px solid #f1f5f9;">
            <table class="active-users-table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Rôle & Permissions</th>
                        <th style="text-align: center;">Statut</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // On prend tous les users qui ne sont pas "en attente" (donc actif, ou rejeté, ou role défini)
                        $managedUsers = $users->reject(function ($user) {
                            return $user->role === 'guest' && !$user->is_active && $user->rejection_reason === null;
                        });
                    @endphp
                    @foreach($managedUsers as $user)
                        <tr class="user-row-active">
                            <td style="padding: 15px 20px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div class="user-avatar-active {{ $user->is_active ? '' : 'user-avatar-inactive' }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong class="user-name-text">{{ $user->name }}</strong><br>
                                        <small class="user-email-text">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px 20px;">
                                <form action="{{ route('admin.users.update', $user) }}" method="POST" style="display: flex; gap: 8px; align-items: center;">
                                    @csrf @method('PATCH')
                                    <select name="role" class="role-select-active">
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Ingénieur Réseau</option>
                                        <option value="responsable" {{ $user->role == 'responsable' ? 'selected' : '' }}>Responsable Tech</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    </select>
                                    <button type="submit" class="btn btn-save-role">
                                        Sauver
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                @if($user->rejection_reason)
                                    <span class="status-badge status-refused" title="{{ $user->rejection_reason }}">
                                        REFUSÉ
                                    </span>
                                @elseif($user->is_active)
                                    <span class="status-badge status-active">
                                        ACTIF
                                    </span>
                                @else
                                    <span class="status-badge status-disabled">
                                        DÉSACTIVÉ
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="is_active" value="{{ $user->is_active ? 0 : 1 }}">
                                    <button class="btn btn-toggle-access {{ $user->is_active ? 'btn-revoke' : 'btn-reactivate' }}">
                                        {{ $user->is_active ? 'Révoquer l\'accès' : 'Réactiver' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal de Refus --}}
    <div id="rejectionModal" 
        data-route="{{ route('admin.users.update', ':id') }}"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div class="rejection-modal-content">
            <h3 class="modal-header-refuse">Refuser la demande</h3>
            <p class="modal-body-text">Veuillez indiquer le motif du refus pour <strong id="modalUserName"></strong>.</p>
            
            <form id="rejectionForm" action="" method="POST">
                @csrf @method('PATCH')
                <!-- On garde le rôle guest et inactif, on ajoute juste la raison -->
                <input type="hidden" name="role" value="guest">
                <input type="hidden" name="is_active" value="0">
                
                <div style="margin-bottom: 20px;">
                    <label for="rejection_reason" class="form-label-refuse">Motif du refus</label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" required class="form-textarea-refuse" placeholder="Ex: Informations incomplètes..."></textarea>
                </div>
                
                <div class="modal-actions">
                    <button type="button" onclick="closeRejectionModal()" class="btn btn-modal-cancel">Annuler</button>
                    <button type="submit" class="btn btn-modal-confirm">Confirmer le refus</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/admin/users.js'])
    @endpush
@endsection
