<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'))
    ->name('home');

Route::middleware('auth')->group(function () {

    /*
     Dashboard
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
      Établissements
    */

    Route::resource('establishments', EstablishmentController::class)
        ->except(['show']);

    Route::post(
        '/establishments/{establishment}/switch',
        [EstablishmentController::class, 'switch']
    )->name('establishments.switch');
    Route::get(
    '/establishments/archived',
    [EstablishmentController::class, 'archived']
)->name('establishments.archived');

Route::patch(
    '/establishments/{id}/restore',
    [EstablishmentController::class, 'restore']
)->name('establishments.restore');

Route::delete(
    '/establishments/{id}/force-delete',
    [EstablishmentController::class, 'forceDelete']
)->name('establishments.force-delete');

    /*
      Avis
    */

    Route::get('/reviews', [ReviewController::class, 'index'])
        ->name('reviews.index');

    Route::get('/reviews/new', [ReviewController::class, 'create'])
        ->name('reviews.create');

    Route::post('/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store');

    Route::get('/reviews/{review}', [ReviewController::class, 'show'])
        ->name('reviews.show');

    Route::patch('/reviews/{review}/replied', [ReviewController::class, 'markReplied'])
        ->name('reviews.replied');

    /*
     Profil
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';