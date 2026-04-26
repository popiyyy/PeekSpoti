<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchedController; 

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Tombol Search 
Route::post('/search', [SearchedController::class, 'search'])->name('search'); 

// Hasil Pencarian 
Route::get('/u/{username}', [SearchedController::class, 'showProfile'])->name('profile.show'); 
