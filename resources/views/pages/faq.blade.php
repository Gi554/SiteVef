@extends('app')
@section('title', 'Questions fréquentes — Verre d’Eau Fraîche')
@section('meta_description', 'Dons généraux, projets, paiement Kkiapay, parrainage et bénévolat : les réponses aux questions pour participer aux actions de VEF.')
@section('content')
<section class="bg-blue-950 px-6 py-20 text-white"><div class="mx-auto max-w-7xl"><p class="text-sm font-bold uppercase tracking-widest text-blue-200">On vous répond</p><h1 class="mt-5 max-w-3xl text-4xl font-extrabold sm:text-6xl">Avant d’agir, faisons le point.</h1><p class="mt-6 max-w-2xl text-lg leading-8 text-blue-100">Comprendre un don, préparer une candidature ou choisir un programme : retrouvez les repères utiles avant votre premier pas avec VEF.</p></div></section>
<section class="mx-auto grid max-w-7xl gap-12 px-6 py-16 lg:grid-cols-3">
    <div><h2 class="text-2xl font-bold text-gray-900">Les questions que vous vous posez</h2><p class="mt-5 leading-8 text-gray-600">Une réponse manque ou votre situation est particulière ? Présentez-la à l’équipe. Une question sur une transaction doit être accompagnée de sa référence, jamais de votre code confidentiel.</p><a href="{{ url('/#contact') }}" class="mt-6 inline-block font-semibold text-blue-700 underline">Contacter l’association →</a></div>
    <div class="space-y-4 lg:col-span-2">@foreach(config('public_content.faq') as $item)<details class="group rounded-2xl border border-gray-200 bg-white p-6 open:border-blue-300 open:bg-blue-50/50"><summary class="cursor-pointer text-lg font-bold leading-7 text-gray-900">{{ $item['question'] }}</summary><p class="mt-5 leading-8 text-gray-600">{{ $item['answer'] }}</p></details>@endforeach</div>
</section>
<x-public-next-step />
@endsection
