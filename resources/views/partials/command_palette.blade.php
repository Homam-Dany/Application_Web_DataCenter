{{-- Global Command Palette --}}
<div id="command-palette-backdrop" class="cmd-backdrop" style="display: none;">
    <div class="cmd-modal">
        <div class="cmd-header">
            <i class="fas fa-search cmd-icon"></i>
            <input type="text" id="cmd-input" placeholder="Tapez une commande..." autocomplete="off">
            <div class="cmd-badges">
                <span class="cmd-badge">ESC pour fermer</span>
            </div>
        </div>
        <div class="cmd-content" id="cmd-results">
            {{-- Dynamic Content --}}
            <div class="cmd-group">
                <div class="cmd-group-title">Navigation</div>
                <a href="{{ auth()->check() && auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                    class="cmd-item selected">
                    <div class="cmd-item-icon"><i class="fas fa-home"></i></div>
                    <span class="cmd-item-text">Dashboard</span>
                    <span class="cmd-item-meta">Accueil</span>
                </a>
                <a href="{{ route('resources.index') }}" class="cmd-item">
                    <div class="cmd-item-icon"><i class="fas fa-server"></i></div>
                    <span class="cmd-item-text">Catalogue</span>
                    <span class="cmd-item-meta">Liste des ressources</span>
                </a>

                @auth
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('reservations.index') }}" class="cmd-item">
                            <div class="cmd-item-icon"><i class="fas fa-calendar-check"></i></div>
                            <span class="cmd-item-text">Mes Réservations</span>
                        </a>
                    @endif

                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'responsable')
                        <a href="{{ route('reservations.manager') }}" class="cmd-item">
                            <div class="cmd-item-icon"><i class="fas fa-inbox"></i></div>
                            <span class="cmd-item-text">Gestion des Demandes</span>
                        </a>
                        <a href="{{ route('resources.manager') }}" class="cmd-item">
                            <div class="cmd-item-icon"><i class="fas fa-tasks"></i></div>
                            <span class="cmd-item-text">Ma Gestion</span>
                            <span class="cmd-item-meta">Hub Responsable</span>
                        </a>
                        <a href="{{ route('incidents.manager') }}" class="cmd-item">
                            <div class="cmd-item-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            <span class="cmd-item-text">Gestion Incidents</span>
                        </a>
                    @endif

                    @if(auth()->user()->role === 'responsable')
                        <a href="{{ route('engineer.dashboard') }}" class="cmd-item">
                            <div class="cmd-item-icon" style="color: #4f46e5;"><i class="fas fa-microchip"></i></div>
                            <span class="cmd-item-text">Dashboard - Espace Responsable</span>
                            <span class="cmd-item-meta">Dashboard Technique</span>
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users') }}" class="cmd-item">
                            <div class="cmd-item-icon"><i class="fas fa-users"></i></div>
                            <span class="cmd-item-text">Utilisateurs</span>
                            <span class="cmd-item-meta">Gestion des comptes</span>
                        </a>
                        <a href="{{ route('admin.logs') }}" class="cmd-item">
                            <div class="cmd-item-icon"><i class="fas fa-history"></i></div>
                            <span class="cmd-item-text">Logs du Système</span>
                        </a>
                    @endif

                    <a href="{{ route('notifications.index') }}" class="cmd-item">
                        <div class="cmd-item-icon"><i class="fas fa-bell"></i></div>
                        <span class="cmd-item-text">Notifications</span>
                    </a>
                @endauth

                <a href="{{ route('about') }}" class="cmd-item">
                    <div class="cmd-item-icon"><i class="fas fa-info-circle"></i></div>
                    <span class="cmd-item-text">À Propos</span>
                </a>
            </div>

            @php
                $allResources = \App\Models\Resource::select('id', 'name', 'type', 'location')->get();
            @endphp
            @if($allResources->count() > 0)
                <div class="cmd-group">
                    <div class="cmd-group-title">Inventaire Ressources</div>
                    @foreach($allResources as $res)
                        <a href="{{ route('resources.show', $res->id) }}" class="cmd-item">
                            <div class="cmd-item-icon"><i class="fas fa-microchip"></i></div>
                            <span class="cmd-item-text">{{ $res->name }}</span>
                            <span class="cmd-item-meta">{{ $res->type }} • {{ $res->location ?? 'N/A' }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="cmd-group">
                <div class="cmd-group-title">Actions Rapides</div>
                @auth
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'responsable')
                        <a href="{{ route('resources.create') }}" class="cmd-item">
                            <div class="cmd-item-icon"><i class="fas fa-plus"></i></div>
                            <span class="cmd-item-text">Ajouter une Ressource</span>
                        </a>
                        <a href="{{ route('resources.export') }}" class="cmd-item">
                            <div class="cmd-item-icon"><i class="fas fa-file-export"></i></div>
                            <span class="cmd-item-text">Exporter Inventaire</span>
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('reports.monthly') }}" class="cmd-item">
                                <div class="cmd-item-icon"><i class="fas fa-file-pdf"></i></div>
                                <span class="cmd-item-text">Rapport Mensuel</span>
                            </a>
                        @endif
                    @endif
                @endauth
                <a href="{{ route('profile.edit') }}" class="cmd-item">
                    <div class="cmd-item-icon"><i class="fas fa-user-circle"></i></div>
                    <span class="cmd-item-text">Mon Profil</span>
                </a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('theme-toggle').click();"
                    class="cmd-item">
                    <div class="cmd-item-icon"><i class="fas fa-adjust"></i></div>
                    <span class="cmd-item-text">Basculer Mode Sombre/Clair</span>
                </a>
            </div>
        </div>
        <div class="cmd-footer">
            Utilisez les flèches <span class="cmd-key">↑</span> <span class="cmd-key">↓</span> pour naviguer et <span
                class="cmd-key">Entrée</span> pour choisir
        </div>
    </div>
</div>