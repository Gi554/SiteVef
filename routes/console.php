<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:grant {email}', function () {
    $user = \App\Models\User::where('email', $this->argument('email'))->firstOrFail();
    $user->forceFill(['is_admin' => true])->save();
    $this->info('Accès administrateur attribué.');
})->purpose('Attribuer explicitement le rôle administrateur à un compte existant');

Artisan::command('donations:verify-kkiapay {reference} {transaction}', function () {
    $donation = \App\Models\Donation::where('reference', $this->argument('reference'))
        ->where('provider', 'kkiapay')->firstOrFail();
    app(\App\Services\Kkiapay::class)->confirm($donation, $this->argument('transaction'));
    $this->info('Don confirmé après vérification auprès de Kkiapay : '.$donation->reference);
})->purpose('Rapprocher un don avec une transaction Kkiapay vérifiée par son API');
