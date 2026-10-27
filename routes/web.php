<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ControllerAccueil;
use App\Http\Controllers\ControllerCandidature;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\ParrainageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemoignageController;
use Illuminate\Support\Facades\Route;

// ── Routes publiques ──────────────────────────────────────────────────────────

Route::get('/', [ControllerAccueil::class, 'index'])->name('index');

Route::get('/rejoindre', fn () => view('rejoindre'))->name('rejoindre');

Route::get('/parrainage', [ParrainageController::class, 'index'])->name('parrainage.index');

Route::get('/equipe', [ControllerAccueil::class, 'equipe'])->name('equipe');

Route::post('/candidature', [ControllerCandidature::class, 'store'])->middleware('throttle:5,1')->name('candidature.store');

Route::post('/accueilMessage', [ControllerAccueil::class, 'message'])->middleware('throttle:5,1')->name('message');

// Donations
Route::get('/don', [DonationController::class, 'index'])->name('donation.index');
Route::post('/don/checkout', [DonationController::class, 'checkout'])->middleware('throttle:10,1')->name('donation.checkout');
Route::get('/don/merci', [DonationController::class, 'success'])->name('donation.success');
Route::get('/don/kkiapay/{donation:reference}', [\App\Http\Controllers\KkiapayController::class, 'show'])->middleware('signed')->name('donation.kkiapay');
Route::get('/don/kkiapay/{donation:reference}/retour', [\App\Http\Controllers\KkiapayController::class, 'paymentReturn'])
    ->middleware([\Illuminate\Routing\Middleware\ValidateSignature::absolute(['transaction_id']), 'throttle:10,1'])
    ->name('donation.kkiapay.return');
Route::post('/don/kkiapay/{donation:reference}', [\App\Http\Controllers\KkiapayController::class, 'confirm'])->middleware(['signed', 'throttle:10,1'])->name('donation.kkiapay.confirm');
Route::post('/kkiapay/webhook', [\App\Http\Controllers\KkiapayController::class, 'webhook'])->name('kkiapay.webhook');

// Suppression projet (resource, seul destroy est utilisé)
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware(['auth', 'can:admin'])->name('posts.destroy');

// ── Routes authentifiées ──────────────────────────────────────────────────────

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'can:admin'])->group(function () {

    // Admin dashboard
    Route::get('/administrateur', [AdminController::class, 'index'])
        ->name('admin.projets.index');

    // Projets
    Route::post('/administrateur/enregistrer-projet', [AdminController::class, 'store'])
        ->name('admin.projets.enregistrer');

    Route::put('/administrateur/projet/{id}', [AdminController::class, 'update'])
        ->name('admin.projets.update');

    Route::delete('/administrateur/projet/{id}', [AdminController::class, 'destroy'])
        ->name('admin.projets.destroy');

    // Messages — marquer lu / non lu
    Route::post('/administrateur/messages/{id}/toggle-read', [AdminController::class, 'toggleRead'])
        ->name('admin.messages.toggleRead');

    // Témoignages
    Route::post('/administrateur/temoignages', [TemoignageController::class, 'store'])
        ->name('admin.temoignages.store');

    Route::delete('/administrateur/temoignages/{id}', [TemoignageController::class, 'destroy'])
        ->name('admin.temoignages.destroy');

    Route::post('/administrateur/temoignages/{id}/toggle', [TemoignageController::class, 'toggle'])
        ->name('admin.temoignages.toggle');

    // Membres
    Route::post('/administrateur/membres', [MembreController::class, 'store'])
        ->name('admin.membres.store');

    Route::put('/administrateur/membres/{id}', [MembreController::class, 'update'])
        ->name('admin.membres.update');

    Route::delete('/administrateur/membres/{id}', [MembreController::class, 'destroy'])
        ->name('admin.membres.destroy');

    Route::post('/administrateur/membres/{id}/toggle', [MembreController::class, 'toggle'])
        ->name('admin.membres.toggle');

});
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
Route::middleware(['auth', 'can:admin'])->prefix('administrateur')->group(function () {
    Route::get('/parrainage', [ParrainageController::class, 'edit'])->name('admin.parrainage.edit');
    Route::put('/parrainage/{programme:code}', [ParrainageController::class, 'update'])->name('admin.parrainage.update');
    Route::get('/projets/{projet}/editer', [\App\Http\Controllers\EditorialController::class, 'editProjet'])->name('admin.projets.edit');
    Route::get('/projets/{projet}/apercu', [\App\Http\Controllers\EditorialController::class, 'previewProjet'])->name('admin.projets.preview');
    Route::get('/articles/creer', [\App\Http\Controllers\EditorialController::class, 'editArticle'])->name('admin.articles.create');
    Route::get('/articles/{article}/editer', [\App\Http\Controllers\EditorialController::class, 'editArticle'])->name('admin.articles.edit');
    Route::get('/articles/{article}/apercu', [\App\Http\Controllers\EditorialController::class, 'previewArticle'])->name('admin.articles.preview');
    Route::post('/articles', [\App\Http\Controllers\EditorialController::class, 'saveArticle'])->name('admin.articles.store');
    Route::put('/articles/{article}', [\App\Http\Controllers\EditorialController::class, 'saveArticle'])->name('admin.articles.update');
    Route::delete('/articles/{article}', [\App\Http\Controllers\EditorialController::class, 'deleteArticle'])->name('admin.articles.destroy');
    Route::post('/categories', [\App\Http\Controllers\EditorialController::class, 'saveCategory'])->name('admin.categories.store');
    Route::put('/categories/{category}', [\App\Http\Controllers\EditorialController::class, 'saveCategory'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\EditorialController::class, 'deleteCategory'])->name('admin.categories.destroy');
});

require __DIR__.'/auth.php';
