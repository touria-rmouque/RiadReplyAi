<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EstablishmentController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

/*
 Public Routes
*/

Route::post('/login', [AuthController::class, 'login'])
    ->name('api.login');

/*
 Protected Routes
*/

Route::middleware('auth:sanctum')
    ->name('api.')
    ->group(function () {

        /*
         Auth
        */

        Route::get('/user', [AuthController::class, 'user'])
            ->name('user');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');

        /*
         Dashboard
        */

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
         Establishments
        */

        Route::apiResource('establishments', EstablishmentController::class);

        Route::post(
            '/establishments/{establishment}/switch',
            [EstablishmentController::class, 'switch']
        )->name('establishments.switch');

        /*
         Reviews
        */

        Route::apiResource('reviews', ReviewController::class);

        Route::patch(
            '/reviews/{review}/replied',
            [ReviewController::class, 'markReplied']
        )->name('reviews.replied');
    });