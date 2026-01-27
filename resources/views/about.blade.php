@extends('layouts.app')

@push('styles')
    @vite(['resources/css/about.css'])
@endpush

@section('content')
    <div class="page-header section-header">
        <h1 class="page-title" style="justify-content: center;">À Propos du <span>Data Center</span></h1>
        <p class="page-subtitle about-subtitle">
            Conditions générales d'accès et d'exploitation des ressources du Data Center IDAI.
        </p>
    </div>

    {{-- Grille des règles avec l'effet de verre (Glassmorphism) --}}
    <div class="stats-grid">
        {{-- Règle 01 : Éligibilité (SUCCESS / GREEN) --}}
        <div class="card card-accent-success">
            <div class="rule-header">
                <span class="badge rule-badge success-accent">01</span>
                <h3 class="rule-title">Éligibilité</h3>
            </div>
            <p class="rule-text">
                L'accès est réservé aux enseignants, chercheurs et doctorants disposant d'un compte actif validé. Le partage
                de compte est strictement interdit.
            </p>
        </div>

        {{-- Règle 02 : Réservations (INFO / BLUE) --}}
        <div class="card card-accent-info">
            <div class="rule-header">
                <span class="badge rule-badge info-accent">02</span>
                <h3 class="rule-title">Réservations</h3>
            </div>
            <p class="rule-text">
                Toute demande doit être justifiée. Les réservations sont limitées à 15 jours pour garantir un partage
                équitable des ressources entre les projets.
            </p>
        </div>

        {{-- Règle 03 : Interdictions (WARNING / ORANGE) --}}
        <div class="card card-accent-warning">
            <div class="rule-header">
                <span class="badge rule-badge warning-accent">03</span>
                <h3 class="rule-title">Interdictions</h3>
            </div>
            <p class="rule-text">
                Le minage de cryptomonnaie, les tests d'intrusion non autorisés et le stockage de données illégales
                entraînent une exclusion immédiate.
            </p>
        </div>
    </div>

    <hr class="about-separator">

    <!-- Administration Section -->
    <h2 class="team-section-title">Administration</h2>
    <div class="admin-wrapper">
        <div class="card team-member-card">
            <div class="card-body member-info">
                <div class="team-image-wrapper">
                    <img src="{{ asset('images/team/dany.jpg') }}" alt="Dany Homam"
                        onerror="this.src='https://ui-avatars.com/api/?name=Dany+Homam&background=0284c7&color=fff'">
                </div>
                <h3 class="member-name">Dany Homam</h3>
                <p class="member-role admin-role">Administrateur Système</p>
                <a href="mailto:dany.homam@etu.uae.ac.ma" class="btn btn-outline btn-contact">
                    <i class="far fa-envelope"></i> Contacter Homam
                </a>
            </div>
        </div>
    </div>

    <!-- Technical Team Section -->
    <h2 class="team-section-title">Équipe Technique</h2>
    <!-- Container for the Marquee -->
    <div class="team-marquee-wrapper">
        <div id="teamGrid" class="team-grid-container">
            <!-- Salim -->
            <div class="card team-member-card">
                <div class="card-body member-info">
                    <div class="team-image-wrapper">
                        <img src="{{ asset('images/team/salim.jpg') }}" alt="El Bourmaki Salim"
                            onerror="this.src='https://ui-avatars.com/api/?name=Salim+El+Bourmaki&background=64748b&color=fff'">
                    </div>
                    <h3 class="member-name">El Bourmaki Salim</h3>
                    <p class="member-role">Responsable Technique</p>
                    <a href="mailto:elbourmaki.salim@etu.uae.ac.ma" class="btn btn-outline btn-contact">
                        <i class="far fa-envelope"></i> Contacter Salim
                    </a>
                </div>
            </div>

            <!-- Houssam -->
            <div class="card team-member-card">
                <div class="card-body member-info">
                    <div class="team-image-wrapper">
                        <img src="{{ asset('images/team/houssam.jpg') }}" alt="El Hajioui Houssam"
                            onerror="this.src='https://ui-avatars.com/api/?name=Houssam+El+Hajioui&background=64748b&color=fff'">
                    </div>
                    <h3 class="member-name">El Hajioui Houssam</h3>
                    <p class="member-role">Responsable Technique</p>
                    <a href="mailto:houssam.elhajioui@etu.uae.ac.ma" class="btn btn-outline btn-contact">
                        <i class="far fa-envelope"></i> Contacter Houssam
                    </a>
                </div>
            </div>

            <!-- Fatima -->
            <div class="card team-member-card">
                <div class="card-body member-info">
                    <div class="team-image-wrapper">
                        <img src="{{ asset('images/team/fatima.jpg') }}" alt="Farssi Fatima Zahra"
                            onerror="this.src='https://ui-avatars.com/api/?name=Fatima+Zahra+Farssi&background=64748b&color=fff'">
                    </div>
                    <h3 class="member-name">Farssi Fatima Zahra</h3>
                    <p class="member-role">Responsable Technique</p>
                    <a href="mailto:farssi.fatimazahra@etu.uae.ac.ma" class="btn btn-outline btn-contact">
                        <i class="far fa-envelope"></i> Contacter Fatima
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/about.js'])
    @endpush
@endsection