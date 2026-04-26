<?php

namespace App\Http\Controllers;

use App\Models\SearchedUser;
use App\Models\CachedAnalysis;
use App\Services\SpotifyAnalyzerService;
;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchController extends Controller
{
    protected $spotifyService;

    public function __construct(SpotifyAnalyzerService $spotifyService)
    {
        $this->spotifyService = $spotifyService;
    }

    // Ambil input form dari main dashboard
    public function search(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255'
        ]);

        // Membersihkan input dari jika user memasukkan URL profil Spotify secara penuh
        $username = $request->username;
        if (str_contains($username, 'spotify.com/user/')) {
            $parts = explode('spotify.com/user/', $username);
            $username = explode('?', $parts[1])[0];
        }

        // Alihkan URL hasil pencarian 
        return redirect()->route('profile.show', ['username' => trim($username)]);
    }

    public function showProfile($username)
    {
        $user = SearchedUser::where('spotify_username', $username)->first();

        if ($user && $user->cachedAnalysis && $user->cachedAnalysis->expires_at > now()) {
            return view('dashboard', [
                'user' => $user,
                'analysis' => $user->cachedAnalysis
            ]);
        }

        $profileData = $this->spotifyService->getUserProfile($username);
        if (!$profileData) {
            return view('dashboard', ['error' => 'Sepertinya user spotify tersebut tidak ditemukan.']);
        }

        try {
            $analysisData = $this->spotifyService->analyzePlaylist($username);
        } catch (\Exception $e) {
            return view('dashboard', ['error' => 'Gagal mengammbil laguu: ' . $e->getMessage()]);
        }

        $user = SearchedUser::updateOrCreate(
            ['spotify_username' => $username],
            [
                'display_name' => $profileData['display_name'] ?? $username,
                'avatar_url' => count($profileData['images']) > 0 ? $profileData['images'][0]['url'] : null,
                'total_public_playlist' => $analysisData['total_playlists']
            ]
        );

        $user->cachedAnalysis()->updateOrCreate(
            ['searched_user_id' => $user->id],
            [
                'top_artists_json' => $analysisData['top_artist'],
                'expires_at' => Carbon::now()->addHours(24)
            ]
        );

        $user->refresh();
        return view('dashboard', [
            'user' => $user,
            'analysis' => $user->cachedAnalysis
        ]);
    }
}
