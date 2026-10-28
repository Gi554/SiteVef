@props(['eyebrow', 'title'])
<div {{ $attributes->class(['max-w-3xl']) }}>
    <p class="text-sm font-bold uppercase tracking-widest text-blue-700">{{ $eyebrow }}</p>
    <h2 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-4xl">{{ $title }}</h2>
    @if($slot->isNotEmpty())<div class="mt-5 text-lg leading-8 text-gray-600">{{ $slot }}</div>@endif
</div>
