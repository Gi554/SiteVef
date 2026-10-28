@extends('app')
@section('title', 'Nos projets humanitaires — Verre d’Eau Fraîche')
@section('content')
<section class="bg-white py-16">
<div class="max-w-7xl mx-auto px-6">
<p class="font-semibold text-blue-700">AGIR ENSEMBLE</p>
<h1 class="text-4xl font-bold mt-3">Nos projets humanitaires</h1>
<p class="mt-6 text-lg text-gray-600 max-w-3xl">Découvrez les initiatives de l’association, leurs objectifs et les actions menées auprès des personnes vulnérables.</p>
<div class="mt-10 grid gap-6 md:grid-cols-3">
    @foreach([
        ['Comprendre l’objectif', 'Chaque fiche rassemble les informations publiées sur le besoin, l’approche proposée et les personnes que le projet souhaite accompagner. Prenez le temps de la lire avant de contribuer.'],
        ['Situer son avancement', 'Les filtres distinguent les projets à venir, les actions en cours et les initiatives réalisées. Le statut décrit l’avancement du projet ; il ne signifie pas nécessairement qu’une collecte est ouverte.'],
        ['Choisir votre contribution', 'Lorsqu’un projet accepte les dons, son formulaire permet de lui affecter directement votre soutien. Vous pouvez aussi contacter l’équipe pour proposer une compétence ou demander une précision.'],
    ] as [$title, $text])<div class="border-l-4 border-blue-500 pl-5"><h2 class="text-lg font-bold text-gray-900">{{ $title }}</h2><p class="mt-3 text-sm leading-7 text-gray-600">{{ $text }}</p></div>@endforeach
</div>
</div>
</section>
<section class="max-w-7xl mx-auto px-6 py-12">
<nav class="flex flex-wrap gap-3 mb-10" aria-label="Filtrer les projets">
@foreach([''=>'Tous les projets','en_cours'=>'En cours','a_venir'=>'À venir','realise'=>'Réalisés'] as $value=>$label)
<a href="{{ route('projets.index', $value ? ['statut'=>$value] : []) }}"
   @if(request('statut','')===$value) aria-current="page" @endif
   class="project-status project-status-filter project-status--{{ $value ?: 'a_venir' }}">
    @if($value)<span class="project-status__marker" aria-hidden="true">{{ $value === 'realise' ? '✓' : '' }}</span>@endif
    {{ $label }}
</a>
@endforeach
</nav>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
@forelse($projets as $projet)<x-project-card :projet="$projet"/>
@empty<p class="text-gray-600">Aucun projet dans cette rubrique pour le moment.</p>
@endforelse
</div>
<div class="mt-10">{{ $projets->links() }}</div>
</section>
<section class="bg-blue-50 px-6 py-16"><div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-2"><x-public-heading eyebrow="Suivre et contribuer" title="Un projet se comprend au-delà de sa collecte.">Les montants affichés reflètent les dons confirmés enregistrés sur le site. Ils ne constituent pas, à eux seuls, un bilan des dépenses ou des résultats du projet. Consultez les informations de suivi disponibles et posez vos questions à l’équipe.</x-public-heading><div class="flex flex-col justify-center gap-4"><a href="{{ route('actualites.index') }}" class="rounded-xl bg-white p-6 font-bold text-blue-800 hover:underline">Voir les nouvelles de l’association →</a><a href="{{ route('faq') }}" class="rounded-xl bg-white p-6 font-bold text-blue-800 hover:underline">Comprendre les dons et les collectes →</a></div></div></section>
@endsection
