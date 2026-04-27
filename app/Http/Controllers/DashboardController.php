<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\SearchedUser;

class DashboardController extends Controller
{
    public function index()
    {
        $token = session('spotify_user_token');
        if (!$token) {
            return redirect()->route('home');
        }

        // Karena kita belum mengimplementasikan auth guard penuh, 
        // kita ambil data berdasar session (ini sangat dasar, tapi cocok untuk demo).
        $profileResponse = Http::withoutVerifying()->withToken($token)->get("https://api.spotify.com/v1/me");
        
        if ($profileResponse->failed()) {
            return view('dashboard', ['error' => 'Sesi Spotify Anda telah berakhir atau tidak valid.']);
        }
        
        $profile = $profileResponse->json();
        $spotifyId = $profile['id'];

        $user = SearchedUser::where('spotify_username', $spotifyId)->first();

        // Ambil Top Artis (Bisa long term, medium term, short term)
        $topResponse = Http::withoutVerifying()
            ->withToken($token)
            ->get("https://api.spotify.com/v1/me/top/artists", [
                'limit' => 20,
                'time_range' => 'medium_term' // 6 bulan terakhir
            ]);

        $artists = [];
        if ($topResponse->successful()) {
            $items = $topResponse->json('items') ?? [];
            foreach ($items as $index => $item) {
                // Jika popularity tidak ada, gunakan default (menurun berdasarkan urutan)
                $score = $item['popularity'] ?? (100 - ($index * 2));
                $artists[$item['name']] = $score; 
            }
        }

        // Kita modifikasi Dashboard View agar menerima data top artis ini.
        // Sebelumnya, analysis->top_artists_json. Kita over-ride:
        
        if ($user) {
            $user->total_public_playlists = 0; // Tidak lagi menghitung playlist
        } else {
            // Fallback jika anehnya tidak masuk database
            $user = (object) [
                'display_name' => $profile['display_name'],
                'avatar_url' => $profile['images'][0]['url'] ?? null,
                'total_public_playlists' => 0,
            ];
        }

        $analysis = (object) [
            'top_artists_json' => $artists
        ];

        return view('dashboard', [
            'user' => $user,
            'analysis' => $analysis
        ]);
    }
}
