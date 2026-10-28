@props(['article'])
<article class="vef-content-card">
    @if($article->image)<div class="vef-content-card__image"><img src="{{ asset($article->image) }}" alt="{{ $article->image_alt ?? $article->titre }}" loading="lazy"></div>@endif
    <div class="vef-content-card__body">
        <p class="vef-content-card__meta"><time datetime="{{ $article->published_at?->toDateString() }}">{{ $article->published_at?->format('d/m/Y') }}</time>@if($article->category) · {{ $article->category->nom }} @endif</p>
        <h3>{{ $article->titre }}</h3>
        <p class="vef-content-card__description">{{ $article->extrait }}</p>
        <a href="{{ route('actualites.show', $article->slug) }}" class="pillar-card__link">Lire l’article <span class="sr-only">{{ $article->titre }}</span><span aria-hidden="true">↗</span></a>
    </div>
</article>
