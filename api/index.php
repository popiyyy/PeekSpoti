<?php

require __DIR__ . '/../vendor/autoload.php';

die('LAMBDA_HIT');
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

use Illuminate\Http\Request;

$app->handleRequest(Request::capture());
