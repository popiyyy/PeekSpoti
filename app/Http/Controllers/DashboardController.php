<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\SearchedUser;
use Illuminate\Http\Client\PendingRequest;

class DashboardController extends Controller
{
    /**
     * Helper: Buat HTTP client yang aman.
     * SSL verification hanya dinonaktifkan di environment lokal.
     */
    private function http(): PendingRequest
    {
        $http = Http::timeout(15);
        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }
        return $http;
    }

    public function index()
    {
        $token = session('spotify_user_token');
        if (!$token) {
            return redirect()->route('home');
        }

        $profileResponse = $this->http()->withToken($token)->get("https://api.spotify.com/v1/me");

        if ($profileResponse->failed()) {
            return view('dashboard', ['error' => 'Sesi Spotify Anda telah berakhir atau tidak valid.']);
        }

        $profile = $profileResponse->json();
        $spotifyId = $profile['id'];

        $user = SearchedUser::where('spotify_username', $spotifyId)->first();

        // Ambil Top Artists
        $topArtistsResponse = $this->http()
            ->withToken($token)
            ->get("https://api.spotify.com/v1/me/top/artists", [
                'limit' => 10,
                'time_range' => 'short_term'
            ]);

        $artists = [];
        $topArtistImage = null;
        $topGenre = 'Unknown';
        $genreCount = [];

        if ($topArtistsResponse->successful()) {
            $items = $topArtistsResponse->json('items') ?? [];
            foreach ($items as $index => $item) {
                $score = $item['popularity'] ?? (100 - ($index * 2));
                $artists[$item['name']] = $score;

                // Ambil gambar artis #1
                if ($index === 0 && !empty($item['images'])) {
                    $topArtistImage = $item['images'][0]['url'];
                }

            }
        }
        // Ambil Top Tracks
        $topTracksResponse = $this->http()
            ->withToken($token)
            ->get("https://api.spotify.com/v1/me/top/tracks", [
                'limit' => 50, // Ambil 50 lagu teratas
                'time_range' => 'short_term'
            ]);

        $topTracks = [];
        $trackArtistIds = [];
        $totalDurationMs = 0;
        $artistDurationMs = [];
        $topTrackId = null;
        $topTrackAlbumCover = null;

        if ($topTracksResponse->successful()) {
            $trackItems = $topTracksResponse->json('items') ?? [];
            foreach ($trackItems as $index => $track) {
                // Simpan 5 lagu teratas untuk ditampilkan
                if ($index < 5) {
                    $topTracks[] = $track['name'];
                }

                // Ambil ID dan album cover dari lagu #1
                if ($index === 0) {
                    $topTrackId = $track['id'] ?? null;
                    $topTrackAlbumCover = $track['album']['images'][0]['url'] ?? null;
                }

                // Kumpulkan ID artis utama dari setiap lagu untuk mendeteksi genrenya
                if (!empty($track['artists']) && isset($track['artists'][0]['id'])) {
                    $trackArtistIds[] = $track['artists'][0]['id'];
                }

                // Akumulasi durasi lagu (dalam milidetik)
                $durationMs = $track['duration_ms'] ?? 0;
                $totalDurationMs += $durationMs;

                // Hitung durasi per artis
                if (!empty($track['artists'][0]['name'])) {
                    $artistName = $track['artists'][0]['name'];
                    $artistDurationMs[$artistName] = ($artistDurationMs[$artistName] ?? 0) + $durationMs;
                }
            }

            // Hitung genre berdasarkan artis dari lagu-lagu tersebut
            $trackArtistIds = array_unique($trackArtistIds); // Hapus duplikat artis
            
            if (!empty($trackArtistIds)) {
                // Spotify API /v1/artists membatasi maksimal 50 ID per request
                $artistIdsChunk = array_slice($trackArtistIds, 0, 50);
                
                $artistsDetailsResponse = $this->http()
                    ->withToken($token)
                    ->get("https://api.spotify.com/v1/artists", [
                        'ids' => implode(',', $artistIdsChunk)
                    ]);
                
                if ($artistsDetailsResponse->successful()) {
                    $artistsDetails = $artistsDetailsResponse->json('artists') ?? [];
                    foreach ($artistsDetails as $artistDetail) {
                        if ($artistDetail && !empty($artistDetail['genres'])) {
                            foreach ($artistDetail['genres'] as $genre) {
                                $genreCount[$genre] = ($genreCount[$genre] ?? 0) + 1;
                            }
                        }
                    }
                }
            }

            // Tentukan genre yang paling sering muncul dari lagu-lagu ini
            if (!empty($genreCount)) {
                arsort($genreCount);
                $topGenre = ucwords(array_key_first($genreCount));
            }
        }

        if ($user) {
            $user->total_public_playlists = 0; 
        } else {
            $user = (object) [
                'display_name' => $profile['display_name'],
                'avatar_url' => $profile['images'][0]['url'] ?? null,
                'total_public_playlists' => 0,
            ];
        }

        $analysis = (object) [
            'top_artists_json' => $artists
        ];

        // Cache AI analysis selama 5 menit per user
        $cacheKey = "gemini_analysis_{$spotifyId}";
        $aiAnalysis = Cache::get($cacheKey);

        if (!$aiAnalysis && count($artists) > 0) {
            $geminiKey = config('services.gemini.api_key');

            if ($geminiKey) {
                $artisNames = array_keys($artists); 
                $prompt = "Sebagai pakar musik yang asik dan gaul, berikan 1 paragraf analisis singkat (maksimal 3 kalimat) tentang kepribadian orang yang paling sering mendengarkan artis-artis berikut: " . implode(", ", array_slice($artisNames, 0, 10)) . ". Gunakan gaya bahasa anak muda Indonesia yang santai dan tambahkan emoji yang pas. Jangan gunakan format markdown seperti bintang (bold/italic) sama sekali."; 

                $models = config('services.gemini.models', ['gemini-2.5-flash']);

                foreach ($models as $model) {
                    try { 
                        $geminiResponse = $this->http()
                            ->withHeaders(['x-goog-api-key' => $geminiKey])
                            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                                'contents' => [
                                    ['parts' => [['text' => $prompt]]]
                                ] 
                            ]);

                        if ($geminiResponse->successful()) { 
                            $aiAnalysis = $geminiResponse->json('candidates.0.content.parts.0.text');
                            Cache::put($cacheKey, $aiAnalysis, 300);
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                if (!$aiAnalysis) {
                    $aiAnalysis = "AI sedang sibuk, coba lagi nanti ya!";
                }
            } else {
                $aiAnalysis = "API Key Gemini belum dikonfigurasi.";
            }
        } 

        // Estimasi menit mendengarkan (durasi total top 50 lagu × rata-rata pengulangan)
        $estimatedMinutes = round($totalDurationMs / 60000);

        // Konversi durasi per artis ke menit, dan urutkan dari terbanyak
        arsort($artistDurationMs);
        $artistMinutes = [];
        foreach ($artistDurationMs as $name => $ms) {
            $artistMinutes[$name] = round($ms / 60000);
        }

        return view('dashboard', [
            'user' => $user,
            'analysis' => $analysis,
            'aiAnalysis' => $aiAnalysis,
            'topArtistImage' => $topArtistImage,
            'topTracks' => $topTracks,
            'topGenre' => $topGenre,
            'minutesListened' => $estimatedMinutes,
            'artistMinutes' => $artistMinutes,
            'topTrackId' => $topTrackId,
            'topTrackAlbumCover' => $topTrackAlbumCover,
        ]);
    }
}