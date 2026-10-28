@extends('app')
@section('title', 'Notre association et nos engagements — Verre d’Eau Fraîche')
@section('meta_description', 'Découvrez VEF : une association humanitaire au Bénin engagée pour l’éducation, la santé et l’autonomie des personnes vulnérables. Comprenez nos actions et les façons de participer.')
@section('content')
<section class="overflow-hidden bg-blue-950 text-white"><div class="mx-auto grid max-w-7xl lg:grid-cols-2">
    <div class="px-6 py-16 lg:px-10 lg:py-24"><p class="text-sm font-bold uppercase tracking-widest text-blue-200">Verre d’Eau Fraîche · Bénin</p><h1 class="mt-6 text-4xl font-extrabold leading-tight tracking-tight sm:text-6xl">La solidarité commence par une attention à l’autre.</h1><p class="mt-7 text-lg leading-8 text-blue-100">Une possibilité d’apprendre, un besoin de santé, une envie de construire son autonomie : notre engagement prend son sens dans les réalités des personnes que nous souhaitons accompagner.</p><a href="{{ route('projets.index') }}" class="mt-8 inline-block rounded-full bg-white px-7 py-4 font-bold text-blue-900 hover:bg-blue-50">Découvrir nos projets →</a></div>
    <img src="{{ asset('Style1/images/VEF.jpeg') }}" alt="Image de présentation de l’association Verre d’Eau Fraîche" class="h-80 w-full object-cover lg:h-full" fetchpriority="high">
</div></section>
<section class="mx-auto grid max-w-7xl gap-10 px-6 py-16 sm:py-24 lg:grid-cols-3">
    <x-public-heading eyebrow="Qui nous sommes" title="Un engagement humain, ancré au Bénin." />
    <div class="space-y-5 text-lg leading-8 text-gray-600 lg:col-span-2"><p>Verre d’Eau Fraîche est une association humanitaire à but non lucratif, apolitique et non confessionnelle, implantée à Cotonou. Elle réunit des personnes qui souhaitent agir auprès des publics vulnérables, notamment les enfants, les jeunes, les veuves et les femmes en difficulté.</p><p>Ses domaines d’intervention se complètent : l’éducation pour ouvrir des perspectives, la santé pour prendre soin des personnes, et l’autonomisation pour soutenir la capacité à agir. Cette présentation donne les grandes orientations ; les fiches de projets précisent les initiatives effectivement publiées par l’équipe.</p><p>La solidarité peut prendre plusieurs formes. Contribuer financièrement, consacrer du temps, proposer une compétence ou ouvrir un dialogue entre organisations sont autant de points de départ pour échanger avec VEF.</p></div>
</section>
<section class="bg-white px-6 py-16 sm:py-24"><div class="mx-auto max-w-7xl">
    <x-public-heading eyebrow="Nos domaines d’action" title="Trois leviers pour élargir les possibilités." />
    <div class="mt-12 grid gap-8 md:grid-cols-3">
        @foreach([
            ['01', 'Éducation', 'ecole.jpg', 'Apprendre, être accompagné, se projeter.', 'L’accès à l’apprentissage ne se limite pas à une inscription scolaire. Les besoins peuvent aussi concerner le matériel, l’accompagnement et les conditions permettant aux enfants et aux jeunes de poursuivre leur parcours. Les projets éducatifs détaillent les objectifs et les contributions possibles.'],
            ['02', 'Santé', 'sante-illustration.webp', 'Prendre soin, avec une attention aux plus vulnérables.', 'Les difficultés de santé peuvent fragiliser une famille et ses possibilités d’avancer. Ce domaine fait partie des priorités de VEF. Le programme de parrainage santé permet d’ouvrir une discussion avec l’équipe sur les besoins et l’organisation d’un soutien adapté.'],
            ['03', 'Autonomie', 'autonomisationfemmes.jpg', 'Soutenir les capacités et les initiatives.', 'L’autonomisation concerne la possibilité de développer des compétences, de prendre des initiatives et de construire son propre chemin. VEF inscrit l’accompagnement des femmes et des jeunes dans cette perspective, en lien avec ses projets et les échanges avec les communautés.'],
        ] as [$number, $title, $image, $lead, $text])
            <article class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-50"><img src="{{ asset('Style1/images/'.$image) }}" alt="Illustration du domaine {{ mb_strtolower($title) }}" loading="lazy" class="h-56 w-full object-cover"><div class="p-7"><p class="text-sm font-bold text-blue-700">{{ $number }} / {{ $title }}</p><h3 class="mt-4 text-2xl font-bold leading-8 text-gray-900">{{ $lead }}</h3><p class="mt-5 leading-7 text-gray-600">{{ $text }}</p></div></article>
        @endforeach
    </div>
</div></section>
<section class="mx-auto max-w-7xl px-6 py-16 sm:py-24">
    <x-public-heading eyebrow="Les repères de notre engagement" title="Aider ne se résume pas à un montant.">Comprendre, respecter et rendre l’action lisible sont des repères utiles pour chaque personne qui souhaite contribuer.</x-public-heading>
    <div class="mt-12 grid gap-8 md:grid-cols-3">
        @foreach([
            ['La dignité avant tout', 'Une personne ne se réduit pas à sa difficulté. L’aide et la communication qui l’entoure doivent préserver le respect des personnes, de leur histoire et de leur vie privée.'],
            ['Un engagement à votre mesure', 'Vous pouvez commencer par une question, une participation ponctuelle ou une proposition. Les missions et les modalités de collaboration se précisent avec l’équipe, selon les besoins et vos possibilités.'],
            ['Des informations à consulter', 'Un statut de projet, un objectif et un montant collecté ne disent pas la même chose. Nous vous invitons à lire les fiches, consulter les actualités et poser vos questions avant de vous engager.'],
        ] as [$title, $text])<article class="border-t-4 border-blue-600 bg-white p-7"><h3 class="text-xl font-bold text-gray-900">{{ $title }}</h3><p class="mt-5 leading-8 text-gray-600">{{ $text }}</p></article>@endforeach
    </div>
</section>
<section class="bg-blue-50 px-6 py-16"><div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-2"><div><p class="font-bold text-blue-700">UNE ASSOCIATION, DES PERSONNES</p><h2 class="mt-4 text-3xl font-bold text-gray-900">Rencontrer celles et ceux qui font vivre VEF.</h2><p class="mt-5 leading-8 text-gray-600">La page de l’équipe présente les membres dont les profils ont été publiés. Pour une question, une collaboration ou une candidature, passez par nos coordonnées de contact et les formulaires dédiés.</p></div><div class="flex flex-col justify-center gap-4"><a href="{{ route('equipe') }}" class="rounded-xl border border-blue-200 bg-white p-6 font-bold text-blue-900 hover:border-blue-500">Découvrir l’équipe →</a><a href="{{ route('faq') }}" class="rounded-xl border border-blue-200 bg-white p-6 font-bold text-blue-900 hover:border-blue-500">Lire les réponses aux questions fréquentes →</a></div></div></section>
<x-public-next-step />
@endsection
