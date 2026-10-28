@props(['eyebrow', 'title'])
<div {{ $attributes->class(['public-heading', 'max-w-3xl']) }}>
    <p class="vef-eyebrow text-blue-700">{{ $eyebrow }}</p>
    <h2 class="vef-display vef-section-title text-gray-900">{{ $title }}</h2>
    @if($slot->isNotEmpty())<div class="mt-5 text-lg leading-8 text-gray-600">{{ $slot }}</div>@endif
</div>
