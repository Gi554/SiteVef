<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\ParrainageController;
use App\Http\Controllers\TemoignageController;
use Illuminate\Support\Facades\Route;

$adminPath = config('admin.path');
if (! is_string($adminPath) || ! preg_match('/\A[a-z0-9][a-z0-9_-]{2,79}\z/i', $adminPath)) {
    throw new InvalidArgumentException('ADMIN_PATH doit contenir entre 3 et 80 lettres, chiffres, tirets ou tirets bas, sans slash.');
}

Route::prefix($adminPath)->middleware(['auth', 'can:admin', 'throttle:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.projets.index');
    Route::post('/enregistrer-projet', [AdminController::class, 'store'])->name('admin.projets.enregistrer');
    Route::put('/projet/{id}', [AdminController::class, 'update'])->name('admin.projets.update');
    Route::delete('/projet/{id}', [AdminController::class, 'destroy'])->name('admin.projets.destroy');
    Route::post('/messages/{id}/toggle-read', [AdminController::class, 'toggleRead'])->name('admin.messages.toggleRead');
    Route::post('/temoignages', [TemoignageController::class, 'store'])->name('admin.temoignages.store');
    Route::delete('/temoignages/{id}', [TemoignageController::class, 'destroy'])->name('admin.temoignages.destroy');
    Route::post('/temoignages/{id}/toggle', [TemoignageController::class, 'toggle'])->name('admin.temoignages.toggle');
    Route::post('/membres', [MembreController::class, 'store'])->name('admin.membres.store');
    Route::put('/membres/{id}', [MembreController::class, 'update'])->name('admin.membres.update');
    Route::delete('/membres/{id}', [MembreController::class, 'destroy'])->name('admin.membres.destroy');
    Route::post('/membres/{id}/toggle', [MembreController::class, 'toggle'])->name('admin.membres.toggle');
    Route::get('/parrainage', [ParrainageController::class, 'edit'])->name('admin.parrainage.edit');
    Route::put('/parrainage/{programme:code}', [ParrainageController::class, 'update'])->name('admin.parrainage.update');
    Route::get('/projets/{projet}/editer', [EditorialController::class, 'editProjet'])->name('admin.projets.edit');
    Route::get('/projets/{projet}/apercu', [EditorialController::class, 'previewProjet'])->name('admin.projets.preview');
    Route::get('/articles/creer', [EditorialController::class, 'editArticle'])->name('admin.articles.create');
    Route::get('/articles/{article}/editer', [EditorialController::class, 'editArticle'])->name('admin.articles.edit');
    Route::get('/articles/{article}/apercu', [EditorialController::class, 'previewArticle'])->name('admin.articles.preview');
    Route::post('/articles', [EditorialController::class, 'saveArticle'])->name('admin.articles.store');
    Route::put('/articles/{article}', [EditorialController::class, 'saveArticle'])->name('admin.articles.update');
    Route::delete('/articles/{article}', [EditorialController::class, 'deleteArticle'])->name('admin.articles.destroy');
    Route::post('/categories', [EditorialController::class, 'saveCategory'])->name('admin.categories.store');
    Route::put('/categories/{category}', [EditorialController::class, 'saveCategory'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [EditorialController::class, 'deleteCategory'])->name('admin.categories.destroy');
});
