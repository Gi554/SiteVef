@extends('app')

@section('title', "Verre d'Eau Fraîche — Association humanitaire au Bénin")
@section('meta_description', "Association humanitaire Verre d'Eau Fraîche : éducation, santé et autonomisation des personnes vulnérables. Rejoignez-nous ou faites un don.")

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- HERO                                                                    --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('Style1/images/VEF.jpeg') }}" alt="Enfants aidés par VEF"
             class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-gray-900/90"></div>
    </div>

    <div class="relative z-10 text-center w-full px-6 sm:px-8 max-w-4xl mx-auto space-y-6 py-24"
         data-aos="fade-up" data-aos-duration="900">
        <h1 class="text-3xl sm:text-5xl lg:text-7xl font-extrabold text-white tracking-tight leading-tight drop-shadow-lg">
            BIENVENUE À<br>
            <span class="text-blue-400">VERRE D'EAU FRAÎCHE</span>
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl text-gray-200 font-light">
            "Agissons pour chacun, agissons pour tous."
        </p>
        <p class="text-base sm:text-lg text-gray-300 max-w-2xl mx-auto">
            Nous œuvrons chaque jour pour améliorer la vie des plus vulnérables à travers l'éducation, la santé et l'autonomisation.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
            <a href="{{ route('rejoindre') }}"
               class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-full transition-all hover:scale-105 shadow-lg">
                Nous rejoindre
            </a>
            <a href="#services"
               class="px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-gray-900 transition-all hover:scale-105">
                Découvrir nos actions
            </a>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <a href="#about" class="text-white/70 hover:text-white transition-colors">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7-7-7m7 7V3"/>
            </svg>
        </a>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- À PROPOS                                                                --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<section id="about" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">À PROPOS DE NOUS</h2>
            <div class="w-24 h-1 bg-blue-600 mx-auto mt-6 rounded-full"></div>
            <p class="mt-6 text-lg leading-8 text-gray-600 max-w-3xl mx-auto">
                L'Association humanitaire Verre d'Eau Fraîche est une Organisation Non Gouvernementale à but non lucratif,
                apolitique et non confessionnelle. Elle réunit des personnes engagées à venir en aide aux plus faibles :
                enfants, veuves, jeunes et femmes en difficultés.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="group p-8 rounded-2xl bg-gray-50 hover:bg-blue-50 transition-colors duration-300 border border-gray-100 hover:border-blue-100"
                 data-aos="fade-up" data-aos-delay="0">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Notre Mission</h3>
                <p class="text-gray-600 leading-relaxed">
                    Investir dans l'épanouissement, l'éducation et l'avenir des personnes les plus faibles et vulnérables de la société.
                </p>
            </div>

            <div class="group p-8 rounded-2xl bg-gray-50 hover:bg-blue-50 transition-colors duration-300 border border-gray-100 hover:border-blue-100"
                 data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Notre Vision</h3>
                <p class="text-gray-600 leading-relaxed">
                    D'ici 2030, être la meilleure organisation de jeunes qui agit pour transformer les conditions de vie des personnes vulnérables.
                </p>
            </div>

            <div class="group p-8 rounded-2xl bg-gray-50 hover:bg-blue-50 transition-colors duration-300 border border-gray-100 hover:border-blue-100"
                 data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Nos Valeurs</h3>
                <p class="text-gray-600 leading-relaxed">
                    Amour, Vertus, Engagement, Noblesse, Intégrité, Respect, Passion, Tolérance, Loyauté, Entraide et Intelligence.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- COMPTEURS D'IMPACT                                                      --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}


