@extends('app')

@section('title', 'Parrainage enfant et santé — Verre d’Eau Fraîche')
@section('meta_description', 'Découvrez nos programmes de parrainage : scolarité et repas d’un enfant, ou soutien mensuel à la santé. Échangez avec notre équipe pour vous engager.')
@section('og_image', asset('Style1/images/VEF.jpeg'))

@section('content')
    <section class="sponsorship-intro overflow-hidden bg-white">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-6 py-14 sm:py-20 lg:grid-cols-2 lg:gap-16 lg:px-8">
            <div>
                <p class="text-sm font-bold tracking-widest text-blue-700">LES PROGRAMMES DE PARRAINAGE</p>
                <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-5xl">
                    Grandir, apprendre,<br>
                    <span class="text-blue-600">se soigner.</span>
                </h1>
                <p class="mt-5 text-xl font-medium text-gray-700">Des besoins essentiels. Un engagement qui compte.</p>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Pour certains enfants, aller à l’école et manger à sa faim reste un défi quotidien.
                    Pour d’autres personnes vulnérables, accéder aux soins est tout aussi difficile.
                </p>
                <p class="mt-4 leading-7 text-gray-600">
                    Avec Verre d’Eau Fraîche, apportez un soutien régulier à travers nos deux programmes.
                    Notre équipe est là pour vous les présenter et préparer votre engagement avec vous.
                </p>
                <a href="#programmes" class="mt-8 inline-flex rounded-full bg-blue-600 px-7 py-4 font-bold text-white transition-colors hover:bg-blue-700">
                    Découvrir les programmes <span class="ml-3" aria-hidden="true">↓</span>
                </a>
                <p class="mt-4 text-sm text-gray-500">Un premier échange pour vous informer, à votre rythme.</p>
            </div>
            <figure>
                <img src="{{ asset('Style1/images/VEF.jpeg') }}"
                     alt="Enfants et membres de Verre d’Eau Fraîche réunis lors d’une action de l’association"
                     width="1600" height="1066" fetchpriority="high"
                     class="aspect-[4/3] w-full rounded-2xl object-cover shadow-lg">
                <figcaption class="mt-4 flex items-center gap-3 text-sm text-gray-500">
                    <span class="h-px w-8 bg-blue-200" aria-hidden="true"></span>
                    Agissons pour chacun, agissons pour tous.
                </figcaption>
            </figure>
        </div>
    </section>

    <section id="programmes" class="sponsorship-programmes scroll-mt-24 bg-gray-50 py-16 sm:py-20" aria-labelledby="programmes-title">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mb-10 max-w-2xl">
                <p class="text-sm font-bold tracking-widest text-blue-700">DEUX FAÇONS D’ÊTRE PRÉSENT</p>
                <h2 id="programmes-title" class="mt-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Choisissez votre engagement.</h2>
                <p class="mt-4 text-lg leading-8 text-gray-600">Un accompagnement individuel pour un enfant, ou un soutien aux besoins de santé des personnes vulnérables.</p>
            </div>
            <div class="grid gap-8 lg:grid-cols-2">
                @foreach($programmes as $programme)
                    <x-parrainage-programme :programme="$programme" />
                @endforeach
            </div>
            <p class="mt-7 text-center text-sm leading-6 text-gray-500">
                Chaque parrainage se prépare avec l’association. Aucun prélèvement automatique.
            </p>
        </div>
    </section>

    <section class="bg-white py-16 sm:py-20" aria-labelledby="commencer-title">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 id="commencer-title" class="text-3xl font-bold tracking-tight text-gray-900">Tout commence par une rencontre.</h2>
            <p class="mt-4 max-w-2xl text-lg leading-8 text-gray-600">Nous prenons le temps de répondre à vos questions avant de définir les modalités de votre soutien.</p>
            <ol class="mt-10 grid gap-8 md:grid-cols-3">
                @foreach([
                    ['titre' => 'Découvrez votre programme', 'texte' => 'Prenez connaissance de son objectif et de la contribution mensuelle proposée.'],
                    ['titre' => 'Échangez avec notre équipe', 'texte' => 'Parlez-nous de votre envie de parrainer. Notre équipe répond à vos questions et vous présente le programme.'],
                    ['titre' => 'Préparez votre engagement', 'texte' => 'Nous précisons ensemble la mise en place du parrainage, les versements et le suivi proposé.'],
                ] as $etape)
                    <li class="rounded-2xl border border-gray-100 bg-gray-50 p-7">
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-800" aria-hidden="true">{{ $loop->iteration }}</span>
                        <h3 class="mt-5 text-lg font-bold text-gray-900">{{ $etape['titre'] }}</h3>
                        <p class="mt-3 leading-7 text-gray-600">{{ $etape['texte'] }}</p>
                    </li>
                @endforeach
            </ol>
            <div class="mt-10 rounded-2xl border border-blue-100 bg-blue-50 p-6 sm:p-8">
                <p class="font-semibold text-blue-900">Une prise de contact, avant tout engagement.</p>
                <p class="mt-2 leading-7 text-blue-900">Votre message permet de vous informer. Les modalités du parrainage sont définies avec notre équipe avant sa mise en place.</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-3xl px-6 py-16 sm:py-20" aria-labelledby="faq-title">
        <h2 id="faq-title" class="text-3xl font-bold tracking-tight text-gray-900">Vos premières questions</h2>
        <div class="mt-8 divide-y divide-gray-200">
            <details class="py-5">
                <summary class="cursor-pointer text-lg font-semibold text-gray-900">Le versement est-il automatique ?</summary>
                <p class="mt-4 leading-7 text-gray-600">Les contributions sont renouvelées chaque mois par le parrain, selon les modalités convenues avec l’association. Aucun prélèvement automatique n’est mis en place.</p>
            </details>
            @foreach($programmes as $programme)
                <details class="py-5">
                    <summary class="cursor-pointer text-lg font-semibold text-gray-900">Quel montant prévoir pour « {{ $programme->titre }} » ?</summary>
                    <p class="mt-4 leading-7 text-gray-600">
                        @if($programme->montant_mensuel_centimes !== null)
                            La contribution est de {{ number_format($programme->montant_mensuel_centimes / 100, 2, ',', ' ') }} € par mois,
                            soit environ {{ \App\Services\Money::fcfa($programme->montant_mensuel_centimes / 100) }}.
                            Elle contribue à la scolarité et aux repas d’un enfant accompagné par l’association.
                        @else
                            Vous choisissez le montant de votre contribution mensuelle. Notre équipe vous explique comment organiser ce soutien à la santé.
                        @endif
                    </p>
                </details>
            @endforeach
            <details class="py-5">
                <summary class="cursor-pointer text-lg font-semibold text-gray-900">Comment le parrainage d’un enfant est-il organisé ?</summary>
                <p class="mt-4 leading-7 text-gray-600">L’équipe vous explique le fonctionnement du programme et organise l’accompagnement individuel après échange avec vous. Les dossiers personnels des enfants ne sont pas publiés sur le site.</p>
            </details>
        </div>
    </section>

    <section class="bg-blue-900 py-16 text-white sm:py-20">
        <div class="mx-auto max-w-3xl px-6 text-center">
            <p class="text-sm font-bold tracking-widest text-blue-200">FAISONS LE PREMIER PAS ENSEMBLE</p>
            <h2 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">Une question, une envie d’aider ?</h2>
            <p class="mt-5 text-lg leading-8 text-blue-100">Choisissez un engagement que vous pourrez tenir dans la durée. Notre équipe est à votre écoute pour en parler.</p>
            <a href="{{ route('index') }}#contact" class="mt-8 inline-flex rounded-full bg-white px-7 py-4 font-bold text-blue-900 transition-colors hover:bg-blue-50">Contacter l’association</a>
        </div>
    </section>
@endsection
