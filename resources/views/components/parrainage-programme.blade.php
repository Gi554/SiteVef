@props(['programme'])
<article id="programme-{{ $programme->code }}" class="programme-card scroll-mt-28 flex flex-col overflow-hidden rounded-2xl border border-blue-100 bg-white shadow-sm">
    @if($programme->image)
        <img src="{{ asset($programme->image) }}" alt="{{ $programme->image_alt }}"
             loading="lazy" class="h-64 w-full object-cover">
    @else
        <img src="{{ asset($programme->code === \App\Models\ProgrammeParrainage::ENFANT ? 'Style1/images/ecole.jpg' : 'Style1/images/soins.jpg') }}"
             alt="" loading="lazy" width="800" height="480" class="h-64 w-full object-cover">
    @endif

    <div class="flex flex-1 flex-col p-7 sm:p-10">
        <div class="mb-6 flex items-center gap-4">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                <x-parrainage-icon :programme="$programme->code" />
            </span>
            <p class="text-sm font-semibold text-blue-700">
                {{ $programme->code === \App\Models\ProgrammeParrainage::ENFANT ? 'ÉDUCATION & REPAS' : 'ACCÈS AUX SOINS' }}
            </p>
        </div>

        <h3 class="text-2xl font-bold tracking-tight text-gray-900">{{ $programme->titre }}</h3>
        <p class="mt-3 text-lg leading-7 text-gray-700">{{ $programme->accroche }}</p>

        <div class="programme-card__amount my-7 rounded-xl bg-gray-50 px-5 py-5">
            @if($programme->montant_mensuel_centimes !== null)
                <p class="text-blue-700">
                    <strong class="text-4xl font-extrabold">{{ number_format($programme->montant_mensuel_centimes / 100, $programme->montant_mensuel_centimes % 100 === 0 ? 0 : 2, ',', ' ') }} €</strong>
                    <span class="text-sm font-medium"> / mois</span>
                </p>
                <p class="mt-2 text-sm text-gray-600">≈ {{ \App\Services\Money::fcfa($programme->montant_mensuel_centimes / 100) }} par mois</p>
            @else
                <p class="text-3xl font-bold text-blue-700">Un montant libre</p>
                <p class="mt-2 text-sm text-gray-600">Une contribution chaque mois, à votre mesure.</p>
            @endif
        </div>

        <p class="flex-1 whitespace-pre-line leading-7 text-gray-600">{{ $programme->description }}</p>
        <a href="{{ $programme->contact_url }}"
           class="mt-8 inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-4 text-center text-sm font-bold leading-6 text-white transition-colors hover:bg-blue-700">
            {{ $programme->code === \App\Models\ProgrammeParrainage::ENFANT ? 'Échanger sur le parrainage d’un enfant' : 'Échanger sur le parrainage santé' }}
        </a>
        <p class="mt-4 text-center text-sm leading-6 text-gray-500">Mise en place avec notre équipe. Versements mensuels manuels.</p>
    </div>
</article>