{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- PROJETS EN COURS                                                        --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<section id="services" class="py-24 bg-gray-50"><div class="max-w-7xl mx-auto px-6"><div class="flex flex-wrap gap-6 items-end justify-between mb-12"><div><p class="text-blue-700 font-semibold">NOS ACTIONS</p><h2 class="text-3xl font-bold mt-3">Des projets pour agir ensemble</h2></div><a class="text-blue-700 font-semibold" href="{{ route('projets.index') }}">Voir tous les projets →</a></div><div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">@forelse($projets->where('statut','en_cours')->take(3) as $projet)<x-project-card :projet="$projet"/>@empty<p class="text-gray-600">Retrouvez les initiatives de l’association sur la page de nos projets.</p>@endforelse</div></div></section>
@if($actualites->isNotEmpty())<section class="py-20 bg-white"><div class="max-w-7xl mx-auto px-6"><div class="flex flex-wrap gap-6 items-end justify-between mb-10"><h2 class="text-3xl font-bold">Les dernières actualités</h2><a href="{{ route('actualites.index') }}" class="text-blue-700 font-semibold">Toutes les actualités →</a></div><div class="grid md:grid-cols-3 gap-8">@foreach($actualites as $article)<x-article-card :article="$article"/>@endforeach</div></div></section>@endif


{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- NOS ACTIONS (Galerie)                                                   --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<section id="gallery" class="py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">NOS ACTIONS</h2>
                <p class="mt-4 text-lg text-gray-600">Découvrez nos domaines d'intervention prioritaires.</p>
            </div>
            <a href="{{ route('donation.index') }}"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg">
                Faire un don
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 h-auto md:h-[500px]">
            <div class="relative group overflow-hidden rounded-2xl h-64 md:h-full md:col-span-2 lg:col-span-1" data-aos="fade-right">
                <img src="{{ asset('Style1/images/ecole.jpg') }}" alt="Education"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/55 transition-colors duration-300"></div>
                <div class="absolute bottom-0 left-0 p-6 text-white translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <h3 class="text-xl font-bold mb-2">Éducation pour tous</h3>
                    <p class="text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">Accès à l'éducation pour tous les enfants.</p>
                </div>
            </div>
            <div class="relative group overflow-hidden rounded-2xl h-64 md:h-full md:col-span-2 lg:col-span-2" data-aos="fade-up">
                <img src="{{ asset('Style1/images/entrepreneuriat-mots.jpg') }}" alt="Entrepreneuriat"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/55 transition-colors duration-300"></div>
                <div class="absolute bottom-0 left-0 p-8 text-white translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <h3 class="text-2xl font-bold mb-2">Entrepreneuriat</h3>
                    <p class="text-base opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">Encourager l'esprit d'initiative des personnes vulnérables.</p>
                </div>
            </div>
            <div class="relative group overflow-hidden rounded-2xl h-64 md:h-full md:col-span-1" data-aos="fade-left">
                <img src="{{ asset('Style1/images/soins.jpg') }}" alt="Santé"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/55 transition-colors duration-300"></div>
                <div class="absolute bottom-0 left-0 p-6 text-white translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <h3 class="text-xl font-bold mb-2">Santé</h3>
                    <p class="text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">Santé mentale et physique des communautés.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- PRINCIPAUX MOYENS D'ACTION                                              --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">PRINCIPAUX MOYENS D'ACTION</h2>
            <div class="w-24 h-1 bg-blue-600 mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="prose prose-lg max-w-4xl mx-auto text-gray-700 leading-relaxed text-center mb-10" data-aos="fade-up" data-aos-delay="100">
            <p>
                Fondée sur une Noblesse d'esprit et une Intégrité absolue, notre mission est de transformer l'Engagement en actes
                concrets pour le Respect de la dignité humaine. Nous agissons avec Amour au cœur des communautés les plus vulnérables,
                là où la Passion et l'Entraide deviennent des moteurs de changement.
            </p>
            <p class="mt-4">
                Notre action ne s'arrête pas au soutien moral : nous bâtissons l'avenir par l'Éducation, en prenant en charge
                la scolarité et les fournitures des enfants délaissés. De la santé mentale aux soins physiques urgents,
                chaque don de kit alimentaire ou de matériel est une pierre posée pour protéger les plus faibles.
            </p>
        </div>

        <div class="text-center" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ url('/#contact') }}"
               class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold rounded-full text-white bg-blue-600 hover:bg-blue-700 transition-all transform hover:scale-105 shadow-lg">
                Vous voulez en savoir plus ? Contactez-nous !
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- PARRAINAGE                                                              --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
@include('parrainage.teaser')

