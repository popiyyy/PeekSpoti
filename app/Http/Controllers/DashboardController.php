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
                $score = $item['popularity'] ?? (100 - ($index * 2));
                $artists[$item['name']] = $score;
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

        $aiAnalysis = null;
        $geminiKey = config('services.gemini.api_key'); 

        if ($geminiKey && count($artists) > 0) { 
            $artisNames = array_keys($artists); 
            $prompt = "Sebagai pakar musik yang asik dan gaul, berikan 1 paragraf analisis singkat (maksimal 3 kalimat) tentang kepribadian orang yang paling sering mendengarkan artis-artis berikut: " . implode(", ", array_slice($artisNames, 0, 10)) . ". Gunakan gaya bahasa anak muda Indonesia yang santai dan tambahkan emoji yang pas. Jangan gunakan format markdown seperti bintang (bold/italic) sama sekali."; 

            // Daftar model yang akan dicoba secara berurutan
            $models = ['gemini-2.5-flash', 'gemini-2.0-flash', 'gemini-2.0-flash-lite'];

            foreach ($models as $model) {
                try { 
                    $geminiResponse = Http::withoutVerifying()
                        ->timeout(15)
                        ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $geminiKey, [
                            'contents' => [
                                ['parts' => [['text' => $prompt]]]
                            ] 
                        ]);

                    if ($geminiResponse->successful()) { 
                        $aiAnalysis = $geminiResponse->json('candidates.0.content.parts.0.text');
                        break; // Berhasil, tidak perlu coba model lain
                    }
                } catch (\Exception $e) {
                    continue; // Coba model berikutnya
                }
            }

            // Jika semua model gagal, tampilkan pesan debug
            if (!$aiAnalysis) {
                $aiAnalysis = "[DEBUG] Semua model Gemini gagal. Key terbaca: " . substr($geminiKey, 0, 10) . "... | Response terakhir: " . ($geminiResponse->status() ?? 'N/A') . " - " . substr($geminiResponse->body() ?? '', 0, 200);
            }
        } else if (!$geminiKey) {
            $aiAnalysis = "[DEBUG] API Key Gemini NULL — config('services.gemini.api_key') kosong. Coba jalankan: php artisan config:clear";
        } 

        return view('dashboard', [
            'user' => $user,
            'analysis' => $analysis,
            'aiAnalysis' => $aiAnalysis
        ]);
    }
}