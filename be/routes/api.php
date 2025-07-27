<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'registerUser']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes (any logged-in user)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/users/{user}', [UserController::class, 'updateUser']);
    Route::get('/tables/seats', [TableController::class, 'seatOptions']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | Admin-only Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('abilities:admin')->group(function () {
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::get('/users/selectable', [UserController::class, 'getUserNames']);
        Route::post('/reservations/for-user', [ReservationController::class, 'storeForUser']);
        Route::post('/reservations/by-phone', [ReservationController::class, 'storeByPhone']);
        Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
    });
});
