<?php

use App\Models\Donation;
use App\Models\Messages;
use App\Models\User;
use Illuminate\Support\Str;

test('administration remains restricted to administrators', function () {
    $this->get(route('admin.projets.index'))->assertRedirect(route('login'));
    $this->actingAs(User::factory()->create())->get(route('admin.projets.index'))->assertForbidden();
});

test('overview totals include only confirmed payments and preserve exact CFA amounts', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    foreach (['confirmed', 'pending', 'failed'] as $status) {
        Donation::create([
            'reference' => (string) Str::uuid(), 'provider' => 'kkiapay',
            'amount_centimes' => 762, 'amount_xof' => 5000,
            'currency' => 'eur', 'status' => $status,
        ]);
    }

    $this->actingAs($admin)->get(route('admin.projets.index'))
        ->assertOk()->assertViewHas('confirmed', 762)
        ->assertViewHas('confirmedXof', 5000)->assertViewHas('pending', 1)
        ->assertSee('5 000 FCFA enregistrés');
});

test('message status can be changed and is reflected in the overview', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $message = Messages::create([
        'nom' => 'Martin', 'prenom' => 'Camille', 'email' => 'camille@example.test',
        'objet' => 'Bénévolat', 'message' => 'Comment vous aider ?', 'lu' => false,
    ]);
    $this->actingAs($admin)->postJson(route('admin.messages.toggleRead', $message->id))
        ->assertOk()->assertJsonPath('lu', true);
    $this->get(route('admin.projets.index'))->assertOk()
        ->assertViewHas('stats', fn ($stats) => $stats['messages_non_lus'] === 0);
    $this->postJson(route('admin.messages.toggleRead', $message->id))
        ->assertOk()->assertJsonPath('lu', false);
    $this->get(route('admin.projets.index'))->assertOk()
        ->assertViewHas('stats', fn ($stats) => $stats['messages_non_lus'] === 1);
});
