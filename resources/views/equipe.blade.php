@extends('app')

@section('title', "Notre Équipe — Verre d'Eau Fraîche")
@section('meta_description', "Découvrez les membres engagés de l'association Verre d'Eau Fraîche qui œuvrent chaque jour pour les personnes vulnérables.")

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- HERO                                                                    --}}
{{-- Les couleurs utilisent style="" pour garantir leur affichage           --}}
{{-- indépendamment de la compilation Tailwind/Vite                         --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<section style="background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%); position:relative; overflow:hidden; padding: 5rem 0;">

    {{-- Motif décoratif SVG en fond --}}
    <div style="position:absolute; inset:0; opacity:0.06; pointer-events:none;"
         aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="dots" x="0" y="0" width="32" height="32" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="2" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dots)"/>
        </svg>
    </div>

    <div style="position:relative; max-width:80rem; margin:0 auto; padding:0 1.5rem; text-align:center;">

        {{-- Fil d'Ariane --}}
        <nav style="display:flex; justify-content:center; align-items:center; gap:0.5rem; margin-bottom:2rem;">
            <a href="{{ url('/') }}"
               style="color:rgba(255,255,255,0.75); font-size:0.875rem; text-decoration:none; transition:color .2s;"
               onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.75)'">
                Accueil
            </a>
            <svg style="width:14px; height:14px; color:rgba(255,255,255,0.4);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span style="color:white; font-size:0.875rem; font-weight:600;">Notre Équipe</span>
        </nav>

        {{-- Titre --}}
        <h1 style="font-size: clamp(2.2rem, 6vw, 3.75rem); font-weight:800; color:#ffffff; letter-spacing:-0.02em; line-height:1.1; margin:0;"
            data-aos="fade-up">
            Notre Équipe
        </h1>

        {{-- Sous-titre --}}
        <p style="margin-top:1.25rem; font-size:clamp(1rem, 2.5vw, 1.2rem); color:rgba(255,255,255,0.85); max-width:36rem; margin-left:auto; margin-right:auto; line-height:1.7;"
           data-aos="fade-up" data-aos-delay="100">
            Des hommes et des femmes engagés qui donnent de leur temps et de leur énergie pour changer des vies.
        </p>

        {{-- Badge compteur --}}
        @if($membres->count() > 0)
        <div style="margin-top:2rem; display:inline-flex; align-items:center; gap:0.625rem; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); border-radius:9999px; padding:0.625rem 1.5rem;"
             data-aos="fade-up" data-aos-delay="150">
            <span style="display:inline-block; width:8px; height:8px; background:#4ade80; border-radius:50%; animation: pulse 2s infinite;"></span>
            <span style="color:white; font-weight:700; font-size:1.05rem;">{{ $membres->count() }} membres actifs</span>
        </div>
        @endif

    </div>
</section>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.4; }
}

/* Style du bouton filtre actif */
.filtre-btn-actif {
    background: #2563eb !important;
    color: #ffffff !important;
    border-color: #2563eb !important;
}

