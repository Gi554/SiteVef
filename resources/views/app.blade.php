<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO -->
    <title>@yield('title', "Verre d'Eau Fraîche — Association humanitaire")</title>
    <meta name="description" content="@yield('meta_description', "Association humanitaire Verre d'Eau Fraîche : éducation, santé et autonomisation des personnes vulnérables au Bénin et en Afrique.")">

    <!-- Open Graph (partage Facebook / LinkedIn) -->
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:title"       content="@yield('title', "Verre d'Eau Fraîche — Association humanitaire")">
    <meta property="og:description" content="@yield('meta_description', "Agissons pour chacun, agissons pour tous.")">
    <meta property="og:image"       content="@yield('og_image', asset('Style1/images/better.jpg'))">
    <meta property="og:locale"      content="fr_FR">
    <meta property="og:site_name"   content="Verre d'Eau Fraîche">

    <link rel="canonical" href="{{ url()->current() }}">
    @stack('head')
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('Style1/images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS — animations au scroll -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <!-- Vite (Tailwind + Alpine) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        :focus-visible { outline: 3px solid #2563eb; outline-offset: 4px; }
        @media(prefers-reduced-motion: reduce) { *, *::before, *::after { animation: none !important; transition: none !important; scroll-behavior: auto !important; } }
        /* Empêche tout débordement horizontal sur mobile */
        html, body { overflow-x: hidden; max-width: 100%; }
    </style>
<link rel="stylesheet" href="{{ asset('Style1/css/project-status.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased flex flex-col min-h-screen">

    <!-- ── Navigation ─────────────────────────────────────────────────────── -->
    <header x-data="{ mobileMenuOpen: false, scrolled: false }"
            x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
            :class="scrolled ? 'shadow-md bg-white' : 'bg-white/90 backdrop-blur-md border-b border-gray-100'"
            @keydown.escape.window="mobileMenuOpen = false" class="fixed w-full z-50 transition-all duration-300">

        <nav class="mx-auto flex max-w-7xl items-center justify-between p-4 lg:px-8" aria-label="Global">
            <div class="flex lg:flex-1">
                <a href="{{ url('/') }}" class="-m-1.5 p-1.5 transition-transform hover:scale-105">
                    <span class="sr-only">Verre d'eau fraîche</span>
                    <img class="h-12 w-auto" src="{{ asset('Style1/images/logo.png') }}" alt="Logo VEF">
                </a>
            </div>

            <!-- Bouton hamburger mobile -->
            <div class="flex lg:hidden">
                <button type="button" @click="mobileMenuOpen = true"
                        class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700 hover:bg-gray-100 transition-colors">
                    <span class="sr-only">Ouvrir le menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            @php
                $navGroups = [
                    "L'association" => [
                        'À propos' => url('/#about'),
                        'Notre équipe' => route('equipe'),
                    ],
                    'Nos projets' => [
                        'Tous les projets' => route('projets.index'),
                        'Projets en cours' => route('projets.index', ['statut' => 'en_cours']),
                        'Projets réalisés' => route('projets.index', ['statut' => 'realise']),
                    ],
                    'S’engager' => [
                        'Devenir bénévole' => route('rejoindre'),
                        'Parrainage' => route('parrainage.index'),
                        'Devenir partenaire' => url('/#contact'),
                        'Faire un don' => route('donation.index'),
                    ],
                ];
            @endphp

            <div class="hidden lg:flex items-center gap-x-5 text-sm font-semibold">
                <a href="{{ route('index') }}" class="hover:text-blue-600">Accueil</a>
                @foreach($navGroups as $label => $links)
                    <details class="relative group">
                        <summary class="cursor-pointer hover:text-blue-600">{{ $label }}</summary>
                        <div class="absolute left-0 top-full pt-4 w-56">
                            <div class="bg-white border border-gray-100 shadow-lg rounded-xl p-2">
                                @foreach($links as $text => $href)
                                    <a class="block p-3 rounded-lg hover:bg-blue-50 focus:bg-blue-50" href="{{ $href }}">{{ $text }}</a>
                                @endforeach
                            </div>
                        </div>
                    </details>
                    @if($label === 'Nos projets')
                        <a href="{{ route('actualites.index') }}" class="hover:text-blue-600">Actualités</a>
                    @endif
                @endforeach
                <a href="{{ url('/#contact') }}" class="hover:text-blue-600">Contact</a>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                <a href="{{ route('donation.index') }}"
                   class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    Faire un don
                </a>
            </div>
        </nav>

        <!-- Menu mobile -->
        <div x-show="mobileMenuOpen" class="lg:hidden" role="dialog" aria-modal="true" x-cloak>
            <div class="fixed inset-0 z-50 bg-black/20 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>
            <div class="fixed right-0 top-0 z-50 max-h-screen w-full overflow-y-auto bg-white px-6 py-6 sm:ring-1 sm:ring-gray-900/10"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">
                <div class="flex items-center justify-between">
                    <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
                        <img class="h-10 w-auto" src="{{ asset('Style1/images/logo.png') }}" alt="Logo VEF">
                    </a>
                    <button type="button" aria-label="Fermer le menu" @click="mobileMenuOpen = false" class="-m-2.5 rounded-md p-2.5 text-gray-700 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="space-y-2 py-6">
                            <a href="{{ route('index') }}" class="block py-3 font-semibold">Accueil</a>
                            @foreach($navGroups as $label => $links)
                                <details class="py-2">
                                    <summary class="font-semibold cursor-pointer">{{ $label }}</summary>
                                    <div class="pl-4">
                                        @foreach($links as $text => $href)
                                            <a class="block py-3 text-gray-700" href="{{ $href }}">{{ $text }}</a>
                                        @endforeach
                                    </div>
                                </details>
                                @if($label === 'Nos projets')
                                    <a href="{{ route('actualites.index') }}" class="block py-3 font-semibold">Actualités</a>
                                @endif
                            @endforeach
                            <a href="{{ url('/#contact') }}" class="block py-3 font-semibold">Contact</a>
                        </div>
                        <div class="py-6">
                            <a href="{{ route('donation.index') }}" @click="mobileMenuOpen = false"
                               class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold text-blue-600 hover:bg-gray-50 text-center border border-blue-100 bg-blue-50">
                                Faire un don
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ── Contenu principal ───────────────────────────────────────────────── -->
    <main class="flex-grow pt-20">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="fixed top-24 left-1/2 -translate-x-1/2 z-50 bg-green-600 text-white px-6 py-3 rounded-full shadow-lg text-sm font-semibold flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))<div class="max-w-7xl mx-auto p-4 text-red-800 bg-red-50" role="alert">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="max-w-7xl mx-auto p-4 text-red-800 bg-red-50" role="alert">{{ $errors->first() }}</div>@endif
        @yield('content')
    </main>

    <!-- ── Footer ─────────────────────────────────────────────────────────── -->
    <footer class="bg-gray-900 text-white" aria-labelledby="footer-heading">
        <h2 id="footer-heading" class="sr-only">Footer</h2>
        <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
            <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                <div class="space-y-8">
                    <img class="h-16 w-auto brightness-0 invert opacity-90" src="{{ asset('Style1/images/logo.png') }}" alt="Logo VEF">
                    <p class="text-sm leading-6 text-gray-300 max-w-md">
                        Verre d'eau fraîche, agissons pour chacun, agissons pour tous. Association humanitaire œuvrant pour l'éducation, la santé et l'autonomisation des plus vulnérables.
                    </p>
                    <div class="flex space-x-6">
                        <a href="https://www.facebook.com/ONG.VERRE.D.EAU.FRAICHE?locale=fr_FR" target="_blank" rel="noopener" class="text-gray-400 hover:text-blue-500 transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/company/association-humanitaire-verre-d-eau-fraiche/posts/?feedView=all" target="_blank" rel="noopener" class="text-gray-400 hover:text-blue-400 transition-colors">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="https://wa.me/2290195666928" target="_blank" rel="noopener" class="text-gray-400 hover:text-green-500 transition-colors">
                            <span class="sr-only">WhatsApp</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" clip-rule="evenodd"/>
                            </svg>
                        </a>                           
                    </div>
                </div>
                <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        <div>
                            <h3 class="text-sm font-semibold text-white">Navigation</h3>
                            <ul role="list" class="mt-6 space-y-4">
                                <li><a href="{{ url('/#hero') }}"      class="text-sm text-gray-300 hover:text-white transition-colors">Accueil</a></li>
                                <li><a href="{{ url('/#about') }}"     class="text-sm text-gray-300 hover:text-white transition-colors">À propos</a></li>
                                <li><a href="{{ url('/#services') }}"  class="text-sm text-gray-300 hover:text-white transition-colors">Nos projets</a></li>
                                <li><a href="{{ url('/#temoignages') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Témoignages</a></li>
                                <li><a href="{{ route('equipe') }}"     class="text-sm text-gray-300 hover:text-white transition-colors">Notre Équipe</a></li>
                                <li><a href="{{ route('parrainage.index') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Parrainage</a></li>
                                <li><a href="{{ route('rejoindre') }}"  class="text-sm text-gray-300 hover:text-white transition-colors">Nous rejoindre</a></li>
                            </ul>
                        </div>
                        <div class="mt-10 md:mt-0">
                            <h3 class="text-sm font-semibold text-white">Contact</h3>
                            <ul role="list" class="mt-6 space-y-4">
                                <li class="flex items-start gap-x-3 text-sm text-gray-300">
                                    <span class="text-blue-500">📍</span>
                                    <span>Gbedjromédé 1, Rue 6.132.carré/ Ilot 1209, Maison FANOU, C. ANTOINE<br>Cotonou, Bénin</span>
                                </li>
                                <li class="flex items-center gap-x-3 text-sm text-gray-300">
                                    <span class="text-blue-500">📞</span>
                                    <a href="tel:+2290196501084" class="hover:text-white transition-colors">+229 01 96 50 10 84</a>
                                </li>
                                <li class="flex items-center gap-x-3 text-sm text-gray-300">
                                    <span class="text-blue-500">✉️</span>
                                    <a href="mailto:contact@verredeaufraiche.com" class="hover:text-white transition-colors">contact@verredeaufraiche.com</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-16 border-t border-white/10 pt-8 sm:mt-20 lg:mt-24">
                <p class="text-xs text-gray-400 text-center">&copy; {{ date('Y') }} Verre d'eau fraîche — Tous droits réservés.</p>
            </div>
        </div>
    </footer>


    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 700, once: true, offset: 60 });
    </script>
    @stack('scripts')
</body>
</html>
