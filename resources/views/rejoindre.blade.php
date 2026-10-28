@extends('app')
@section('title', 'Bénévolat et candidatures — Verre d’Eau Fraîche')
@section('meta_description', 'Préparez votre candidature auprès de VEF : compétences, disponibilités, motivations et étapes pour proposer votre aide à l’association.')

@section('content')

<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-blue-900 py-20 sm:py-32 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl mb-6">Rejoignez notre association</h1>
            <p class="text-lg leading-8 text-blue-100 max-w-2xl mx-auto">
                Participez à nos actions humanitaires et contribuez à améliorer la vie des communautés. Votre engagement fait la différence.
            </p>
        </div>
    </section>

    @include('partials.volunteer-introduction')
    <!-- Form Section -->
    <section class="py-16 sm:py-24">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            
            @if(session('success'))
                <div class="rounded-md bg-green-50 p-4 mb-8 border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <form method="POST" action="{{ route('candidature.store') }}" class="p-8 sm:p-12 space-y-12">
                    @csrf
                    
                    <!-- Personal Info -->
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900">Informations personnelles</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Dites-nous en plus sur vous.</p>

                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="nom" class="block text-sm font-medium leading-6 text-gray-900">Nom <span class="text-red-500">*</span></label>
                                <div class="mt-2">
                                    <input type="text" name="nom" id="nom" required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-gray-50 focus:bg-white transition-colors">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="prenom" class="block text-sm font-medium leading-6 text-gray-900">Prénom <span class="text-red-500">*</span></label>
                                <div class="mt-2">
                                    <input type="text" name="prenom" id="prenom" required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-gray-50 focus:bg-white transition-colors">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="dateNaissance" class="block text-sm font-medium leading-6 text-gray-900">Date de naissance <span class="text-red-500">*</span></label>
                                <div class="mt-2">
                                    <input type="date" name="dateNaissance" id="dateNaissance" required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-gray-50 focus:bg-white transition-colors">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">E-mail <span class="text-red-500">*</span></label>
                                <div class="mt-2">
                                    <input type="email" name="email" id="email" required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-gray-50 focus:bg-white transition-colors">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="telephone" class="block text-sm font-medium leading-6 text-gray-900">Téléphone <span class="text-red-500">*</span></label>
                                <div class="mt-2">
                                    <input type="tel" name="telephone" id="telephone" placeholder="+229 90 00 00 00" required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-gray-50 focus:bg-white transition-colors">
                                </div>
                            </div>

                            <div class="col-span-full">
                                <label for="adresse" class="block text-sm font-medium leading-6 text-gray-900">Adresse <span class="text-red-500">*</span></label>
                                <div class="mt-2">
                                    <input type="text" name="adresse" id="adresse" placeholder="Votre adresse complète" required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-gray-50 focus:bg-white transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Engagement Info -->
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900">Votre engagement</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Comment souhaitez-vous nous aider ?</p>

                        <div class="mt-10 space-y-10">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="typeParticipation" class="block text-sm font-medium leading-6 text-gray-900">Type de participation <span class="text-red-500">*</span></label>
                                    <div class="mt-2">
                                        <select id="typeParticipation" name="typeParticipation" required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:max-w-xs sm:text-sm sm:leading-6 bg-gray-50 focus:bg-white transition-colors" x-data @change="$el.value === 'autre' ? document.getElementById('autreTypeGroup').style.display = 'block' : document.getElementById('autreTypeGroup').style.display = 'none'">
                                            <option value="">Sélectionnez une option</option>
                                            <option value="stagiaire">Stagiaire</option>
                                            <option value="benevole">Bénévole</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-3" id="autreTypeGroup" style="display: none;">
                                    <label for="autreType" class="block text-sm font-medium leading-6 text-gray-900">Précisez votre type de participation <span class="text-red-500">*</span></label>
                                    <div class="mt-2">
                                        <input type="text" name="autreType" id="autreType" placeholder="Ex: Partenaire, Consultant, etc." class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-gray-50 focus:bg-white transition-colors">
                                    </div>
                                </div>

                                <div class="col-span-full">
                                    <label for="disponibilites" class="block text-sm font-medium leading-6 text-gray-900">Disponibilités <span class="text-red-500">*</span></label>
                                    <div class="mt-2">
                                        <textarea id="disponibilites" name="disponibilites" maxlength="2000" rows="3" placeholder="Ex: Disponible les week-ends, 2 jours par semaine, etc." required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-gray-50 focus:bg-white transition-colors"></textarea>
                                    </div>
                                </div>
                            </div>

                            <fieldset>
                                <legend class="text-sm font-semibold leading-6 text-gray-900">Préférence d'action <span class="text-red-500">*</span></legend>
                                <div class="mt-6 space-y-6">
                                    <div class="flex items-center gap-x-3">
                                        <input id="action-locale" name="preferenceAction" type="radio" value="locale" required class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600">
                                        <label for="action-locale" class="block text-sm font-medium leading-6 text-gray-900">Locale</label>
                                    </div>
                                    <div class="flex items-center gap-x-3">
                                        <input id="action-nationale" name="preferenceAction" type="radio" value="nationale" required class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600">
                                        <label for="action-nationale" class="block text-sm font-medium leading-6 text-gray-900">Nationale</label>
                                    </div>
                                    <div class="flex items-center gap-x-3">
                                        <input id="action-internationale" name="preferenceAction" type="radio" value="internationale" required class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600">
                                        <label for="action-internationale" class="block text-sm font-medium leading-6 text-gray-900">Internationale</label>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="col-span-full">
                                <label for="motivation" class="block text-sm font-medium leading-6 text-gray-900">Lettre de motivation <span class="text-red-500">*</span></label>
                                <div class="mt-2">
                                    <textarea id="motivation" name="motivation" maxlength="10000" rows="6" placeholder="Expliquez pourquoi vous souhaitez rejoindre notre association et ce que vous pouvez apporter..." required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-gray-50 focus:bg-white transition-colors"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RGPD -->
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="relative flex gap-x-3">
                            <div class="flex h-6 items-center">
                                <input id="rgpd" name="rgpd" type="checkbox" value="1" required class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                            </div>
                            <div class="text-sm leading-6">
                                <label for="rgpd" class="font-medium text-gray-900">Confidentialité des données <span class="text-red-500">*</span></label>
                                <p class="text-gray-500">J’accepte l’utilisation de mes informations pour examiner ma candidature et me contacter. <a href="{{ route('confidentialite') }}" class="font-semibold text-blue-700 underline">Lire la politique de confidentialité</a>.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-x-6">
                        <button type="reset" class="text-sm font-semibold leading-6 text-gray-900 hover:text-blue-600 transition-colors">Réinitialiser</button>
                        <button type="submit" class="rounded-full bg-blue-600 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all transform hover:scale-105">
                            Envoyer ma candidature
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
