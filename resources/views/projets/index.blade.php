@extends('app')
@section('title', 'Nos projets humanitaires — Verre d’Eau Fraîche')
@section('meta_description', 'Découvrez les projets de Verre d’Eau Fraîche au Bénin : éducation, santé et autonomisation. Suivez nos actions et trouvez comment les soutenir.')

@section('content')
    <section class="page-intro" aria-labelledby="projets-title">
        <div class="site-container intro-grid">
            <div>
                <p class="eyebrow">AU BÉNIN, ENSEMBLE</p>
                <h1 id="projets-title" class="display-title">Des actions concrètes.<br><span>Des vies qui changent.</span></h1>
                <p class="intro-copy">L’éducation, la santé et l’autonomie ouvrent de nouvelles perspectives. Découvrez les projets que nous construisons avec les personnes que nous accompagnons.</p>
                <a href="#liste-projets" class="site-button">Explorer nos projets <span aria-hidden="true">↓</span></a>
            </div>
            <figure class="intro-photo">
                <img src="{{ asset('Style1/images/VEF.jpeg') }}" alt="Enfants et équipe de l’association réunis lors d’une action" width="1600" height="1066" fetchpriority="high">
                <figcaption>Sur le terrain, au plus près des besoins.</figcaption>
            </figure>
        </div>
    </section>

    <section id="liste-projets" class="site-container project-list" aria-labelledby="liste-title">
        <div class="section-heading">
            <div>
                <p class="eyebrow">NOS INITIATIVES</p>
                <h2 id="liste-title">Chaque projet est un pas en avant.</h2>
            </div>
            <p class="result-count">{{ $projets->total() }} {{ $projets->total() > 1 ? 'projets' : 'projet' }}</p>
        </div>
        <nav class="project-filters" aria-label="Filtrer les projets">
            @foreach(['' => 'Tous les projets', 'en_cours' => 'En cours', 'a_venir' => 'À venir', 'realise' => 'Réalisés'] as $value => $label)
                <a href="{{ route('projets.index', $value ? ['statut' => $value] : []) }}"
                   @if(request('statut', '') === $value) aria-current="page" @endif
                   class="project-filter">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($projets as $projet)
                <x-project-card :projet="$projet" />
            @empty
                <div class="empty-state md:col-span-2 lg:col-span-3">
                    <h3>Les prochaines actions se préparent.</h3>
                    <p>Aucun projet dans cette rubrique pour le moment. Découvrez nos autres initiatives ou échangez avec notre équipe.</p>
                    <a href="{{ route('index') }}#contact" class="site-button">Nous contacter <span aria-hidden="true">→</span></a>
                </div>
            @endforelse
        </div>
        <div class="mt-10">{{ $projets->links() }}</div>
    </section>

    <x-engagement-banner />
@endsection
