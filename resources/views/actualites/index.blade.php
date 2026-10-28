@extends('app')
@section('title', 'Actualités — Verre d’Eau Fraîche')
@section('content')<section class="max-w-7xl mx-auto px-6 py-16">
<p class="text-blue-700 font-semibold">SUR LE TERRAIN</p>
<h1 class="text-4xl font-bold mt-3">Actualités de l’association</h1>
<p class="mt-5 text-lg text-gray-600">Suivez nos actions, les avancées des projets et la vie de l’association.</p>
<div class="mt-8 max-w-4xl border-l-4 border-blue-500 pl-6"><p class="leading-8 text-gray-600">Une initiative se raconte aussi dans ses étapes, ses rencontres et ses apprentissages. Cette rubrique rassemble les articles que l’équipe choisit de publier. Les catégories vous aident à explorer les sujets ; les liens présents dans un article permettent de retrouver les projets associés lorsqu’ils sont renseignés.</p></div>
<form method="get" class="my-8 flex flex-wrap gap-3 items-center">
<label for="categorie">Catégorie</label>
<select id="categorie" name="categorie" class="rounded-lg border-gray-300">
<option value="">Toutes les catégories</option>
@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('categorie')==$category->id)>{{ $category->nom }}</option>
@endforeach
</select>
<button class="bg-blue-600 text-white rounded-full px-5 py-2">Filtrer</button>
</form>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
@forelse($articles as $article)<x-article-card :article="$article"/>
@empty<p class="text-gray-600">Aucune actualité publiée dans cette rubrique pour le moment.</p>
@endforelse
</div>
<div class="mt-10">{{ $articles->links() }}</div>
</section>
<section class="border-t border-gray-200 bg-white px-6 py-16"><div class="mx-auto grid max-w-7xl gap-8 md:grid-cols-2"><x-public-heading eyebrow="Faire le lien avec l’action" title="Une actualité vous donne envie de participer ?">Retrouvez les informations complètes d’un projet, les possibilités de contribution et les coordonnées de l’équipe. Vous pouvez aussi poser une question sur un article en indiquant son titre dans votre message.</x-public-heading><div class="flex flex-col justify-center gap-4"><a href="{{ route('projets.index') }}" class="rounded-xl bg-blue-50 p-6 font-bold text-blue-800 hover:underline">Découvrir les projets →</a><a href="{{ url('/#contact') }}" class="rounded-xl bg-blue-50 p-6 font-bold text-blue-800 hover:underline">Écrire à l’association →</a></div></div></section>
@endsection
