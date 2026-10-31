<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Verre d’Eau Fraîche — Association humanitaire')</title>
    <meta name="description" content="@yield('meta_description', 'Association humanitaire au Bénin : éducation, santé et autonomie. Découvrez les actions de VEF et trouvez votre façon de contribuer.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Verre d’Eau Fraîche — Association humanitaire')">
    <meta property="og:description" content="@yield('meta_description', 'Agissons pour chacun, agissons pour tous.')">
    <meta property="og:image" content="@yield('og_image', asset('Style1/images/VEF.jpeg'))">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Verre d’Eau Fraîche">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/png" href="{{ asset('Style1/images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('Style1/css/project-status.css') }}">
    @stack('head')
</head>
<body class="vef-site {{ request()->routeIs('index') ? 'vef-home' : 'vef-inner' }}">
    <a class="skip-link" href="#contenu">Aller au contenu</a>
    @php
        $navGroups = [
            "L'association" => ['Notre association' => route('association'), 'Notre équipe' => route('equipe'), 'Questions fréquentes' => route('faq')],
            'Nos projets' => ['Tous les projets' => route('projets.index'), 'Projets en cours' => route('projets.index', ['statut' => 'en_cours']), 'Projets réalisés' => route('projets.index', ['statut' => 'realise'])],
            'S’engager' => ['Devenir bénévole' => route('rejoindre'), 'Parrainage' => route('parrainage.index'), 'Devenir partenaire' => url('/#contact')],
        ];
    @endphp
    <header class="site-header" data-site-header>
        <div class="site-header__inner">
            <a href="{{ route('index') }}" class="site-brand" aria-label="Verre d’Eau Fraîche — Accueil">
                <img src="{{ asset('Style1/images/logo.png') }}" width="48" height="48" alt="Logo VEF">
                <span class="site-brand__text">Verre d’Eau<br><strong>Fraîche.</strong></span>
            </a>
            <nav class="desktop-nav" aria-label="Navigation principale">
                <a href="{{ route('index') }}" @if(request()->routeIs('index')) aria-current="page" @endif>Accueil</a>
                @foreach($navGroups as $label => $links)
                    <details class="nav-dropdown">
                        <summary>{{ $label }} <span aria-hidden="true">⌄</span></summary>
                        <div class="nav-dropdown__panel">
                            @foreach($links as $text => $href)
                                <a href="{{ $href }}" @if(url()->full() === $href) aria-current="page" @endif>{{ $text }} <span aria-hidden="true">↗</span></a>
                            @endforeach
                        </div>
                    </details>
                    @if($label === 'Nos projets')
                        <a href="{{ route('actualites.index') }}" @if(request()->routeIs('actualites.*')) aria-current="page" @endif>Actualités</a>
                    @endif
                @endforeach
                <a href="{{ url('/#contact') }}">Contact</a>
            </nav>
            <div class="header-actions">
                <a href="{{ route('donation.index') }}" class="vef-button vef-button--blue header-donate">Faire un don <span aria-hidden="true">↗</span></a>
                <button type="button" class="menu-toggle" aria-label="Ouvrir le menu" aria-controls="navigation-mobile" aria-expanded="false" data-menu-open>
                    <span></span><span></span>
                </button>
            </div>
        </div>
    </header>
    <dialog id="navigation-mobile" class="site-menu" aria-labelledby="mobile-menu-title">
        <div class="site-menu__head">
            <h2 id="mobile-menu-title">Explorons VEF.</h2>
            <button type="button" class="menu-close" aria-label="Fermer le menu" data-menu-close>×</button>
        </div>
        <nav aria-label="Navigation mobile">
            <a class="site-menu__link" href="{{ route('index') }}">Accueil <span aria-hidden="true">↗</span></a>
            @foreach($navGroups as $label => $links)
                <details>
                    <summary>{{ $label }} <span aria-hidden="true">+</span></summary>
                    <div class="site-menu__sublinks">@foreach($links as $text => $href)<a href="{{ $href }}">{{ $text }}</a>@endforeach</div>
                </details>
                @if($label === 'Nos projets')<a class="site-menu__link" href="{{ route('actualites.index') }}">Actualités <span aria-hidden="true">↗</span></a>@endif
            @endforeach
            <a class="site-menu__link" href="{{ url('/#contact') }}">Contact <span aria-hidden="true">↗</span></a>
        </nav>
        <a href="{{ route('donation.index') }}" class="vef-button vef-button--blue">Faire un don <span aria-hidden="true">↗</span></a>
        <p class="site-menu__note">Au Bénin, la solidarité se construit ensemble.</p>
    </dialog>
    <main id="contenu" tabindex="-1">
        @if(session('success'))<div class="site-notice site-notice--success" role="status">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="site-notice site-notice--error" role="alert">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="site-notice site-notice--error" role="alert">{{ $errors->first() }}</div>@endif
        @yield('content')
    </main>
    <footer class="site-footer" aria-labelledby="footer-heading">
        <div class="vef-container">
            <div class="footer-invitation">
                <div><p class="vef-eyebrow">Chaque geste ouvre une possibilité.</p><h2 id="footer-heading" class="vef-display">Et si on agissait<br><span>ensemble ?</span></h2></div>
                <a href="{{ route('rejoindre') }}" class="vef-button vef-button--white">Trouver ma façon d’agir <span aria-hidden="true">↗</span></a>
            </div>
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="{{ route('index') }}" class="site-brand"><img src="{{ asset('Style1/images/logo.png') }}" width="56" height="56" alt="Logo VEF"><span>Verre d’Eau<br><strong>Fraîche.</strong></span></a>
                    <p>Agissons pour chacun, agissons pour tous. Une association humanitaire engagée pour l’éducation, la santé et l’autonomie au Bénin.</p>
                    <div class="footer-socials"><a href="https://www.facebook.com/ONG.VERRE.D.EAU.FRAICHE?locale=fr_FR" target="_blank" rel="noopener noreferrer">Facebook ↗</a><a href="https://www.linkedin.com/company/association-humanitaire-verre-d-eau-fraiche/posts/?feedView=all" target="_blank" rel="noopener noreferrer">LinkedIn ↗</a><a href="https://wa.me/2290196501084" target="_blank" rel="noopener noreferrer">WhatsApp ↗</a></div>
                </div>
                <div><h3>Découvrir</h3><nav aria-label="Découvrir VEF"><a href="{{ route('association') }}">Notre association</a><a href="{{ route('projets.index') }}">Nos projets</a><a href="{{ route('equipe') }}">Notre équipe</a><a href="{{ route('actualites.index') }}">Actualités</a></nav></div>
                <div><h3>Participer</h3><nav aria-label="Participer aux actions"><a href="{{ route('donation.index') }}">Faire un don</a><a href="{{ route('rejoindre') }}">Devenir bénévole</a><a href="{{ route('parrainage.index') }}">Parrainage</a><a href="{{ route('faq') }}">Questions fréquentes</a></nav></div>
                <div class="footer-contact"><h3>On se parle ?</h3><a href="mailto:contact@verredeaufraiche.com">contact@verredeaufraiche.com</a><a href="tel:+2290196501084">+229 01 96 50 10 84</a><p>Gbedjromédé 1, Rue 6.132, carré / îlot 1209, Maison FANOU, C. ANTOINE<br>Cotonou, Bénin</p><a href="{{ url('/#contact') }}" class="footer-contact__link">Nous écrire <span aria-hidden="true">↗</span></a></div>
            </div>
            <div class="footer-bottom"><p>© {{ date('Y') }} Verre d’Eau Fraîche</p><nav aria-label="Informations pratiques et légales">@foreach(['confidentialite' => 'Confidentialité', 'cookies' => 'Cookies', 'mentions-legales' => 'Mentions légales', 'conditions-utilisation' => 'Conditions d’utilisation'] as $route => $label)<a href="{{ route($route) }}">{{ $label }}</a>@endforeach</nav></div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
