<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Cron Job Keep-Alive supabase 
Route::get('/api/cron-keep-alive', function (Request $request){
    // verif cron 
    $cronSecret = env('CRON_SECRET');
    if ($cronSecret && $request->header('Authorization') !== 'Bearer' . $cronSecret) {
        return response()->json(['error' => 'Unauthorizated'], 401);
    }

    try { 
        DB::select('SELECT 1');
        return response()->json([
            'status' => 'success',
            'message' => 'Database keep-alive ping sent success.'
        ]);
    } catch (\Exception $e) { 
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage()
        ], 500);
    }
});

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
