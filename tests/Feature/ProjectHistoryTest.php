<?php

use App\Models\Donation;
use App\Models\Projet;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

test('removing a project keeps the historical allocation of pending and confirmed donations', function () {
    Http::preventStrayRequests();
    $project = Projet::create(['titre' => 'Projet à conserver', 'slug' => 'historique-projet', 'publication' => 'publie', 'statut' => 'en_cours', 'dons_actifs' => true]);
    foreach (['pending', 'confirmed'] as $status) {
        Donation::create(['reference' => (string) Str::uuid(), 'projet_id' => $project->id, 'amount_centimes' => 762, 'amount_xof' => 5000, 'status' => $status]);
    }
    $this->actingAs(User::factory()->create(['is_admin' => true]))
        ->delete(route('admin.projets.destroy', $project))->assertRedirect();
    $this->assertSoftDeleted('projets', ['id' => $project->id]);
    $this->assertDatabaseHas('projets', ['id' => $project->id, 'publication' => 'archive', 'dons_actifs' => false]);
    expect(Projet::find($project->id))->toBeNull();
    foreach (Donation::with('projet')->get() as $donation) {
        expect($donation->projet_id)->toBe($project->id)->and($donation->projet->titre)->toBe('Projet à conserver');
    }
    $this->get(route('admin.projets.index'))->assertOk()->assertSee('Projet à conserver');
    $this->get(route('projets.show', $project->slug))->assertNotFound();
    $this->postJson(route('donation.checkout'), ['amount' => 10, 'projet_id' => $project->id])->assertNotFound();
    expect(Donation::count())->toBe(2);
    Http::assertNothingSent();
});

test('a pending payment link retains its project destination after project removal', function () {
    config(['kkiapay.public_key' => 'public', 'kkiapay.private_key' => 'private', 'kkiapay.secret_key' => 'secret']);
    $project = Projet::create(['titre' => 'Destination historique', 'slug' => 'destination-historique', 'publication' => 'publie', 'statut' => 'en_cours']);
    $donation = Donation::create(['reference' => (string) Str::uuid(), 'projet_id' => $project->id, 'amount_centimes' => 762, 'amount_xof' => 5000, 'status' => 'pending']);
    $project->delete();
    $url = URL::temporarySignedRoute('donation.kkiapay', now()->addHour(), ['donation' => $donation->reference]);
    $this->get($url)->assertOk()->assertSee('Destination historique');
});
