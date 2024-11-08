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
    //Owner
    Route::group(['prefix' => 'owner'], function () {
        // Property
        Route::get('/properties',
            [\App\Http\Controllers\Owner\PropertyController::class, 'index'])
            ->name('property.index');
        Route::post('/properties',
            [\App\Http\Controllers\Owner\PropertyController::class, 'store'])->name('property.store');
        // Photo Store
        Route::post('/{property}/photos',
            [\App\Http\Controllers\Owner\PropertyPhotoController::class, 'store'])->name('property-photo');
        // Photo reorder position
        Route::post('{property}/photos/{photo}/reorder/{newPosition}',
            [\App\Http\Controllers\Owner\PropertyPhotoController::class, 'reorder'])->name('photos.reorder');
    });

    // User
    Route::group(['prefix' => 'user'], function () {
        // Booking
        Route::resource('bookings', \App\Http\Controllers\User\BookingController::class)
            ->except(['edit', 'create']);
    });
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

    // Show Apartment
    Route::get('/apartments/{apartment}',
        \App\Http\Controllers\Public\ApartmentController::class)
        ->name('apartment.show');
});

// Logout
Route::post('/logout',
    [\App\Http\Controllers\Auth\SessionController::class, 'destroy']
)->middleware('auth:sanctum')->name('logout');
