<section class="bg-blue-50 px-6 py-20 sm:py-24" aria-labelledby="participation-title">
    <div class="mx-auto max-w-7xl">
        <p class="text-sm font-bold uppercase tracking-widest text-blue-700">À chacun sa façon d’agir</p>
        <div class="mt-4 grid gap-6 lg:grid-cols-2 lg:items-end"><h2 id="participation-title" class="text-3xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-4xl">Du temps, une idée ou un don.<br>Le premier pas vous appartient.</h2><p class="max-w-xl text-lg leading-8 text-gray-600">Vous n’avez pas besoin de choisir un engagement avant de comprendre comment il fonctionne. Voici trois portes d’entrée pour participer aux actions de Verre d’Eau Fraîche.</p></div>
        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach([
                ['01', 'Soutenir l’association', 'Un don général contribue aux actions de VEF sans être affecté à un projet précis. Le paiement se fait directement avec Kkiapay, sans compte et sans demande d’accord à un administrateur.', route('donation.index'), 'Faire un don général'],
                ['02', 'Choisir une initiative', 'Les fiches de projets permettent de comprendre les objectifs, le statut et les informations de suivi. Lorsqu’une collecte est ouverte, vous pouvez faire un don rattaché au projet choisi.', route('projets.index'), 'Explorer les projets'],
                ['03', 'Apporter une compétence', 'Présentez ce que vous aimez faire, vos disponibilités et la façon dont vous souhaitez contribuer. Une candidature permet à l’équipe de mieux comprendre votre proposition avant d’échanger avec vous.', route('rejoindre'), 'Proposer mon aide'],
            ] as [$number, $title, $text, $href, $label])
                <article class="flex flex-col rounded-2xl border border-blue-100 bg-white p-8"><span class="text-4xl font-extrabold text-blue-200" aria-hidden="true">{{ $number }}</span><h3 class="mt-6 text-xl font-bold text-gray-900">{{ $title }}</h3><p class="mb-8 mt-4 flex-1 leading-7 text-gray-600">{{ $text }}</p><a href="{{ $href }}" class="font-bold text-blue-700 hover:underline">{{ $label }} →</a></article>
            @endforeach
        </div>
        <p class="mt-8 text-center leading-7 text-gray-600">Vous représentez une organisation ou souhaitez organiser une collecte ? <a href="{{ url('/#contact') }}" class="font-bold text-blue-700 underline">Parlons de votre proposition.</a></p>
    </div>
</section>
