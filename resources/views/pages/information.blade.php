@extends('app')
@section('title', $content['label'].' — Verre d’Eau Fraîche')
@section('meta_description', $content['intro'])
@section('content')
<header class="border-b border-blue-100 bg-blue-50 px-6 py-16 sm:py-20"><div class="mx-auto max-w-7xl">
    <nav aria-label="Fil d’Ariane" class="text-sm text-blue-800"><a href="{{ route('index') }}" class="underline">Accueil</a><span aria-hidden="true" class="mx-2">/</span>{{ $content['label'] }}</nav>
    <p class="mt-10 text-sm font-bold uppercase tracking-widest text-blue-700">{{ $content['label'] }}</p>
    <h1 class="mt-4 max-w-4xl text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">{{ $content['title'] }}</h1>
    <p class="mt-6 max-w-3xl text-lg leading-8 text-gray-600">{{ $content['intro'] }}</p>
    <p class="mt-6 text-sm text-gray-500">Mise à jour éditoriale : 28 octobre 2026</p>
</div></header>
<div class="mx-auto grid max-w-7xl gap-12 px-6 py-14 lg:grid-cols-4 lg:gap-16">
    <aside><nav aria-label="Sommaire de la page" class="rounded-2xl border border-gray-200 bg-white p-6 lg:sticky lg:top-28"><p class="font-bold text-gray-900">Sur cette page</p><ol class="mt-5 space-y-4 text-sm leading-6">@foreach($content['sections'] as $section)<li><a href="#{{ $section['id'] }}" class="text-gray-600 hover:text-blue-700">{{ $loop->iteration }}. {{ $section['title'] }}</a></li>@endforeach</ol></nav></aside>
    <article class="space-y-12 lg:col-span-3">
        @foreach($content['sections'] as $section)
            <section id="{{ $section['id'] }}" class="scroll-mt-28 border-b border-gray-200 pb-10">
                <h2 class="text-2xl font-bold text-gray-900">{{ $section['title'] }}</h2>
                @foreach($section['paragraphs'] as $paragraph)<p class="mt-5 leading-8 text-gray-600">{{ $paragraph }}</p>@endforeach
                @if($page === 'mentions-legales' && $section['id'] === 'publication')
                    <dl class="mt-6 space-y-3 rounded-xl bg-blue-50 p-6 text-sm leading-6">
                        @foreach(['publication_director' => 'Responsable de publication', 'registration' => 'Référence d’enregistrement', 'hosting_name' => 'Hébergeur', 'hosting_address' => 'Adresse de l’hébergeur', 'hosting_contact' => 'Contact de l’hébergeur'] as $key => $label)
                            <div><dt class="font-semibold text-blue-950">{{ $label }}</dt><dd class="text-gray-600">{{ config('vef.'.$key) ?: 'Information à confirmer par l’association.' }}</dd></div>
                        @endforeach
                    </dl>
                @endif
                @if($page === 'confidentialite' && $section['id'] === 'droits')<a href="https://service.apdp.bj/actualites/mes_droits" class="mt-5 inline-block font-semibold text-blue-700 underline" rel="noopener noreferrer" target="_blank">Consulter les informations de l’APDP du Bénin ↗</a>@endif
            </section>
        @endforeach
        <div class="rounded-2xl bg-blue-950 p-7 text-white"><h2 class="text-xl font-bold">Besoin d’une précision ?</h2><p class="mt-3 leading-7 text-blue-100">Pour une question sur le site ou vos informations, écrivez à <a href="mailto:contact@verredeaufraiche.com" class="font-semibold text-white underline">contact@verredeaufraiche.com</a>. Indiquez la page ou la référence de votre demande, sans communiquer de secret de paiement.</p></div>
        <nav aria-label="Autres informations utiles" class="flex flex-wrap gap-4 text-sm font-semibold text-blue-700">@foreach(['confidentialite' => 'Confidentialité', 'cookies' => 'Cookies', 'mentions-legales' => 'Mentions légales', 'conditions-utilisation' => 'Conditions d’utilisation', 'faq' => 'Questions fréquentes'] as $route => $label)<a href="{{ route($route) }}" class="underline">{{ $label }}</a>@endforeach</nav>
    </article>
</div>
@endsection
