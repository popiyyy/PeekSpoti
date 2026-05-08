<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Autentikasi Spotify (Dibatasi 10 request per menit untuk mencegah abuse)
Route::middleware('throttle:10,1')->group(function () {
    Route::get('/auth/redirect', [AuthController::class, 'redirect'])->name('spotify.login');
    Route::get('/auth/callback', [AuthController::class, 'callback'])->name('spotify.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Pribadi (Harus login Spotify, dibatasi 30 request per menit)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['spotify.auth', 'throttle:30,1'])
    ->name('dashboard');