/* Masquer les cartes filtrées avec transition */
.membre-cache {
    display: none !important;
}
</style>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- BARRE DE RECHERCHE & FILTRES                                            --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
@if($membres->count() > 0)
<div style="position:sticky; top:80px; z-index:40; background:rgba(255,255,255,0.97); backdrop-filter:blur(12px); border-bottom:1px solid #e5e7eb; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <div style="max-width:80rem; margin:0 auto; padding:1rem 1.5rem;">

        {{-- Ligne 1 : recherche + compteur --}}
        <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center;">

            {{-- Input recherche --}}
            <div style="position:relative; flex:1; min-width:200px; max-width:400px;">
                <div style="position:absolute; top:50%; left:1rem; transform:translateY(-50%); pointer-events:none;">
                    <svg style="width:18px; height:18px; color:#9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       id="recherche-membre"
                       placeholder="Rechercher par nom…"
                       style="width:100%; padding:0.625rem 2.5rem 0.625rem 2.75rem; background:#f9fafb; border:1px solid #d1d5db; border-radius:0.75rem; font-size:0.9rem; color:#111827; outline:none; transition:border-color .2s, box-shadow .2s; box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.15)'; this.style.background='#fff';"
                       onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'; this.style.background='#f9fafb';">
                <button id="btn-clear-search"
                        onclick="clearSearch()"
                        style="display:none; position:absolute; top:50%; right:0.75rem; transform:translateY(-50%); background:none; border:none; cursor:pointer; padding:0.25rem; color:#9ca3af;">
                    <svg style="width:15px; height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Compteur --}}
            <div style="display:flex; align-items:center; gap:6px; color:#6b7280; font-size:0.875rem; flex-shrink:0;">
                <span id="count-visible"
                      style="font-size:1.2rem; font-weight:800; color:#2563eb;">{{ $membres->count() }}</span>
                <span>membre(s) affiché(s)</span>
            </div>

        </div>

        {{-- Ligne 2 : filtres par poste --}}
        @if($postes->count() > 1)
        <div id="filtres-postes" style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-top:0.75rem;">
            <button onclick="filtrerPoste('', this)"
                    class="filtre-btn filtre-btn-actif"
                    style="padding:0.35rem 1rem; border-radius:9999px; font-size:0.8rem; font-weight:600; border:1px solid #2563eb; cursor:pointer; transition:all .2s; background:#2563eb; color:#fff;">
                Tous les rôles
            </button>
            @foreach($postes as $poste)
            <button onclick="filtrerPoste('{{ addslashes($poste) }}', this)"
                    class="filtre-btn"
                    style="padding:0.35rem 1rem; border-radius:9999px; font-size:0.8rem; font-weight:500; border:1px solid #d1d5db; cursor:pointer; transition:all .2s; background:#fff; color:#4b5563;">
                {{ $poste }}
            </button>
            @endforeach
        </div>
        @endif

    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- GRILLE DES MEMBRES                                                      --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<section style="padding:3.5rem 0; background:#f8fafc;">
    <div style="max-width:80rem; margin:0 auto; padding:0 1.5rem;">

        {{-- Message "aucun résultat" --}}
        <div id="aucun-resultat"
             style="display:none; text-align:center; padding:5rem 1rem;">
            <div style="width:72px; height:72px; background:#eff6ff; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem;">
                <svg style="width:36px; height:36px; color:#93c5fd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p style="color:#374151; font-weight:600; font-size:1.05rem; margin:0 0 0.5rem;">Aucun membre trouvé</p>
            <p style="color:#9ca3af; font-size:0.875rem; margin:0 0 1.5rem;">Essayez un autre nom ou réinitialisez les filtres.</p>
            <button onclick="reinitialiserFiltres()"
                    style="padding:0.6rem 1.5rem; background:#2563eb; color:#fff; border:none; border-radius:9999px; font-size:0.875rem; font-weight:600; cursor:pointer;">
                Réinitialiser les filtres
            </button>
        </div>

        {{-- Grille --}}
        <div id="grille-membres"
             style="display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:1.5rem;">

            @foreach($membres as $membre)
            <article class="membre-card"
                     data-nom="{{ strtolower($membre->prenom . ' ' . $membre->nom) }}"
                     data-poste="{{ $membre->poste }}"
                     style="background:#ffffff; border-radius:1rem; overflow:hidden; border:1px solid #e5e7eb; box-shadow:0 1px 4px rgba(0,0,0,0.06); transition:box-shadow .25s, transform .25s;"
                     onmouseover="this.style.boxShadow='0 8px 30px rgba(37,99,235,0.12)'; this.style.transform='translateY(-4px)'; this.style.borderColor='#bfdbfe';"
                     onmouseout="this.style.boxShadow='0 1px 4px rgba(0,0,0,0.06)'; this.style.transform='translateY(0)'; this.style.borderColor='#e5e7eb';">

                {{-- Bande bleue en haut de la carte --}}
                <div style="height:6px; background:linear-gradient(90deg, #1d4ed8, #3b82f6);"></div>

                <div style="padding:1.5rem; display:flex; flex-direction:column; align-items:center; text-align:center;">

                    {{-- Photo ou avatar initiales --}}
                    <div style="position:relative; margin-bottom:1rem;">
                        @if($membre->photo)
                            <img src="{{ asset($membre->photo) }}"
                                 alt="{{ $membre->prenom }} {{ $membre->nom }}"
                                 loading="lazy"
                                 style="width:76px; height:76px; border-radius:50%; object-fit:cover; border:3px solid #eff6ff; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                        @else
                            <div style="width:76px; height:76px; border-radius:50%; background:linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%); display:flex; align-items:center; justify-content:center; border:3px solid #eff6ff; box-shadow:0 2px 8px rgba(37,99,235,0.2);">
                                <span style="font-size:1.4rem; font-weight:800; color:#ffffff; letter-spacing:-0.02em; user-select:none;">
                                    {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->nom, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        {{-- Pastille "actif" --}}
                        <span style="position:absolute; bottom:2px; right:2px; width:14px; height:14px; background:#22c55e; border:2px solid #fff; border-radius:50%; display:block;"></span>
                    </div>

                    {{-- Nom --}}
                    <h2 style="font-size:0.95rem; font-weight:700; color:#111827; margin:0; line-height:1.3;">
                        {{ $membre->prenom }} {{ $membre->nom }}
                    </h2>

                    {{-- Poste --}}
                    <span style="display:inline-block; margin-top:0.5rem; padding:0.25rem 0.75rem; background:#eff6ff; color:#1d4ed8; font-size:0.75rem; font-weight:600; border-radius:9999px; border:1px solid #bfdbfe;">
                        {{ $membre->poste }}
                    </span>

                    {{-- Bio --}}
            
                    @if($membre->bio)
                    <div class="membre-bio">
                        <p class="membre-bio__texte" style="margin-top:0.875rem; font-size:0.8rem; color:#6b7280; line-height:1.6; overflow:hidden; display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical;">{{ $membre->bio }}</p>
                        <button type="button" class="membre-bio__bouton" aria-expanded="false" hidden
                                style="margin-top:0.5rem; background:none; border:0; padding:0; font-size:0.8rem; font-weight:600; color:#1d4ed8; cursor:pointer;">Lire la suite</button>
                    </div>
                    @endif
                

                </div>
            </article>
            @endforeach

        </div>
    </div>
</section>

@else
{{-- ── Aucun membre en base ──────────────────────────────────────────────── --}}
<section style="padding:8rem 1.5rem; background:#f8fafc; text-align:center;">
    <div style="max-width:28rem; margin:0 auto;">
        <div style="width:88px; height:88px; background:#eff6ff; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 2rem;">
            <svg style="width:44px; height:44px; color:#93c5fd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h2 style="font-size:1.5rem; font-weight:700; color:#1f2937; margin:0 0 1rem;">L'équipe arrive bientôt !</h2>
        <p style="color:#6b7280; margin:0 0 2rem; line-height:1.7;">Les profils des membres seront affichés ici prochainement.</p>
        <a href="{{ url('/') }}"
           style="display:inline-block; padding:0.75rem 2rem; background:#2563eb; color:#fff; font-weight:600; border-radius:9999px; text-decoration:none; font-size:0.9rem;">
            Retour à l'accueil
        </a>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- CALL TO ACTION BAS DE PAGE                                              --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#1d4ed8; padding:4rem 1.5rem;">
    <div style="max-width:48rem; margin:0 auto; text-align:center;" data-aos="fade-up">
        <h2 style="font-size:clamp(1.5rem, 4vw, 2rem); font-weight:800; color:#ffffff; margin:0 0 1rem;">
            Rejoignez l'aventure !
        </h2>
        <p style="color:rgba(255,255,255,0.85); font-size:1.05rem; margin:0 0 2rem; line-height:1.7; max-width:34rem; margin-left:auto; margin-right:auto;">
            Vous souhaitez faire partie de notre équipe et contribuer à notre mission humanitaire ?
        </p>
        <div style="display:flex; flex-wrap:wrap; gap:1rem; justify-content:center;">
            <a href="{{ route('rejoindre') }}"
               style="padding:0.875rem 2rem; background:#ffffff; color:#1d4ed8; font-weight:700; border-radius:9999px; text-decoration:none; font-size:0.95rem; box-shadow:0 4px 12px rgba(0,0,0,0.15); transition:transform .2s;"
               onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                Postuler maintenant
            </a>
            <a href="{{ url('/#contact') }}"
               style="padding:0.875rem 2rem; background:transparent; border:2px solid rgba(255,255,255,0.6); color:#ffffff; font-weight:600; border-radius:9999px; text-decoration:none; font-size:0.95rem; transition:background .2s, border-color .2s;"
               onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.borderColor='rgba(255,255,255,0.9)';"
               onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(255,255,255,0.6)';">
                Nous contacter
            </a>
        </div>
    </div>
</section>

<section class="bg-white px-6 py-16"><div class="mx-auto max-w-4xl"><x-public-heading eyebrow="Derrière les actions, des personnes" title="Des rôles et des compétences qui se complètent.">Les profils présentés sur cette page sont ceux publiés par l’association. Pour entrer en relation avec l’équipe, utilisez les coordonnées officielles de VEF : elles permettent d’orienter votre message vers les personnes concernées.</x-public-heading><p class="mt-5 leading-8 text-gray-600">Vous souhaitez proposer votre aide ? Expliquez votre expérience, vos disponibilités et le domaine qui vous intéresse. La candidature est une première étape pour discuter de votre participation, sans préjuger d’une mission ou d’un rôle disponible.</p></div></section>
<x-public-next-step />
@endsection

@push('scripts')
<script>
(function () {
    let recherche  = '';
    let posteActif = '';

    const cartes         = document.querySelectorAll('.membre-card');
    const compteur       = document.getElementById('count-visible');
    const aucunResultat  = document.getElementById('aucun-resultat');
    const champRecherche = document.getElementById('recherche-membre');
    const btnClear       = document.getElementById('btn-clear-search');

    // ── Filtrage principal ───────────────────────────────────────────────
    function filtrer() {
        let visible = 0;

        cartes.forEach(function (carte) {
            const correspondNom   = !recherche  || carte.dataset.nom.includes(recherche);
            const correspondPoste = !posteActif || carte.dataset.poste === posteActif;

            if (correspondNom && correspondPoste) {
                carte.style.display = '';
                visible++;
            } else {
                carte.style.display = 'none';
            }
        });

        if (compteur) compteur.textContent = visible;

        if (aucunResultat) {
            aucunResultat.style.display = visible === 0 ? 'block' : 'none';
        }

        if (btnClear) {
            btnClear.style.display = recherche ? 'flex' : 'none';
        }
    }

    // ── Recherche par nom ────────────────────────────────────────────────
    if (champRecherche) {
        champRecherche.addEventListener('input', function () {
            recherche = this.value.toLowerCase().trim();
            filtrer();
        });
    }

    // ── Filtre par poste ─────────────────────────────────────────────────
    window.filtrerPoste = function (poste, bouton) {
        posteActif = poste;

        document.querySelectorAll('.filtre-btn').forEach(function (btn) {
            btn.style.background    = '#ffffff';
            btn.style.color         = '#4b5563';
            btn.style.borderColor   = '#d1d5db';
        });

        if (bouton) {
            bouton.style.background  = '#2563eb';
            bouton.style.color       = '#ffffff';
            bouton.style.borderColor = '#2563eb';
        }

        filtrer();
    };

    // ── Effacer la recherche ─────────────────────────────────────────────
    window.clearSearch = function () {
        if (champRecherche) {
            champRecherche.value = '';
            recherche = '';
            filtrer();
            champRecherche.focus();
        }
    };

    // ── Tout réinitialiser ───────────────────────────────────────────────
    window.reinitialiserFiltres = function () {
        recherche  = '';
        posteActif = '';
        if (champRecherche) champRecherche.value = '';

        const premierBouton = document.querySelector('.filtre-btn');
        if (premierBouton) window.filtrerPoste('', premierBouton);
        else filtrer();
    };

})();
</script>

<script>
document.querySelectorAll('.membre-bio').forEach(function (bloc) {
    var texte = bloc.querySelector('.membre-bio__texte');
    var bouton = bloc.querySelector('.membre-bio__bouton');
    if (texte.scrollHeight > texte.clientHeight + 1) { bouton.hidden = false; }
    bouton.addEventListener('click', function () {
        var ouvert = bouton.getAttribute('aria-expanded') === 'true';
        texte.style.webkitLineClamp = ouvert ? '4' : 'unset';
        texte.style.display = ouvert ? '-webkit-box' : 'block';
        bouton.setAttribute('aria-expanded', ouvert ? 'false' : 'true');
        bouton.textContent = ouvert ? 'Lire la suite' : 'Réduire';
    });
});
</script>
@endpush
