<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Autentikasi Spotify
Route::get('/auth/redirect', [AuthController::class, 'redirect'])->name('spotify.login');
Route::get('/auth/callback', [AuthController::class, 'callback'])->name('spotify.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Pribadi
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
