<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\SearchedUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('spotify')
            ->scopes(['user-top-read', 'user-read-private', 'user-read-email'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $spotifyUser = Socialite::driver('spotify')->user();
        } catch (\Exception $e) {
            // Catat error detail di log server (storage/logs/laravel.log)
            Log::error('Spotify OAuth Error: ' . $e->getMessage());
            // Tampilkan pesan generik ke user (tanpa detail internal)
            return redirect('/')->withErrors(['username' => 'Gagal login dengan Spotify. Silakan coba lagi.']);
        }

        $user = SearchedUser::updateOrCreate(
            ['spotify_username' => $spotifyUser->id],
            [
                'display_name' => $spotifyUser->name ?? $spotifyUser->nickname,
                'avatar_url' => $spotifyUser->avatar,
            ]
        );
        
        session(['spotify_user_token' => $spotifyUser->token]);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        session()->forget('spotify_user_token');
        return redirect()->route('home');
    }
}
