<?php

use App\Http\Controllers\ControllerAccueil;
use App\Http\Controllers\ControllerCandidature;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ParrainageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Routes publiques ──────────────────────────────────────────────────────────

Route::get('/', [ControllerAccueil::class, 'index'])->name('index');

Route::view('/association', 'pages.association')->name('association');
Route::view('/questions-frequentes', 'pages.faq')->name('faq');
foreach (['confidentialite', 'cookies', 'mentions-legales', 'conditions-utilisation'] as $page) {
    Route::get('/'.$page, [\App\Http\Controllers\InformationController::class, 'show'])
        ->defaults('page', $page)->name($page);
}

Route::get('/rejoindre', fn () => view('rejoindre'))->name('rejoindre');

Route::get('/parrainage', [ParrainageController::class, 'index'])->name('parrainage.index');

Route::get('/equipe', [ControllerAccueil::class, 'equipe'])->name('equipe');

Route::post('/candidature', [ControllerCandidature::class, 'store'])->middleware('throttle:5,1')->name('candidature.store');

Route::post('/accueilMessage', [ControllerAccueil::class, 'message'])->middleware('throttle:5,1')->name('message');

// Donations
Route::get('/don', [DonationController::class, 'index'])->name('donation.index');
Route::post('/don/checkout', [DonationController::class, 'checkout'])->middleware('throttle:donation-checkout')->name('donation.checkout');
Route::get('/don/merci', [DonationController::class, 'success'])->name('donation.success');
Route::get('/don/kkiapay/{donation:reference}', [\App\Http\Controllers\KkiapayController::class, 'show'])->middleware(['signed', 'throttle:payment-confirmation'])->name('donation.kkiapay');
Route::get('/don/kkiapay/{donation:reference}/retour', [\App\Http\Controllers\KkiapayController::class, 'paymentReturn'])
    ->middleware([\Illuminate\Routing\Middleware\ValidateSignature::absolute(['transaction_id']), 'throttle:payment-confirmation'])
    ->name('donation.kkiapay.return');
Route::post('/don/kkiapay/{donation:reference}', [\App\Http\Controllers\KkiapayController::class, 'confirm'])->middleware(['signed', 'throttle:payment-confirmation'])->name('donation.kkiapay.confirm');
Route::post('/kkiapay/webhook', [\App\Http\Controllers\KkiapayController::class, 'webhook'])->middleware([\App\Http\Middleware\VerifyKkiapayWebhook::class, 'throttle:kkiapay-webhook'])->name('kkiapay.webhook');

Route::get('/dashboard', fn () => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/sitemap.xml', [\App\Http\Controllers\EditorialController::class, 'sitemap'])->name('sitemap');
Route::get('/projets', [\App\Http\Controllers\EditorialController::class, 'projets'])->name('projets.index');
Route::get('/projets/{slug}', [\App\Http\Controllers\EditorialController::class, 'projet'])->name('projets.show');
Route::get('/actualites', [\App\Http\Controllers\EditorialController::class, 'articles'])->name('actualites.index');
Route::get('/actualites/{slug}', [\App\Http\Controllers\EditorialController::class, 'article'])->name('actualites.show');
require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
