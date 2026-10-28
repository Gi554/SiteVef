<section id="contact" class="home-contact scroll-mt-24 py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Carte & infos -->
            <div class="space-y-8">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl mb-4">Contactez-nous</h2>
                    <p class="text-gray-600 text-lg">Vous souhaitez devenir bénévole, partenaire ou simplement en savoir plus ?</p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 text-blue-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Notre adresse</h3>
                            <p class="text-gray-600">Gbedjromédé 1, Rue 6.132.carré/ Ilot 1209, Maison FANOU, C. ANTOINE, Cotonou, Bénin</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 text-blue-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Téléphone</h3>
                            <a href="tel:+2290196501084" class="text-gray-600 hover:text-blue-600 transition-colors">+229 01 96 50 10 84</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 text-blue-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Email</h3>
                            <a href="mailto:contact@verredeaufraiche.com" class="text-gray-600 hover:text-blue-600 transition-colors">contact@verredeaufraiche.com</a>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-8">
                    <p class="font-bold text-blue-950">Préparons votre venue à Cotonou.</p>
                    <p class="mt-3 leading-7 text-gray-600">Avant de vous déplacer, contactez l’équipe pour convenir d’un rendez-vous et confirmer les indications d’accès.</p>
                    <a href="https://www.google.com/maps/search/?api=1&amp;query=Gbedjromede+Cotonou+Benin" target="_blank" rel="noopener noreferrer" class="mt-5 inline-block font-bold text-blue-700 underline">Ouvrir le quartier dans Google Maps ↗</a>
                    <p class="mt-3 text-sm leading-6 text-gray-500">La carte externe s’ouvre uniquement si vous choisissez ce lien.</p>
                </div>
            </div>

            <!-- Formulaire de contact -->
            <div class="bg-white rounded-2xl shadow-lg p-8 lg:p-12 border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h3>

                @if(session('success'))
                    <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-200 flex items-center gap-3">
                        <svg class="h-5 w-5 text-green-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if($contactProgramme)
                    <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-900">
                        <p class="font-semibold">Votre demande concerne : {{ $contactProgramme->titre }}</p>
                        <p class="mt-2">Posez vos questions à notre équipe. Les modalités du parrainage seront définies ensemble.</p>
                        <a href="{{ route('parrainage.index') }}" class="mt-2 inline-block underline">Revoir les programmes</a>
                    </div>
                @endif

                <form method="POST" action="{{ route('message') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                            <input type="text" id="nom" name="nom" required
                                   value="{{ old('nom') }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                   placeholder="Votre nom">
                            @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required
                                   value="{{ old('prenom') }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                   placeholder="Votre prénom">
                            @error('prenom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" required
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                               placeholder="vous@example.com">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="objet" class="block text-sm font-medium text-gray-700 mb-2">Objet</label>
                        <input type="text" id="objet" name="objet" required
                               value="{{ old('objet', $contactProgramme?->contact_subject) }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                               placeholder="Sujet de votre message">
                        @error('objet')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea id="message" name="message" rows="5" required
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 focus:bg-white transition-colors"
                                  placeholder="Écrivez votre message ici...">{{ old('message') }}</textarea>
                        @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <p class="text-sm leading-6 text-gray-500">Vos informations permettent à l’équipe de traiter votre demande et de vous répondre. <a href="{{ route('confidentialite') }}" class="font-semibold text-blue-700 underline">Comprendre l’utilisation de mes données</a>.</p>
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-lg transition-all transform hover:scale-[1.02] shadow-md hover:shadow-lg">
                        Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
