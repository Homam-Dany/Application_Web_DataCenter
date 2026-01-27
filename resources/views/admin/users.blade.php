@extends('layouts.app')

@section('content')
    <div style="padding: 20px;">
        {{-- En-tête de la page --}}
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: #1e293b; font-size: 1.875rem; font-weight: 800; margin: 0; letter-spacing: -0.025em;">
                    Gestion des <span style="color: var(--primary);">Utilisateurs</span>
                </h1>
                <p style="color: #64748b; margin-top: 0.5rem; font-size: 1rem;">Validez les nouveaux comptes et gérez les accès au Data Center.</p>
            </div>
            <div style="background: white; padding: 10px 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <span style="color: #64748b; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fas fa-users" style="margin-right: 8px; color: var(--primary);"></i> {{ count($users) }} membres au total
                </span>
            </div>
        </div>

        {{-- SECTION 1 : DEMANDES D'OUVERTURE DE COMPTE (EN ATTENTE) --}}
        @php 
            $pendingUsers = $users->where('role', 'guest')->where('is_active', false)->where('rejection_reason', null); 
        @endphp

        <div style="margin-bottom: 40px;">
            <h2 style="color: #b45309; font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <span style="background: #f59e0b; width: 10px; height: 10px; border-radius: 50%;"></span>
                Demandes d'ouverture de compte <span style="background: #fef3c7; color: #b45309; padding: 2px 8px; border-radius: 20px; font-size: 0.8rem; margin-left: 5px;">{{ count($pendingUsers) }}</span>
            </h2>

            <div class="card" style="background: white; border-radius: 16px; box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05); overflow: hidden; border: 1px solid {{ count($pendingUsers) > 0 ? '#fef3c7' : '#f1f5f9' }};">
                @if(count($pendingUsers) > 0)
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; background: #fffcf0; border-bottom: 1px solid #fef3c7;">
                                <th style="padding: 15px 20px; color: #b45309; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Candidat</th>
                                <th style="padding: 15px 20px; color: #b45309; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Attribuer un Rôle & Valider</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingUsers as $guest)
                                <tr style="border-bottom: 1px solid #fffcf0; transition: all 0.2s;" onmouseover="this.style.background='#fffef5'" onmouseout="this.style.background='white'">
                                    <td style="padding: 15px 20px;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 40px; height: 40px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #d97706; font-weight: 700;">
                                                {{ strtoupper(substr($guest->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong style="color: #1e293b; font-size: 0.95rem;">{{ $guest->name }}</strong><br>
                                                <small style="color: #64748b;">{{ $guest->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        <form action="{{ route('admin.users.update', $guest) }}" method="POST" style="display: flex; gap: 12px; align-items: center;">
                                            @csrf @method('PATCH')
                                            <select name="role" required style="background: white; color: #1e293b; border: 1px solid #e2e8f0; padding: 10px; border-radius: 10px; flex: 1; font-size: 0.9rem;">
                                                <option value="" disabled selected>Choisir le rôle...</option>
                                                <option value="user">Ingénieur Réseau</option>
                                                <option value="responsable">Responsable Technique</option>
                                                <option value="admin">Administrateur</option>
                                            </select>
                                            <input type="hidden" name="is_active" value="1">
                                            <button type="submit" class="btn" style="background: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); font-size: 0.85rem;">
                                                <i class="fas fa-check-circle"></i> Activer
                                            </button>
                                            <button type="button" onclick="openRejectionModal('{{ $guest->id }}', '{{ $guest->name }}')" class="btn" style="background: #ef4444; color: white; border: none; padding: 10px 15px; border-radius: 10px; font-weight: 700; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2); font-size: 0.85rem;">
                                                <i class="fas fa-times-circle"></i> Refuser
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="padding: 40px; text-align: center;">
                        <div style="width: 60px; height: 60px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #94a3b8; font-size: 1.5rem;">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <p style="color: #64748b; font-weight: 500;">Aucune demande en attente pour le moment.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- SECTION 2 : UTILISATEURS ACTIFS & GESTION GÉNÉRALE --}}
        <h2 style="color: #1e293b; font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;">
            <i class="fas fa-user-shield" style="color: var(--primary); margin-right: 8px;"></i> Gestion des accès existants
        </h2>
        
        <div class="card" style="background: white; padding: 0; border-radius: 16px; box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05); overflow: hidden; border: 1px solid #f1f5f9;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                        <th style="padding: 15px 20px; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Utilisateur</th>
                        <th style="padding: 15px 20px; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Rôle & Permissions</th>
                        <th style="padding: 15px 20px; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; text-align: center;">Statut</th>
                        <th style="padding: 15px 20px; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; text-align: center;">Actions</th>
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
                        <tr style="border-bottom: 1px solid #f8fafc; transition: all 0.2s;" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='white'">
                            <td style="padding: 15px 20px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 38px; height: 38px; background: {{ $user->is_active ? '#eef2ff' : '#f1f5f9' }}; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: {{ $user->is_active ? 'var(--primary)' : '#94a3b8' }}; font-weight: 800; font-size: 0.9rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong style="color: #334155; font-size: 0.95rem;">{{ $user->name }}</strong><br>
                                        <small style="color: #94a3b8;">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px 20px;">
                                <form action="{{ route('admin.users.update', $user) }}" method="POST" style="display: flex; gap: 8px; align-items: center;">
                                    @csrf @method('PATCH')
                                    <select name="role" style="background: white; color: var(--primary); border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; flex: 1;">
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Ingénieur Réseau</option>
                                        <option value="responsable" {{ $user->role == 'responsable' ? 'selected' : '' }}>Responsable Tech</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    </select>
                                    <button type="submit" class="btn" style="padding: 8px 12px; font-size: 0.75rem; color: #64748b; border: 1px solid #e2e8f0; background: #f8fafc; border-radius: 8px; font-weight: 700; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f8fafc'">
                                        Sauver
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                @if($user->rejection_reason)
                                    <span style="background: #fff5f5; color: #ef4444; border: 2px solid #fca5a5; padding: 5px 20px; border-radius: 50px; font-weight: 700; font-size: 0.75rem; letter-spacing: 0.5px; display: inline-block; text-transform: uppercase;" title="{{ $user->rejection_reason }}">
                                        REFUSÉ
                                    </span>
                                @elseif($user->is_active)
                                    <span style="background: #f0f9f7; color: #00b894; border: 2px solid #a3e6d8; padding: 5px 20px; border-radius: 50px; font-weight: 700; font-size: 0.75rem; letter-spacing: 0.5px; display: inline-block; text-transform: uppercase;">
                                        ACTIF
                                    </span>
                                @else
                                    <span style="background: #fff5f5; color: #e53e3e; border: 2px solid #feb2b2; padding: 5px 20px; border-radius: 50px; font-weight: 700; font-size: 0.75rem; letter-spacing: 0.5px; display: inline-block; text-transform: uppercase;">
                                        DÉSACTIVÉ
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="is_active" value="{{ $user->is_active ? 0 : 1 }}">
                                    <button class="btn" style="font-size: 0.8rem; font-weight: 700; color: white; background: {{ $user->is_active ? '#f43f5e' : '#10b981' }}; border: none; padding: 10px 18px; border-radius: 10px; box-shadow: 0 4px 12px {{ $user->is_active ? 'rgba(244, 63, 94, 0.2)' : 'rgba(16, 185, 129, 0.2)' }}; transition: all 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
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
        <div style="background: white; padding: 30px; border-radius: 16px; width: 90%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <h3 style="color: #ef4444; margin-top: 0; margin-bottom: 15px; font-size: 1.5rem; font-weight: 700;">Refuser la demande</h3>
            <p style="color: #64748b; margin-bottom: 20px;">Veuillez indiquer le motif du refus pour <strong id="modalUserName"></strong>.</p>
            
            <form id="rejectionForm" action="" method="POST">
                @csrf @method('PATCH')
                <!-- On garde le rôle guest et inactif, on ajoute juste la raison -->
                <input type="hidden" name="role" value="guest">
                <input type="hidden" name="is_active" value="0">
                
                <div style="margin-bottom: 20px;">
                    <label for="rejection_reason" style="display: block; color: #475569; font-weight: 600; margin-bottom: 8px;">Motif du refus</label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px; font-family: inherit;" placeholder="Ex: Informations incomplètes..."></textarea>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeRejectionModal()" style="padding: 10px 20px; border-radius: 8px; border: 1px solid #cbd5e1; background: white; color: #64748b; font-weight: 600; cursor: pointer;">Annuler</button>
                    <button type="submit" style="padding: 10px 20px; border-radius: 8px; border: none; background: #ef4444; color: white; font-weight: 700; cursor: pointer;">Confirmer le refus</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/admin/users.js'])
    @endpush
@endsection
