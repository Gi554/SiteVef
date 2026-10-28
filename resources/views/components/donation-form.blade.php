@props(['projet' => null, 'amounts' => [10, 20, 50, 100]])
@php
    $available = \App\Services\Kkiapay::available();
@endphp
<form action="{{ route('donation.checkout') }}" method="post" class="mt-8 space-y-6"
      x-data="{ currency: 'eur', custom: '', selected: '', rate: {{ \App\Services\Money::XOF_PER_EUR }},
          get euros() { return this.custom !== '' ? (this.currency === 'xof' ? Number(this.custom) / this.rate : Number(this.custom)) : Number(this.selected); },
          get equivalent() { const euros = Math.round(this.euros * 100) / 100; return euros > 0 && Number.isFinite(euros) ? euros.toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' € ≈ ' + Math.round(euros * this.rate).toLocaleString('fr-FR') + ' FCFA' : ''; }
      }">
    @csrf
    @if(session('error'))<p role="alert" class="text-red-700">{{ session('error') }}</p>@endif
    @if($errors->any())<p role="alert" class="text-red-700">{{ $errors->first() }}</p>@endif
    @if($projet)<input type="hidden" name="projet_id" value="{{ $projet->id }}">@endif
    @if(count($amounts))
    <fieldset>
        <legend class="font-semibold text-gray-900">Choisissez un montant</legend>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
            @foreach($amounts as $amount)
            <label class="cursor-pointer rounded-xl border border-blue-100 bg-blue-50 p-4 text-center">
                <input type="radio" name="amount" value="{{ $amount }}" x-model="selected" @change="custom = ''" class="mr-1">
                <span class="font-bold text-blue-700">{{ number_format($amount, 2, ',', ' ') }} €</span>
                <span class="block text-sm text-gray-600 mt-2">≈ {{ \App\Services\Money::fcfa($amount) }}</span>
            </label>
            @endforeach
        </div>
    </fieldset>
    @endif
    <fieldset>
        <legend class="font-semibold text-gray-900">Ou indiquez un montant libre</legend>
        <div class="grid sm:grid-cols-2 gap-4 mt-4">
            <div>
                <label for="don-currency" class="block text-sm text-gray-700 mb-2">Devise du montant libre</label>
                <select id="don-currency" name="amount_currency" x-model="currency" @change="custom = ''; selected = ''" class="w-full rounded-xl border-gray-300">
                    <option value="eur">Euro (€)</option><option value="xof">Franc CFA (FCFA / XOF)</option>
                </select>
            </div>
            <div>
                <label for="don-custom" class="block text-sm text-gray-700 mb-2">Montant libre <span x-text="currency === 'xof' ? '(FCFA)' : '(€)'">(€)</span></label>
                <input id="don-custom" name="custom_amount" type="number" x-model="custom" @input="selected = ''"
                       min="1" max="100000" step="0.01" :min="currency === 'xof' ? 656 : 1"
                       :max="currency === 'xof' ? 65595700 : 100000" :step="currency === 'xof' ? 1 : 0.01"
                       class="w-full rounded-xl border-gray-300" placeholder="Votre montant">
            </div>
        </div>
    </fieldset>
    <p x-text="equivalent" role="status" aria-live="polite" class="font-semibold text-blue-700"></p>
    <p class="text-sm text-gray-500">1 € = 655,957 FCFA (XOF). Le paiement est effectué en FCFA avec Kkiapay, par Mobile Money ou carte, arrondi au franc. Des frais peuvent être appliqués par le prestataire.</p>
    <p class="text-sm leading-6 text-gray-500">Le site conserve les références utiles au suivi du don ; les informations de paiement sont saisies auprès de Kkiapay. <a href="{{ route('confidentialite') }}#paiements" class="font-semibold text-blue-700 underline">En savoir plus sur vos données</a>.</p>
    @if(!$available)<p class="bg-blue-50 text-blue-800 p-4 rounded-xl">La collecte en ligne n’est pas encore disponible. <a href="{{ url('/#contact') }}" class="underline">Contactez l’association pour contribuer.</a></p>@endif
    <button type="submit" @disabled(!$available) class="bg-blue-600 hover:bg-blue-500 disabled:opacity-50 disabled:cursor-not-allowed text-white px-8 py-4 rounded-full font-bold">Faire un don avec Kkiapay</button>
</form>
