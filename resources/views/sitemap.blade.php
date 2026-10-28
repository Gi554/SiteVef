{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach([route('index'),route('projets.index'),route('actualites.index'),route('equipe'),route('rejoindre'),route('parrainage.index')] as $url)<url><loc>{{ $url }}</loc></url>@endforeach
@foreach(['association', 'faq', 'confidentialite', 'cookies', 'mentions-legales', 'conditions-utilisation', 'donation.index'] as $route)<url><loc>{{ route($route) }}</loc></url>@endforeach
@foreach($projets as $projet)<url><loc>{{ route('projets.show',$projet->slug) }}</loc><lastmod>{{ $projet->updated_at->toAtomString() }}</lastmod></url>@endforeach
@foreach($articles as $article)<url><loc>{{ route('actualites.show',$article->slug) }}</loc><lastmod>{{ $article->updated_at->toAtomString() }}</lastmod></url>@endforeach
</urlset>
