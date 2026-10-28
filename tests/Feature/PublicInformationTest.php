<?php

test('the new public pages render without an account and expose their navigation', function (string $route) {
    $response = $this->get(route($route));
    $response->assertOk()->assertSee('Informations pratiques et légales')
        ->assertSee(route('confidentialite'), false)->assertSee(route('faq'), false);
    expect(substr_count($response->getContent(), '<h1'))->toBe(1);
})->with(['association', 'faq', 'confidentialite', 'cookies', 'mentions-legales', 'conditions-utilisation']);

test('the privacy information is available before sending a contact or application form', function () {
    $this->get(route('index'))->assertOk()->assertSee(route('confidentialite'), false)
        ->assertSee('name="message"', false)->assertSee('les solidarités au Bénin.');
    $this->get(route('rejoindre'))->assertOk()->assertSee(route('confidentialite'), false)
        ->assertSee('name="rgpd"', false)->assertSee('Envoyer ma candidature');
});

test('the contact map does not contact google maps through an automatic iframe', function () {
    $this->get(route('index'))->assertOk()->assertDontSee('<iframe', false)
        ->assertSee('Ouvrir le quartier dans Google Maps');
});

test('the information pages do not invent registration or hosting data', function () {
    config(['vef.publication_director' => null, 'vef.registration' => null, 'vef.hosting_name' => null]);
    $this->get(route('mentions-legales'))->assertOk()->assertSee('Information à confirmer par l’association.');
    config(['vef.publication_director' => '<script>example</script>', 'vef.registration' => 'REFERENCE-FOURNIE-PAR-VEF']);
    $this->get(route('mentions-legales'))->assertOk()->assertSee('&lt;script&gt;example&lt;/script&gt;', false)
        ->assertDontSee('<script>example</script>', false)->assertSee('REFERENCE-FOURNIE-PAR-VEF');
});

test('the sitemap includes the information pages and donation entry point', function () {
    $response = $this->get(route('sitemap'))->assertOk();
    foreach (['association', 'faq', 'confidentialite', 'cookies', 'mentions-legales', 'conditions-utilisation', 'donation.index'] as $route) {
        $response->assertSee(route($route), false);
    }
});