{{-- DEVENEZ BÉNÉVOLE --}}
<section id="volunteer" class="py-24 bg-blue-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">DEVENEZ BÉNÉVOLE</h2>
            <p class="mt-4 text-lg text-blue-100 max-w-2xl mx-auto">
                Rejoignez notre équipe et participez activement au changement.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/10 hover:bg-white/20 transition-all duration-300 text-center group"
                 data-aos="fade-up" data-aos-delay="0">
                <div class="w-16 h-16 mx-auto bg-blue-500 rounded-full flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <span class="text-3xl">🎓</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-4">Stage</h3>
                <p class="text-blue-100 mb-8">Participez à nos programmes éducatifs et contribuez à l'avenir des enfants.</p>
                <a href="{{ route('rejoindre') }}"
                   class="inline-block px-6 py-2 border border-white text-white rounded-full hover:bg-white hover:text-blue-900 transition-colors font-medium">
                    Postuler
                </a>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/10 hover:bg-white/20 transition-all duration-300 text-center group"
                 data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 mx-auto bg-green-500 rounded-full flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <span class="text-3xl">🌍</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-4">Mission</h3>
                <p class="text-blue-100 mb-8">Aidez-nous à apporter des soins de santé aux communautés vulnérables.</p>
                <a href="{{ route('rejoindre') }}"
                   class="inline-block px-6 py-2 border border-white text-white rounded-full hover:bg-white hover:text-blue-900 transition-colors font-medium">
                    Postuler
                </a>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/10 hover:bg-white/20 transition-all duration-300 text-center group"
                 data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 mx-auto bg-purple-500 rounded-full flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <span class="text-3xl">🤝</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-4">Service Civique</h3>
                <p class="text-blue-100 mb-8">Soutenez l'autonomisation économique des entrepreneurs locaux.</p>
                <a href="{{ route('rejoindre') }}"
                   class="inline-block px-6 py-2 border border-white text-white rounded-full hover:bg-white hover:text-blue-900 transition-colors font-medium">
                    Postuler
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- TÉMOIGNAGES                                                             --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
@if(isset($temoignages) && $temoignages->where('actif', true)->count() > 0)
<section id="temoignages" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">ILS TÉMOIGNENT</h2>
            <div class="w-24 h-1 bg-blue-600 mx-auto mt-6 rounded-full"></div>
            <p class="mt-4 text-lg text-gray-600">Ce que disent ceux qui nous ont rejoints.</p>
        </div>

        <div id="carousel-temoignages" class="relative">
            <!-- Slides -->
            <div class="overflow-hidden">
                @foreach($temoignages->where('actif', true)->values() as $index => $temoignage)
                <div class="carousel-slide max-w-3xl mx-auto text-center px-4 transition-opacity duration-500"
                     data-index="{{ $index }}"
                     style="{{ $index === 0 ? '' : 'display:none;' }}">
                    <svg class="w-12 h-12 text-blue-200 mx-auto mb-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="text-xl md:text-2xl text-gray-700 italic leading-relaxed mb-8">
                        "{{ $temoignage->texte }}"
                    </p>
                    <p class="font-bold text-gray-900 text-lg">{{ $temoignage->auteur }}</p>
                    @if($temoignage->role)
                        <p class="text-blue-600 text-sm mt-1">{{ $temoignage->role }}</p>
                    @endif
                </div>
                @endforeach
            </div>

            @if($temoignages->where('actif', true)->count() > 1)
            <div class="flex justify-center items-center gap-6 mt-12">
                <!-- Précédent -->
                <button onclick="carouselPrev()" class="w-10 h-10 rounded-full bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <!-- Dots -->
                <div id="carousel-dots" class="flex gap-2">
                    @foreach($temoignages->where('actif', true)->values() as $i => $t)
                    <button onclick="carouselGoTo({{ $i }})"
                            class="carousel-dot h-2 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-blue-600 w-6' : 'bg-gray-300 w-2' }}">
                    </button>
                    @endforeach
                </div>
                <!-- Suivant -->
                <button onclick="carouselNext()" class="w-10 h-10 rounded-full bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- SOUTENEZ-NOUS                                                           --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<section id="support" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl shadow-2xl overflow-hidden" data-aos="zoom-in">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="p-12 lg:p-20 flex flex-col justify-center">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl mb-6">Soutenez-nous</h2>
                    <p class="text-blue-100 text-lg leading-relaxed mb-10">
                        Votre soutien est essentiel pour mener à bien nos missions humanitaires. Chaque contribution,
                        aussi modeste soit-elle, a un impact réel sur la vie des personnes que nous aidons. Ensemble, nous transformons des vies.
                    </p>
                    <div>
                        <a href="{{ route('donation.index') }}"
                           class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-full text-blue-700 bg-white hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            Donnez le sourire 
                        </a>
                    </div>
                </div>
                <div class="relative h-64 lg:h-auto">
                    <img src="{{ asset('Style1/images/enfantAide.jpg') }}" alt="Enfant aidé" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-blue-900/20"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- CONTACT & CARTE                                                         --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<section id="contact" class="scroll-mt-24 py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Carte & infos -->
            <div class="space-y-8" data-aos="fade-right">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl mb-4">Contactez-nous</h2>
                    <p class="text-gray-600 text-lg">Vous souhaitez devenir bénévole, partenaire ou simplement en savoir plus ?</p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 text-blue-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Notre adresse</h3>
                            <p class="text-gray-600">Gbedjromédé 1, Rue 6.132.carré/ Ilot 1209, Maison FANOU, C. ANTOINE, Cotonou, Bénin</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 text-blue-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Téléphone</h3>
                            <a href="tel:+2290196501084" class="text-gray-600 hover:text-blue-600 transition-colors">+229 01 96 50 10 84</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 text-blue-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Email</h3>
                            <a href="mailto:contact@verredeaufraiche.com" class="text-gray-600 hover:text-blue-600 transition-colors">contact@verredeaufraiche.com</a>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 h-64 lg:h-80">
                    <iframe class="w-full h-full border-0" title="Localisation Cotonou"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
                            src="https://maps.google.com/maps?q=6.4969,2.3158&z=14&output=embed">
                    </iframe>
                </div>
            </div>

            <!-- Formulaire de contact -->
            <div class="bg-white rounded-2xl shadow-lg p-8 lg:p-12 border border-gray-100" data-aos="fade-left">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h3>

                @if(session('success'))
                    <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-200 flex items-center gap-3">
                        <svg class="h-5 w-5 text-green-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if($contactProgramme)
                    <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-900">
                        <p class="font-semibold">Votre demande concerne : {{ $contactProgramme->titre }}</p>
                        <p class="mt-2">Posez vos questions à notre équipe. Les modalités du parrainage seront définies ensemble.</p>
                        <a href="{{ route('parrainage.index') }}" class="mt-2 inline-block underline">Revoir les programmes</a>
                    </div>
                @endif

                <form method="POST" action="{{ route('message') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                            <input type="text" id="nom" name="nom" required
                                   value="{{ old('nom') }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                   placeholder="Votre nom">
                            @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required
                                   value="{{ old('prenom') }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                   placeholder="Votre prénom">
                            @error('prenom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" required
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                               placeholder="vous@example.com">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="objet" class="block text-sm font-medium text-gray-700 mb-2">Objet</label>
                        <input type="text" id="objet" name="objet" required
                               value="{{ old('objet', $contactProgramme?->contact_subject) }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                               placeholder="Sujet de votre message">
                        @error('objet')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea id="message" name="message" rows="5" required
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                  placeholder="Écrivez votre message ici...">{{ old('message') }}</textarea>
                        @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-lg transition-all transform hover:scale-[1.02] shadow-md hover:shadow-lg">
                        Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

{{-- ── Modale détail projet ──────────────────────────────────────────────────── --}}
@push('scripts')
<script>
// ── Compteurs animés ─────────────────────────────────────────────────────────
(function () {
    const counters = document.querySelectorAll('.counter');
    if (!counters.length) return;

    const animate = (el) => {
        const target = parseInt(el.dataset.target, 10);
        const step   = target / (1800 / 16);
        let current  = 0;
        const tick = () => {
            current = Math.min(current + step, target);
            el.textContent = Math.floor(current).toLocaleString('fr-FR');
            if (current < target) requestAnimationFrame(tick);
        };
        tick();
    };

    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { animate(e.target); obs.unobserve(e.target); } });
    }, { threshold: 0.3 });

    counters.forEach(c => obs.observe(c));
})();

// ── Carousel témoignages (JS vanilla) ────────────────────────────────────────
(function () {
    const slides = document.querySelectorAll('.carousel-slide');
    const dots   = document.querySelectorAll('.carousel-dot');
    if (!slides.length) return;

    let current = 0;

    function goTo(index) {
        slides[current].style.display = 'none';
        dots[current]?.classList.replace('bg-blue-600', 'bg-gray-300');
        dots[current]?.classList.replace('w-6', 'w-2');

        current = (index + slides.length) % slides.length;

        slides[current].style.display = '';
        slides[current].style.opacity = '0';
        setTimeout(() => { slides[current].style.opacity = '1'; }, 10);

        dots[current]?.classList.replace('bg-gray-300', 'bg-blue-600');
        dots[current]?.classList.replace('w-2', 'w-6');
    }

    // Expose les fonctions pour les boutons
    window.carouselGoTo  = goTo;
    window.carouselNext  = () => goTo(current + 1);
    window.carouselPrev  = () => goTo(current - 1);

    // Auto-rotation toutes les 5 secondes
    if (slides.length > 1) {
        setInterval(() => window.carouselNext(), 5000);
    }
})();
</script>
@endpush
