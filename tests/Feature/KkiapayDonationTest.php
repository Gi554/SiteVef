<?php

use App\Models\Donation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

beforeEach(function () {
    config()->set([
        'kkiapay.public_key' => 'public-test',
        'kkiapay.private_key' => 'private-test',
        'kkiapay.secret_key' => 'secret-test',
        'kkiapay.webhook_secret' => 'webhook-test',
        'kkiapay.sandbox' => true,
    ]);
    Http::preventStrayRequests();
});

function pendingKkiapayDonation(): Donation
{
    return Donation::create([
        'reference' => (string) Str::uuid(), 'provider' => 'kkiapay',
        'amount_xof' => 1000, 'amount_centimes' => 152, 'currency' => 'eur', 'status' => 'pending',
    ]);
}

function verifiedKkiapayPayment(Donation $donation, array $overrides = []): array
{
    return array_replace([
        'status' => 'SUCCESS', 'amount' => 1000, 'partnerId' => $donation->reference,
    ], $overrides);
}

test('checkout uses kkiapay by default and keeps the exact XOF amount', function () {
    $this->get(route('donation.index'))->assertOk()->assertSee('Mobile Money ou carte');
    $response = $this->post(route('donation.checkout'), [
        'custom_amount' => 1000, 'amount_currency' => 'xof',
    ]);
    $donation = Donation::sole();
    expect($donation->amount_xof)->toBe(1000)
        ->and($donation->amount_centimes)->toBe(152)
        ->and($donation->status)->toBe('pending');
    $response->assertRedirect();
    $this->get($response->headers->get('Location'))->assertOk()
        ->assertSee('1 000 FCFA')->assertDontSee('private-test')->assertDontSee('secret-test');
    Http::assertNothingSent();
});

test('missing keys do not create a donation or start a payment', function () {
    config(['kkiapay.private_key' => null]);
    $this->post(route('donation.checkout'), ['provider' => 'kkiapay', 'amount' => 10])
        ->assertSessionHas('error');
    expect(Donation::count())->toBe(0);
    Http::assertNothingSent();
});

test('the donation form and routes only offer kkiapay', function () {
    $this->get(route('donation.index'))->assertOk()->assertSee('Faire un don avec Kkiapay')->assertDontSee('Stripe');
    expect(\Illuminate\Support\Facades\Route::has('stripe.webhook'))->toBeFalse();
    $this->postJson(route('donation.checkout'), ['provider' => 'stripe', 'amount' => 10])->assertUnprocessable();
    expect(Donation::count())->toBe(0);
    Http::assertNothingSent();
});

test('a kkiapay project donation updates only its project after confirmation', function () {
    $project = \App\Models\Projet::create([
        'titre' => 'École', 'description' => 'Soutien scolaire', 'slug' => 'ecole',
        'publication' => 'publie', 'statut' => 'en_cours', 'dons_actifs' => true,
    ]);
    $this->post(route('donation.checkout'), [
        'projet_id' => $project->id, 'custom_amount' => 5000, 'amount_currency' => 'xof',
    ])->assertRedirect();
    $donation = Donation::sole();
    expect($donation->provider)->toBe('kkiapay')->and($donation->projet_id)->toBe($project->id)
        ->and($project->fresh()->collected)->toBe(0);
    Http::fake(['api-sandbox.kkiapay.me/*' => Http::response(verifiedKkiapayPayment($donation, ['amount' => 5000]))]);
    $payload = ['event' => 'transaction.success', 'transactionId' => 'tx-project', 'partnerId' => $donation->reference];
    $headers = ['x-kkiapay-secret' => 'webhook-test'];
    $this->postJson(route('kkiapay.webhook'), $payload, $headers)->assertOk();
    $this->postJson(route('kkiapay.webhook'), $payload, $headers)->assertOk();
    expect($project->fresh()->collected)->toBe(762);
    // A general donation cannot increase this project's collection.
    $general = pendingKkiapayDonation();
    $general->update(['status' => 'confirmed', 'confirmed_at' => now()]);
    expect($project->fresh()->collected)->toBe(762);
});

test('closed and unpublished projects cannot collect donations', function (string $publication, bool $enabled) {
    $project = \App\Models\Projet::create([
        'titre' => 'Collecte fermée', 'description' => 'Texte', 'slug' => 'ferme',
        'publication' => $publication, 'statut' => 'en_cours', 'dons_actifs' => $enabled,
    ]);
    $this->post(route('donation.checkout'), ['projet_id' => $project->id, 'amount' => 10])->assertNotFound();
    expect(Donation::count())->toBe(0);
    Http::assertNothingSent();
})->with([['brouillon', true], ['publie', false]]);

test('unsigned and expired payment pages are rejected', function () {
    $donation = pendingKkiapayDonation();
    $this->get(route('donation.kkiapay', $donation->reference))->assertForbidden();
    $this->get(URL::temporarySignedRoute('donation.kkiapay', now()->subMinute(), ['donation' => $donation->reference]))->assertForbidden();
    $this->post(route('donation.kkiapay.confirm', $donation->reference), ['transaction_id' => 'forged'])->assertForbidden();
    expect($donation->fresh()->status)->toBe('pending');
    Http::assertNothingSent();
});

