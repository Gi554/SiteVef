<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Projet;
use App\Models\User;
use App\Models\Visite;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

// Le framework saute normalement CSRF pendant les tests : ici, on le réactive.
class EnforcedCsrfForRouteTests extends ValidateCsrfToken
{
    protected function runningUnitTests()
    {
        return false;
    }
}

beforeEach(function () {
    config([
        'auth.registration_enabled' => false,
        'kkiapay.public_key' => 'public-test', 'kkiapay.private_key' => 'private-test',
        'kkiapay.secret_key' => 'secret-test', 'kkiapay.webhook_secret' => 'webhook-test',
        'kkiapay.sandbox' => true,
    ]);
    Http::preventStrayRequests();
});

function routeSecurityDonation(): Donation
{
    return Donation::create([
        'reference' => (string) Str::uuid(), 'provider' => 'kkiapay',
        'amount_xof' => 5000, 'amount_centimes' => 762, 'currency' => 'eur', 'status' => 'pending',
    ]);
}

test('old administration and deletion paths have no active endpoint', function () {
    $this->get('/administrateur')->assertNotFound();
    $this->deleteJson('/posts/1')->assertNotFound();
    $this->postJson('/administrateur/enregistrer-projet', [])->assertNotFound();
    expect(Route::has('posts.destroy'))->toBeFalse();
});

test('public registration is closed and cannot grant privileges', function () {
    $this->get('/register')->assertNotFound();
    $this->post('/register', [
        'name' => 'Intrus', 'email' => 'intrus@example.test', 'password' => 'Password!123456',
        'password_confirmation' => 'Password!123456', 'is_admin' => true,
    ])->assertNotFound();
    expect(User::count())->toBe(0);
    $this->assertGuest();
});

test('every administration endpoint rejects guests and ordinary accounts', function () {
    $project = Projet::create(['titre' => 'Projet', 'slug' => 'security-project', 'publication' => 'brouillon', 'statut' => 'a_venir']);
    $article = Article::create(['titre' => 'Article', 'slug' => 'security-article', 'contenu' => 'Texte', 'publication' => 'brouillon']);
    $category = Category::create(['nom' => 'Terrain', 'slug' => 'terrain']);
    $params = ['id' => 1, 'projet' => $project->id, 'article' => $article->id, 'category' => $category->id, 'programme' => 'enfant'];
    $endpoints = [];
    foreach (Route::getRoutes() as $route) {
        if (str_starts_with($route->getName() ?? '', 'admin.')) {
            $endpoints[] = [$route->methods()[0], route($route->getName(), array_intersect_key($params, array_flip($route->parameterNames())))];
        }
    }
    expect(count($endpoints))->toBe(25);
    foreach ($endpoints as [$method, $url]) {
        $this->json($method, $url)->assertUnauthorized();
    }
    $this->actingAs(User::factory()->create(['is_admin' => false]));
    foreach ($endpoints as [$method, $url]) {
        $this->json($method, $url)->assertForbidden();
    }
    expect($project->fresh())->not->toBeNull();
});

test('login limits apply across different account names from one address', function () {
    for ($i = 0; $i < 10; $i++) {
        $this->postJson(route('login'), ['email' => 'unknown'.$i.'@example.test', 'password' => 'wrong'])->assertUnprocessable();
    }
    $this->postJson(route('login'), ['email' => 'another@example.test', 'password' => 'wrong'])
        ->assertTooManyRequests()->assertHeader('Retry-After');
});

test('checkout limits creation of unpaid donations without calling Kkiapay', function () {
    for ($i = 0; $i < 10; $i++) {
        $this->post(route('donation.checkout'), ['amount' => 10])->assertRedirect();
    }
    $this->postJson(route('donation.checkout'), ['amount' => 10])->assertTooManyRequests();
    expect(Donation::count())->toBe(10);
    Http::assertNothingSent();
});

test('confirmation limits follow the donation even when the source address changes', function () {
    $donation = routeSecurityDonation();
    Http::fake(['api-sandbox.kkiapay.me/*' => Http::response(['status' => 'PENDING'])]);
    $url = URL::temporarySignedRoute('donation.kkiapay.confirm', now()->addHour(), ['donation' => $donation->reference]);
    for ($i = 1; $i <= 5; $i++) {
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.'.$i])
            ->postJson($url, ['transaction_id' => 'not-paid'])->assertUnprocessable();
    }
    $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.6'])
        ->postJson($url, ['transaction_id' => 'not-paid'])->assertTooManyRequests();
    Http::assertSentCount(5);
    expect($donation->fresh()->status)->toBe('pending');
});

