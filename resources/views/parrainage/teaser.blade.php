@if($programmesParrainage->isNotEmpty())
<section class="vef-section home-parrainage" aria-labelledby="parrainage-accueil-title">
    <div class="vef-container parrainage-layout">
        <div class="parrainage-visual"><img src="{{ asset('Style1/images/enfantAide.jpg') }}" alt="Illustration de l’accompagnement d’un enfant" loading="lazy"><p class="parrainage-visual__caption">Un parrainage commence par une rencontre et un échange avec notre équipe.</p></div>
        <div>
            <p class="vef-eyebrow">L’envie d’être présent, dans la durée</p>
            <h2 id="parrainage-accueil-title" class="vef-display vef-section-title">Un lien.<br><span>Qui compte.</span></h2>
            <p class="vef-lead">Contribuer à la scolarité et aux repas d’un enfant, ou soutenir les besoins de santé des personnes vulnérables. Découvrez les programmes et préparez votre engagement avec VEF.</p>
            <ul class="programme-links">
                @foreach($programmesParrainage as $programme)
                    <li><a href="{{ route('parrainage.index') }}#programme-{{ $programme->code }}"><x-parrainage-icon :programme="$programme->code" /><span><strong>{{ $programme->titre }}</strong><small>@if($programme->montant_mensuel_centimes !== null){{ number_format($programme->montant_mensuel_centimes / 100, 2, ',', ' ') }} € / mois · ≈ {{ \App\Services\Money::fcfa($programme->montant_mensuel_centimes / 100) }}@else Un montant mensuel libre @endif</small></span><span class="programme-arrow" aria-hidden="true">↗</span></a></li>
                @endforeach
            </ul>
            <a href="{{ route('parrainage.index') }}" class="vef-text-link">Comprendre le parrainage <span aria-hidden="true">↗</span></a>
        </div>
    </div>
</section>
@endif
