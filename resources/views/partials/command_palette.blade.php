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
                <a href="{{ route('dashboard') }}" class="cmd-item selected">
                    <div class="cmd-item-icon"><i class="fas fa-home"></i></div>
                    <span class="cmd-item-text">Dashboard</span>
                    <span class="cmd-item-meta">Accueil</span>
                </a>
                <a href="{{ route('resources.index') }}" class="cmd-item">
                    <div class="cmd-item-icon"><i class="fas fa-server"></i></div>
                    <span class="cmd-item-text">Catalogue</span>
                    <span class="cmd-item-meta">Liste des ressources</span>
                </a>
                @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'responsable'))
                    <a href="{{ route('resources.manager') }}" class="cmd-item">
                        <div class="cmd-item-icon"><i class="fas fa-tasks"></i></div>
                        <span class="cmd-item-text">Ma Gestion</span>
                        <span class="cmd-item-meta">Hub Responsable</span>
                    </a>
                @endif
                @if(auth()->check() && auth()->user()->role === 'responsable')
                    <a href="{{ route('engineer.dashboard') }}" class="cmd-item">
                        <div class="cmd-item-icon" style="color: #4f46e5;"><i class="fas fa-microchip"></i></div>
                        <span class="cmd-item-text">Cockpit Ingénieur</span>
                        <span class="cmd-item-meta">Dashboard Technique</span>
                    </a>
                @endif
            </div>

            <div class="cmd-group">
                <div class="cmd-group-title">Actions Rapides</div>
                @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'responsable'))
                    <a href="{{ route('resources.create') }}" class="cmd-item">
                        <div class="cmd-item-icon"><i class="fas fa-plus"></i></div>
                        <span class="cmd-item-text">Ajouter une Ressource</span>
                    </a>
                    <a href="{{ route('resources.export') }}" class="cmd-item">
                        <div class="cmd-item-icon"><i class="fas fa-file-export"></i></div>
                        <span class="cmd-item-text">Exporter Inventaire</span>
                    </a>
                @endif
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