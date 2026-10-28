<section class="vef-section home-questions" aria-labelledby="home-faq-title">
    <div class="vef-container questions-layout">
        <div><p class="vef-eyebrow">On vous répond</p><h2 id="home-faq-title" class="vef-display vef-section-title">Avant de<br><span>faire le pas.</span></h2><p class="vef-lead">Don général, projet ou parrainage : chaque démarche a son fonctionnement. Quelques réponses pour choisir en confiance.</p><a href="{{ route('faq') }}" class="vef-text-link">Toutes les questions fréquentes <span aria-hidden="true">↗</span></a></div>
        <div class="question-list">@foreach(array_slice(config('public_content.faq'), 1, 4) as $item)<details><summary>{{ $item['question'] }}</summary><p>{{ $item['answer'] }}</p></details>@endforeach</div>
    </div>
</section>
