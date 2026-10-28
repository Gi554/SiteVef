@extends('app')
@section('title', 'Verre d’Eau Fraîche — Agir ensemble au Bénin')
@section('meta_description', 'Éducation, santé, autonomie : découvrez Verre d’Eau Fraîche, ses projets au Bénin et votre façon de contribuer. Don, bénévolat et parrainage.')

@section('content')
<section id="hero" class="home-hero" aria-labelledby="home-title">
    <picture>
        <source media="(max-width: 767px)" srcset="{{ asset('Style1/images/header1.jpg') }}">
        <img class="home-hero__photo" src="{{ asset('Style1/images/VEF.jpeg') }}" alt="Une rencontre autour des actions de l’association Verre d’Eau Fraîche" fetchpriority="high">
    </picture>
    <div class="vef-container home-hero__content">
        <p class="vef-eyebrow">Au Bénin, avec vous.</p>
        <h1 id="home-title" class="vef-display">Agir ensemble.<span>Pour demain.</span></h1>
        <div class="home-hero__bottom">
            <div>
                <p class="home-hero__lead">Avec VEF, faisons grandir les solidarités au Bénin. Éducation, santé, autonomie : un peu de votre temps, une idée ou un don peuvent être le début d’une action commune.</p>
                <div class="home-hero__buttons">
                    <a href="#gallery" class="vef-button vef-button--white">Découvrir nos actions <span aria-hidden="true">↗</span></a>
                    <a href="{{ route('donation.index') }}" class="vef-button vef-button--glass">Faire un don <span aria-hidden="true">↗</span></a>
                </div>
            </div>
            <div class="hero-seal" aria-hidden="true"><span>✳</span><p>Le pouvoir<br>du collectif.</p></div>
        </div>
    </div>
</section>
<div class="mission-band" aria-label="Nos domaines d’action"><div><span>ÉDUCATION</span><i aria-hidden="true">✳</i><span>SANTÉ</span><i aria-hidden="true">✳</i><span>AUTONOMIE</span></div></div>

<section id="about" class="vef-section">
    <div class="vef-container home-intro">
        <div><p class="vef-eyebrow">Verre d’Eau Fraîche</p><h2 class="vef-display vef-section-title">Solidaires.<br><span>Tout simplement.</span></h2></div>
        <div class="home-intro__body">
            <p>Une possibilité d’apprendre. Un besoin de santé. Une envie de construire son autonomie. Derrière chaque action, il y a des personnes et des chemins à accompagner.</p>
            <p>Implantée à Cotonou, notre association humanitaire réunit des personnes qui souhaitent agir auprès des publics vulnérables, notamment les enfants, les jeunes et les femmes en difficulté. Apolitique et non confessionnelle, VEF fait de la solidarité une affaire collective.</p>
            <a href="{{ route('association') }}" class="vef-text-link">Mieux connaître l’association <span aria-hidden="true">↗</span></a><br>
            <span class="intro-signature">Agissons pour chacun, agissons pour tous.</span>
        </div>
    </div>
</section>

<section id="gallery" class="vef-section home-pillars" aria-labelledby="actions-title">
    <div class="vef-container">
        <div class="vef-section-head"><div><p class="vef-eyebrow">Ce qui nous mobilise</p><h2 id="actions-title" class="vef-display vef-section-title">Des actions.<br><span>Des possibilités nouvelles.</span></h2></div><p>Trois domaines qui se complètent, avec la même attention portée aux personnes et à leurs réalités.</p></div>
        <div class="pillar-grid">
            @foreach([
                ['Éducation', 'Grandir.', 'ecole.jpg', 'Ouvrir des perspectives par l’apprentissage, les conditions de scolarité et l’accompagnement des enfants et des jeunes.', route('parrainage.index').'#programme-enfant', 'Le parrainage d’un enfant'],
                ['Santé', 'Prendre soin.', 'sante-illustration.webp', 'Porter une attention aux besoins de santé des personnes vulnérables et préparer un soutien adapté avec l’équipe.', route('parrainage.index').'#programme-sante', 'Le programme santé'],
                ['Autonomie', 'Avancer.', 'autonomisationfemmes.jpg', 'Soutenir les capacités, les compétences et les initiatives des femmes et des jeunes pour construire leur propre chemin.', route('projets.index'), 'Découvrir les initiatives'],
            ] as [$domain, $title, $image, $description, $href, $label])
                <article class="pillar-card">
                    <div class="pillar-card__image"><img src="{{ asset('Style1/images/'.$image) }}" alt="Illustration du domaine {{ mb_strtolower($domain) }}" loading="lazy"><span class="pillar-card__label">{{ $domain }}</span></div>
                    <div class="pillar-card__body"><h3 class="vef-display">{{ $title }}</h3><p>{{ $description }}</p><a href="{{ $href }}" class="pillar-card__link">{{ $label }} <span aria-hidden="true">↗</span></a></div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="vef-section" aria-labelledby="agir-title">
    <div class="vef-container">
        <div class="vef-section-head"><div><p class="vef-eyebrow">Le premier pas vous appartient</p><h2 id="agir-title" class="vef-display vef-section-title">Comment<br><span>agir avec nous ?</span></h2></div><p>Pas besoin d’avoir toutes les réponses pour commencer. Prenez le temps de découvrir l’association et de trouver la contribution qui vous ressemble.</p></div>
        <div class="step-grid">
            <article class="step-card"><span class="step-card__number">01. Je découvre</span><h3 class="vef-display">Comprendre<br>avant d’agir.</h3><p>Explorez les projets, leurs objectifs et les informations de suivi. Une question sur une initiative ? L’équipe est là pour en parler.</p><a href="{{ route('projets.index') }}" class="vef-button vef-button--white">Voir les projets <span aria-hidden="true">↗</span></a></article>
            <article class="step-card step-card--light"><span class="step-card__number">02. Je participe</span><h3 class="vef-display">Un savoir-faire.<br>Du temps à offrir.</h3><p>Présentez vos compétences, vos disponibilités et vos motivations. Les modalités de votre engagement se construisent ensuite avec l’association.</p><a href="{{ route('rejoindre') }}" class="vef-button vef-button--blue">Proposer mon aide <span aria-hidden="true">↗</span></a></article>
            <article class="step-photo"><img src="{{ asset('Style1/images/devenirBenevole.jpg') }}" alt="Illustration de l’engagement bénévole" loading="lazy"><div class="step-photo__body"><p class="vef-eyebrow">03. Je soutiens</p><h3 class="vef-display">Un geste<br>à votre mesure.</h3><a href="{{ route('donation.index') }}" class="vef-button vef-button--white">Faire un don <span aria-hidden="true">↗</span></a></div></article>
        </div>
    </div>
