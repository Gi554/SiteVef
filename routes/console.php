<?php

use App\Models\Donation;
use App\Models\User;
use App\Services\Kkiapay;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:grant {email}', function () {
    $user = User::where('email', $this->argument('email'))->firstOrFail();
    $user->forceFill(['is_admin' => true])->save();
    $this->info('Accès administrateur attribué.');
})->purpose('Attribuer explicitement le rôle administrateur à un compte existant');

Artisan::command('admin:create {email} {--name=Administrateur VEF}', function () {
    $password = $this->secret('Mot de passe administrateur');
    $data = ['email' => $this->argument('email'), 'name' => $this->option('name'), 'password' => $password];
    $validator = Validator::make($data, [
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'name' => ['required', 'string', 'max:255'],
        'password' => ['required', Password::min(12)->mixedCase()->numbers()->symbols()],
    ]);
    if ($validator->fails()) {
        $this->error('Email déjà utilisé, nom invalide ou mot de passe insuffisant (12 caractères, majuscules, minuscules, chiffres et symbole).');

        return 1;
    }
    DB::transaction(function () use ($data) {
        $user = User::create($data);
        $user->forceFill(['is_admin' => true, 'email_verified_at' => now()])->save();
    });
    $this->info('Compte administrateur créé.');

    return 0;
})->purpose('Créer le premier administrateur par une saisie de mot de passe masquée, sans inscription publique');

Artisan::command('donations:verify-kkiapay {reference} {transaction}', function () {
    $donation = Donation::where('reference', $this->argument('reference'))
        ->where('provider', 'kkiapay')->firstOrFail();
    app(Kkiapay::class)->confirm($donation, $this->argument('transaction'));
    $this->info('Don confirmé après vérification auprès de Kkiapay : '.$donation->reference);
})->purpose('Rapprocher un don avec une transaction Kkiapay vérifiée par son API');
