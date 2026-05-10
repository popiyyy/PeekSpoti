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

use Illuminate\Http\Request;

try {
    $request = Request::capture();
    $response = $app->handleRequest($request);
    
    if ($response->getStatusCode() === 403 || $response->getStatusCode() === 500) {
        die("<h1>LARAVEL HTTP " . $response->getStatusCode() . "</h1><p>Path: " . $request->path() . "</p><hr>" . $response->getContent());
    }
    
    $response->send();
} catch (\Throwable $e) {
    die("<h1>FATAL EXCEPTION</h1><p>" . $e->getMessage() . "</p><p>File: " . $e->getFile() . ":" . $e->getLine() . "</p>");
}
