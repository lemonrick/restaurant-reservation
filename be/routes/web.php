<?php
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json(['error' => 'Unauthenticated.'], 401);
})->name('login');
