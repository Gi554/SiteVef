<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('initial administrators can be provisioned with a hidden password prompt', function () {
    $this->artisan('admin:create', ['email' => 'admin@example.test', '--name' => 'Responsable VEF'])
        ->expectsQuestion('Mot de passe administrateur', 'Vef!SecurePass2026')
        ->assertSuccessful();
    $user = User::sole();
    expect((bool) $user->is_admin)->toBeTrue()->and($user->email_verified_at)->not->toBeNull()
        ->and(Hash::check('Vef!SecurePass2026', $user->password))->toBeTrue();
});

test('provisioning cannot replace or promote an existing account', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $this->artisan('admin:create', ['email' => $user->email])
        ->expectsQuestion('Mot de passe administrateur', 'Vef!SecurePass2026')->assertFailed();
    expect(User::count())->toBe(1)->and((bool) $user->fresh()->is_admin)->toBeFalse();
});

test('weak provisioning passwords do not create an administrator', function () {
    $this->artisan('admin:create', ['email' => 'admin@example.test'])
        ->expectsQuestion('Mot de passe administrateur', 'password')->assertFailed();
    expect(User::count())->toBe(0);
});
