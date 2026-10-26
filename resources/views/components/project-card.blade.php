@props(['projet'])

<article class="project-card">
    <a href="{{ route('projets.show', $projet->slug) }}" class="project-card__visual" tabindex="-1" aria-hidden="true">
        @if($projet->image)
            <img src="{{ asset($projet->image) }}" alt="" loading="lazy" width="640" height="420">
        @else
            <div class="project-card__placeholder"><span>VERRE D’EAU FRAÎCHE</span><span>Agir ensemble.</span></div>
        @endif
        <x-project-status :status="$projet->statut" />
    </a>
    <div class="project-card__body">
        @if($projet->localisation)
            <p class="project-card__location">{{ $projet->localisation }}</p>
        @endif
        <h3><a href="{{ route('projets.show', $projet->slug) }}">{{ $projet->titre }}</a></h3>
        <p class="project-card__description">{{ $projet->description }}</p>
        <a href="{{ route('projets.show', $projet->slug) }}" class="project-card__link">
            Découvrir le projet <span class="sr-only">{{ $projet->titre }}</span><span aria-hidden="true">↗</span>
        </a>
    </div>
</article>
