<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController; 

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Tombol Search 
Route::post('/search', [SearchController::class, 'search'])->name('search'); 

// Hasil Pencarian 
Route::get('/u/{username}', [SearchController::class, 'showProfile'])->name('profile.show'); 