test('the browser return is verified with the provider credentials', function () {
    $donation = pendingKkiapayDonation();
    Http::fake(['api-sandbox.kkiapay.me/*' => Http::response(verifiedKkiapayPayment($donation))]);
    $url = URL::temporarySignedRoute('donation.kkiapay.confirm', now()->addHour(), ['donation' => $donation->reference]);
    $this->post($url, ['transaction_id' => 'tx-verified'])->assertRedirect(route('donation.success'));
    expect($donation->fresh()->status)->toBe('confirmed')->and($donation->fresh()->kkiapay_transaction_id)->toBe('tx-verified');
    Http::assertSent(fn ($request) => $request->url() === 'https://api-sandbox.kkiapay.me/api/v1/transactions/status'
        && $request->hasHeader('X-PRIVATE-KEY', 'private-test')
        && $request['transactionId'] === 'tx-verified');
});

test('unpaid mismatched and unbound transactions cannot confirm a donation', function (array $overrides) {
    $donation = pendingKkiapayDonation();
    Http::fake(['api-sandbox.kkiapay.me/*' => Http::response(verifiedKkiapayPayment($donation, $overrides))]);
    $url = URL::temporarySignedRoute('donation.kkiapay.confirm', now()->addHour(), ['donation' => $donation->reference]);
    $this->postJson($url, ['transaction_id' => 'tx-invalid'])->assertUnprocessable();
    expect($donation->fresh()->status)->toBe('pending');
})->with([
    'pending' => [['status' => 'PENDING']],
    'failed' => [['status' => 'FAILED']],
    'wrong amount' => [['amount' => 999]],
    'fractional amount' => [['amount' => 1000.1]],
    'wrong currency' => [['currency' => 'EUR']],
    'wrong donation' => [['partnerId' => 'another-donation']],
    'missing reference' => [['partnerId' => null]],
]);

test('webhook authentication rejects forged notifications', function () {
    $donation = pendingKkiapayDonation();
    $this->postJson(route('kkiapay.webhook'), [
        'event' => 'transaction.success', 'transactionId' => 'forged', 'partnerId' => $donation->reference,
    ], ['x-kkiapay-secret' => 'wrong'])->assertUnauthorized();
    expect($donation->fresh()->status)->toBe('pending');
    Http::assertNothingSent();
});

test('webhooks are idempotent and late failures cannot reverse a confirmation', function () {
    $donation = pendingKkiapayDonation();
    Http::fake(['api-sandbox.kkiapay.me/*' => Http::response(verifiedKkiapayPayment($donation))]);
    $payload = ['event' => 'transaction.success', 'transactionId' => 'tx-1', 'partnerId' => $donation->reference];
    $headers = ['x-kkiapay-secret' => 'webhook-test'];
    $this->postJson(route('kkiapay.webhook'), $payload, $headers)->assertOk();
    $confirmedAt = $donation->fresh()->confirmed_at;
    $this->travel(5)->minutes();
    $this->postJson(route('kkiapay.webhook'), $payload, $headers)->assertOk();
    $this->postJson(route('kkiapay.webhook'), array_replace($payload, ['event' => 'transaction.failed']), $headers)->assertOk();
    expect($donation->fresh()->status)->toBe('confirmed')
        ->and($donation->fresh()->confirmed_at->equalTo($confirmedAt))->toBeTrue();
});

test('a transaction cannot fund two donations', function () {
    $first = pendingKkiapayDonation();
    $first->update(['kkiapay_transaction_id' => 'tx-used', 'status' => 'confirmed', 'confirmed_at' => now()]);
    $second = pendingKkiapayDonation();
    Http::fake(['api-sandbox.kkiapay.me/*' => Http::response(verifiedKkiapayPayment($second))]);
    $url = URL::temporarySignedRoute('donation.kkiapay.confirm', now()->addHour(), ['donation' => $second->reference]);
    $this->postJson($url, ['transaction_id' => 'tx-used'])->assertUnprocessable();
    expect($second->fresh()->status)->toBe('pending');
});

test('provider outages keep donations pending and let webhooks retry', function () {
    $donation = pendingKkiapayDonation();
    Http::fake(['api-sandbox.kkiapay.me/*' => Http::response([], 503)]);
    $url = URL::temporarySignedRoute('donation.kkiapay.confirm', now()->addHour(), ['donation' => $donation->reference]);
    $this->post($url, ['transaction_id' => 'tx-retry'])->assertSessionHas('error')->assertSessionHasInput('transaction_id', 'tx-retry');
    $this->postJson(route('kkiapay.webhook'), [
        'event' => 'transaction.success', 'transactionId' => 'tx-retry', 'partnerId' => $donation->reference,
    ], ['x-kkiapay-secret' => 'webhook-test'])->assertServerError();
    expect($donation->fresh()->status)->toBe('pending');
});

test('the signed callback accepts the provider transaction parameter without confirming on GET', function () {
    $donation = pendingKkiapayDonation();
    $url = URL::temporarySignedRoute('donation.kkiapay.return', now()->addHour(), ['donation' => $donation->reference]);
    $this->get($url.'&transaction_id=tx-callback')->assertOk()->assertSee('tx-callback');
    expect($donation->fresh()->status)->toBe('pending');
    Http::assertNothingSent();
    $this->get(route('donation.kkiapay.return', $donation->reference).'?transaction_id=tx-forged')->assertForbidden();
    $this->get($url.'&transaction_id=tx-callback&other=forged')->assertForbidden();
});

test('a receipt can reconcile a donation only through provider verification', function () {
    $donation = pendingKkiapayDonation();
    Http::fake(['api-sandbox.kkiapay.me/*' => Http::response(verifiedKkiapayPayment($donation))]);
    $this->artisan('donations:verify-kkiapay', ['reference' => $donation->reference, 'transaction' => 'tx-reconciled'])->assertSuccessful();
    expect($donation->fresh()->status)->toBe('confirmed')
        ->and($donation->fresh()->kkiapay_transaction_id)->toBe('tx-reconciled');
});
