<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // Property
    Route::get('owner/properties',
        [\App\Http\Controllers\Owner\PropertyController::class, 'index'])
        ->name('property.index');

    Route::post('owner/properties',
        [\App\Http\Controllers\Owner\PropertyController::class, 'store'])->name('property.store');

    // Bookings
    Route::get('user/bookings',
        [\App\Http\Controllers\User\BookingController::class, 'index'])->name('booking.index');
});

Route::middleware('guest')->group(function () {
    // Register
    Route::post('auth/register', App\Http\Controllers\Auth\RegisterController::class)
        ->name('auth.register');

    // Login
    Route::post('auth/login',
        [\App\Http\Controllers\Auth\SessionController::class, 'store']
    )->name('auth.login');

    // Search GeoObject
    Route::get('search',
        \App\Http\Controllers\Public\PropertySearchController::class)
        ->name('property.search');

    // Show Properties
    Route::get('properties/{property}',
        \App\Http\Controllers\Public\PropertyController::class)
        ->name('property.show');
});

// Logout
Route::post('/logout',
    [\App\Http\Controllers\Auth\SessionController::class, 'destroy']
)->middleware('auth:sanctum')->name('logout');