test('replayed confirmed webhooks avoid API calls even during a provider outage', function () {
    $donation = routeSecurityDonation();
    $donation->update(['status' => 'confirmed', 'kkiapay_transaction_id' => 'paid-once', 'confirmed_at' => now()]);
    $confirmedAt = $donation->fresh()->confirmed_at;
    $payload = ['event' => 'transaction.success', 'partnerId' => $donation->reference, 'transactionId' => 'paid-once'];
    $this->postJson(route('kkiapay.webhook'), $payload, ['x-kkiapay-secret' => 'webhook-test'])->assertOk();
    $this->postJson(route('kkiapay.webhook'), array_replace($payload, ['transactionId' => 'another']), ['x-kkiapay-secret' => 'webhook-test'])->assertUnprocessable();
    Http::assertNothingSent();
    expect($donation->fresh()->confirmed_at->equalTo($confirmedAt))->toBeTrue();
});

test('invalid webhook authentication cannot exhaust the trusted notification quota', function () {
    for ($i = 0; $i < 125; $i++) {
        $this->postJson(route('kkiapay.webhook'), [], ['x-kkiapay-secret' => 'wrong'])->assertUnauthorized();
    }
    $this->postJson(route('kkiapay.webhook'), ['event' => 'unknown'], ['x-kkiapay-secret' => 'webhook-test'])->assertOk();
    Http::assertNothingSent();
});

test('sensitive pages avoid caching framing referrers and visitor tracking', function () {
    $donation = routeSecurityDonation();
    $url = URL::temporarySignedRoute('donation.kkiapay', now()->addHour(), ['donation' => $donation->reference]);
    $this->get($url)->assertOk()->assertHeader('Referrer-Policy', 'no-referrer')
        ->assertHeader('X-Frame-Options', 'DENY')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    $this->actingAs(User::factory()->create(['is_admin' => true]))->get(route('admin.projets.index'))
        ->assertOk()->assertHeader('Cache-Control', 'no-store, private');
    expect(Visite::count())->toBe(0);
});

test('real CSRF rejects checkout confirmation and administration writes but exempts authenticated webhooks', function () {
    $this->app->bind(ValidateCsrfToken::class, EnforcedCsrfForRouteTests::class);
    $donation = routeSecurityDonation();
    $url = URL::temporarySignedRoute('donation.kkiapay.confirm', now()->addHour(), ['donation' => $donation->reference]);
    $this->postJson(route('donation.checkout'), ['amount' => 10])->assertStatus(419);
    $this->postJson($url, ['transaction_id' => 'forged'])->assertStatus(419);
    $this->actingAs(User::factory()->create(['is_admin' => true]))
        ->postJson(route('admin.projets.enregistrer'), [])->assertStatus(419);
    $this->postJson(route('kkiapay.webhook'), ['event' => 'unknown'], ['x-kkiapay-secret' => 'webhook-test'])->assertOk();
    Http::assertNothingSent();
    expect(Donation::count())->toBe(1);
});

test('invalid signatures do not consume the valid payment link quota', function () {
    $donation = routeSecurityDonation();
    for ($i = 0; $i < 35; $i++) {
        $this->getJson(route('donation.kkiapay', $donation->reference))->assertForbidden();
    }
    $url = URL::temporarySignedRoute('donation.kkiapay', now()->addHour(), ['donation' => $donation->reference]);
    $this->get($url)->assertOk();
    Http::assertNothingSent();
});

test('administration mutations are limited per account across source addresses', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));
    for ($i = 1; $i <= 30; $i++) {
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.'.$i])
            ->postJson(route('admin.categories.store'), [])->assertUnprocessable();
    }
    $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.31'])
        ->postJson(route('admin.categories.store'), [])->assertTooManyRequests();
    expect(Category::count())->toBe(0);
});

test('valid CSRF still permits a public checkout without contacting the payment API', function () {
    $this->app->bind(ValidateCsrfToken::class, EnforcedCsrfForRouteTests::class);
    $this->withSession(['_token' => 'test-csrf-token'])
        ->post(route('donation.checkout'), ['amount' => 10, '_token' => 'test-csrf-token'])->assertRedirect();
    expect(Donation::count())->toBe(1)->and(Donation::sole()->status)->toBe('pending');
    Http::assertNothingSent();
});

test('administrators return directly to their management space after login', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $this->post(route('login'), ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect(route('admin.projets.index', absolute: false));
    $this->assertAuthenticatedAs($admin);
});
