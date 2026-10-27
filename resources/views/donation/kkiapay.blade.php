@extends('app')
@section('title', 'Paiement Kkiapay — Verre d’Eau Fraîche')
@section('content')
<section class="bg-gray-50 min-h-screen py-16 px-6">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-xl">
        <h1 class="text-3xl font-bold">Finaliser votre don</h1>
        <p class="mt-4">{{ $donation->projet?->titre ?? 'Soutenir Verre d’Eau Fraîche' }}</p>
        <p class="text-2xl font-semibold mt-4">{{ number_format($donation->amount_xof, 0, ',', ' ') }} FCFA</p>
        <p class="mt-2 text-gray-600">Payez avec Mobile Money ou par carte via Kkiapay. Votre don sera validé après vérification du paiement.</p>
        @if(config('kkiapay.sandbox'))<p class="mt-4 font-semibold">Mode test : aucun paiement réel.</p>@endif
        @if(session('error'))<p role="alert" class="mt-4 text-red-700">{{ session('error') }}</p>@endif
        @if($errors->any())<p role="alert" class="mt-4 text-red-700">{{ $errors->first() }}</p>@endif
        <p id="payment-error" role="alert" class="mt-4 text-red-700" hidden></p>
        <button id="kkiapay-open" type="button" class="mt-6 bg-blue-600 text-white px-8 py-4 rounded-full font-bold">Payer {{ number_format($donation->amount_xof, 0, ',', ' ') }} FCFA</button>
        <form id="kkiapay-confirm" method="post" action="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('donation.kkiapay.confirm', now()->addHours(24), ['donation' => $donation->reference]) }}">
            @csrf
            <details id="kkiapay-recovery" class="mt-6" @if(old('transaction_id') || isset($transactionId)) open @endif>
                <summary class="cursor-pointer font-semibold">Déjà payé ? Vérifier mon don</summary>
                <p class="mt-3 text-gray-600">Si vous avez reçu une facture Kkiapay, indiquez son identifiant de transaction pour vérifier ce paiement sans le refaire.</p>
                <label for="kkiapay-transaction" class="block mt-3">Identifiant de transaction</label>
                <input id="kkiapay-transaction" name="transaction_id" type="text" maxlength="255" required
                       class="mt-2 w-full rounded-xl border-gray-300" value="{{ old('transaction_id', $transactionId ?? '') }}">
                <button class="mt-4 underline" type="submit">Vérifier mon paiement</button>
            </details>
        </form>
        <a href="{{ route('donation.index') }}" class="block mt-6 underline">Revenir au formulaire de don</a>
    </div>
</section>
@endsection
@push('scripts')
<script src="https://cdn.kkiapay.me/k.js"></script>
<script>
    const paymentOptions = {{ Illuminate\Support\Js::from([
        'amount' => (int) $donation->amount_xof,
        'key' => config('kkiapay.public_key'),
        'sandbox' => (bool) config('kkiapay.sandbox'),
        'position' => 'center',
        'theme' => '#2563eb',
        'partnerId' => $donation->reference,
        'data' => ['donation_reference' => $donation->reference],
        'callback' => \Illuminate\Support\Facades\URL::temporarySignedRoute('donation.kkiapay.return', now()->addHours(24), ['donation' => $donation->reference]),
    ]) }};
    const paymentError = document.getElementById('payment-error');
    function showPaymentError(message) {
        paymentError.textContent = message;
        paymentError.hidden = false;
    }
    document.getElementById('kkiapay-open').addEventListener('click', () => {
        if (typeof window.openKkiapayWidget !== 'function') {
            showPaymentError('Kkiapay est indisponible. Rechargez la page pour réessayer.');
            return;
        }
        paymentError.hidden = true;
        window.openKkiapayWidget(paymentOptions);
    });
    let confirmingPayment = false;
    function confirmPayment(response) {
        if (!response?.transactionId || confirmingPayment) return;
        confirmingPayment = true;
        document.getElementById('kkiapay-transaction').value = response.transactionId;
        document.getElementById('kkiapay-recovery').open = true;
        document.getElementById('kkiapay-confirm').requestSubmit();
    }
    if (typeof window.addSuccessListener === 'function') {
        window.addSuccessListener(response => {
            confirmPayment(response);
        });
        window.addFailedListener(() => showPaymentError('Le paiement a échoué. Vous pouvez réessayer.'));
    }
    @if(isset($transactionId))
        confirmPayment({transactionId: {{ Illuminate\Support\Js::from($transactionId) }}});
    @endif
</script>
@endpush
