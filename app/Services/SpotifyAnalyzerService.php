<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;


class SpotifyAnalyzerService
{
    
    // Mengambil Acces Token dari Spotify
    private function getAccessToken()
    {
        return Cache::remember('spotify_access_token', 3500, function(){
            $clientId = env('SPOTIFY_CLIENT_ID');
            $clientSecret = env('SPOTIFY_CLIENT_SECRET');
            $response = Http::asForm()->withBasicAuth($clientId, $clientSecret)->post('https://accounts.spotify.com/api/token', ['grant_type' => 'client_credentials', ]);
            if ($response->failed()) {
                throw new Exception('Gagal mendapatkan otorisasi dari Spotify. Cek Client ID & Secret di .env'); 
            }
            return $response->json('access_token');
        });
    }

    // Mengambil info profil (Display nama dan foto profil)
    public function getUserProfile($username)
    {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)->get("https://api.spotify.com/v1/users/{$username}");
        if ($response->failed()) {
            return null; // jika username tidak ditemukan
        }

        return $response->json();
    }

    // Anaisis playlist dan menghitung top artis
    public function analyzePlaylist($username)
    {
        $token = $this->getAccessToken();
        $playlistsResponse = Http::withToken($token)->get("https://api.spotify.com/v1/users/{$username}/playlists", ['limit' => 50]);
        if ($playlistsResponse->failed()){
            throw new Exception("Gagal mengambil daftar playlist.");
        }
        
        // Filter: Hanya akan mengambil playlist milik user
        $playlist = collect($playlistsResponse->json('items'))->filter(fn($p) => isset($p['owner']['id']) && $p['owner']['id'] === $username); 
        $artistCount = []; 

        // Mengambil lagu playlist 
        foreach ($playlist as $pl) {
            if (!isset($pl['tracks']['href'])) continue; 
            $tracksResponse = Http::withToken($token)->get($pl['tracks']['href'], ['limit' => 100]); 

            if ($tracksResponse->successful()) { 
                $tracks = collect($tracksResponse->json('items'))->pluck('track.artists')->flatten(1); 
                foreach ($tracks as $artist) { 
                    if ($artist && isset($artist['name'])) { 
                        $name = $artist['name']; 
                        $artistCount[$name] = ($artistCount[$name] ?? 0) + 1; // skor frekuensi artis
                    }
                }
            }
        }

        // Sorting artis terbanyak muncul (descending)
        arsort($artistCount); 

        return[
            'total_playlists' => $playlist->count(), 
            'top_artist' => array_slice($artistCount, 0, 20, true),  
        ]; 
    }
}
