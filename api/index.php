<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Menyesuaikan path storage untuk Vercel (karena filesystem utamanya read-only)
$app->useStoragePath('/tmp/storage');

// Buat direktori yang dibutuhkan Laravel di /tmp
$dirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/views'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Set public path agar Vite bisa menemukan manifest.json di folder public/build
$app->usePublicPath(__DIR__ . '/../public');

use Illuminate\Http\Request;

$app->handleRequest(Request::capture());
