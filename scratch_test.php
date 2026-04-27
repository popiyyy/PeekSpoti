<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$token = \Illuminate\Support\Facades\Cache::get('spotify_access_token');
$response = \Illuminate\Support\Facades\Http::withoutVerifying()->withToken($token)->get("https://api.spotify.com/v1/playlists/37i9dQZF1DXcBWIGoYBM5M");
echo "Status: " . $response->status() . "\n";
echo substr($response->body(), 0, 500);
