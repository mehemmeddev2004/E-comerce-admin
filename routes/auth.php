<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Login routes
Route::get('/login', function () {
    return Inertia::render('auth/login', [
        'canResetPassword' => true,
        'canRegister' => true,
        'status' => session('status'),
    ]);
})->middleware('guest')->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest')
    ->name('login.store');

// Register routes
Route::get('/register', function () {
    return Inertia::render('auth/register');
})->middleware('guest')->name('register');

Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('guest')
    ->name('register.store');

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
