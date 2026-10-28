@props(['projet'])
<article class="vef-content-card">
    @if($projet->image)
        <a href="{{ route('projets.show', $projet->slug) }}" class="vef-content-card__image" tabindex="-1" aria-hidden="true"><img src="{{ asset($projet->image) }}" alt="" loading="lazy"></a>
    @endif
    <div class="vef-content-card__body">
        <div><x-project-status :status="$projet->statut" /></div>
        <h3>{{ $projet->titre }}</h3>
        @if($projet->localisation)<p class="vef-content-card__meta">{{ $projet->localisation }}</p>@endif
        <p class="vef-content-card__description">{{ $projet->description }}</p>
        <a href="{{ route('projets.show', $projet->slug) }}" class="pillar-card__link">Découvrir le projet <span class="sr-only">{{ $projet->titre }}</span><span aria-hidden="true">↗</span></a>
    </div>
</article>
