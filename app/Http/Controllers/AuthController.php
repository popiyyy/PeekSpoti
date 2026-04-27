<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\SearchedUser;
use Illuminate\Support\Facades\Auth;

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
            return redirect('/')->withErrors(['username' => 'Gagal login dengan Spotify: ' . $e->getMessage()]);
        }

        // Simpan data profil pengguna ke database kita (Atau perbarui jika sudah ada)
        $user = SearchedUser::updateOrCreate(
            ['spotify_username' => $spotifyUser->id],
            [
                'display_name' => $spotifyUser->name ?? $spotifyUser->nickname,
                'avatar_url' => $spotifyUser->avatar,
            ]
        );
        
        // Simpan tokennya sementara di session untuk menarik top artis.
        session(['spotify_user_token' => $spotifyUser->token]);

        return redirect()->route('dashboard');
    }
}