</section>

<section id="services" class="vef-section home-projects" aria-labelledby="projects-title">
    <div class="vef-container">
        <div class="vef-section-head"><div><p class="vef-eyebrow">Les initiatives de VEF</p><h2 id="projects-title" class="vef-display vef-section-title">L’action prend<br><span>forme ici.</span></h2></div><a href="{{ route('projets.index') }}" class="vef-text-link">Tous les projets <span aria-hidden="true">↗</span></a></div>
        @if($projets->isNotEmpty())
            <div class="content-card-grid">@foreach($projets->take(3) as $projet)<x-project-card :projet="$projet" />@endforeach</div>
        @else
            <div class="content-empty"><div><h3>Comprendre les projets, étape par étape.</h3><p>Les fiches publiées par l’équipe rassemblent les objectifs, le statut et les informations de suivi disponibles. Pour connaître les besoins actuels ou proposer votre contribution, échangez avec VEF.</p></div><a href="{{ url('/#contact') }}" class="vef-button vef-button--blue">Échanger avec l’équipe <span aria-hidden="true">↗</span></a></div>
        @endif
    </div>
</section>

<section id="support" class="vef-section home-choices" aria-labelledby="choices-title">
    <div class="vef-container">
        <div class="vef-section-head"><div><p class="vef-eyebrow">À chacun sa façon d’agir</p><h2 id="choices-title" class="vef-display vef-section-title">Le bon geste.<br><span>À votre mesure.</span></h2></div></div>
        <div class="choice-grid">
            <article class="choice-card"><span class="choice-card__tag">Une initiative précise</span><h3 class="vef-display">Un projet.</h3><p>Vous choisissez l’action que vous souhaitez soutenir, lorsqu’une collecte est ouverte.</p><ul><li>Un objectif à découvrir</li><li>Un don rattaché au projet</li><li>Des informations de suivi sur sa fiche</li></ul><a href="{{ route('projets.index') }}" class="vef-button vef-button--white">Choisir un projet <span aria-hidden="true">↗</span></a></article>
            <article class="choice-card choice-card--featured"><span class="choice-card__tag">Direct · Sans compte</span><h3 class="vef-display">Un don.</h3><p>Vous soutenez l’action de VEF dans son ensemble, sans affectation à un projet précis.</p><ul><li>Le montant qui vous convient</li><li>Mobile Money ou carte via Kkiapay</li><li>Une confirmation du paiement vérifiée</li></ul><a href="{{ route('donation.index') }}" class="vef-button vef-button--blue">Faire un don général <span aria-hidden="true">↗</span></a></article>
            <article class="choice-card"><span class="choice-card__tag">Vos compétences comptent</span><h3 class="vef-display">Du temps.</h3><p>Vous proposez votre aide et préparez votre participation avec notre équipe.</p><ul><li>Une expérience à partager</li><li>Des disponibilités à préciser</li><li>Un premier échange pour se connaître</li></ul><a href="{{ route('rejoindre') }}" class="vef-button vef-button--white">Nous rejoindre <span aria-hidden="true">↗</span></a></article>
        </div>
    </div>
</section>

@include('parrainage.teaser')

@if($temoignages->isNotEmpty())
<section id="temoignages" class="vef-section">
    <div class="vef-container"><div class="vef-section-head"><div><p class="vef-eyebrow">Une aventure humaine</p><h2 class="vef-display vef-section-title">Des voix.<br><span>Des engagements.</span></h2></div><p>Des témoignages publiés par l’association, pour laisser la parole à celles et ceux qui la font vivre.</p></div><div class="testimony-grid">
        @foreach($temoignages->take(3) as $temoignage)<figure class="testimony-card"><span aria-hidden="true">“</span><blockquote>{{ $temoignage->texte }}</blockquote><figcaption><strong>{{ $temoignage->auteur }}</strong>{{ $temoignage->role }}</figcaption></figure>@endforeach
    </div></div>
</section>
@endif

@if($actualites->isNotEmpty())
<section class="vef-section"><div class="vef-container"><div class="vef-section-head"><div><p class="vef-eyebrow">La vie de l’association</p><h2 class="vef-display vef-section-title">Les nouvelles<br><span>du collectif.</span></h2></div><a href="{{ route('actualites.index') }}" class="vef-text-link">Toutes les actualités <span aria-hidden="true">↗</span></a></div><div class="content-card-grid">@foreach($actualites as $article)<x-article-card :article="$article" />@endforeach</div></div></section>
@endif

@include('partials.home-questions')
@include('partials.home-contact')
@endsection
