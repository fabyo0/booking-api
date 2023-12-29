<?php

use Illuminate\Http\Request;
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
    Route::get('owner/properties',
        [\App\Http\Controllers\Owner\PropertyController::class, 'index']);
    Route::get('user/bookings',
        [\App\Http\Controllers\User\BookingController::class, 'index']);
});

// Register
Route::post('auth/register', App\Http\Controllers\Auth\RegisterController::class);

// Login
Route::post('auth/login',
    [\App\Http\Controllers\Auth\SessionController::class, 'store']
);

// Logout
Route::post('/logout',
    [\App\Http\Controllers\Auth\SessionController::class, 'destroy']
)->middleware('auth');

