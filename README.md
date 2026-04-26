# SpotyStats (Public Playlist Analyzer)

SpotyStats adalah aplikasi web penganalisis profil Spotify publik. Alih-alih meminta akses OAuth dari pengguna, aplikasi ini beroperasi menggunakan *Client Credentials Flow* untuk membaca *playlist* publik milik sebuah *username*, mengurai lagu-lagunya, dan menyajikan statistik (Artis Teratas) secara instan.

## Fitur Utama

1. **Smart Search Input:** Pencarian berdasarkan Username Spotify.
2. **Public Playlist Scraper:** Otomatis mengambil daftar playlist publik.
3. **Track Aggregation:** Ekstraksi lagu dan perhitungan frekuensi artis.
4. **Cached Dashboard:** Halaman hasil di-cache menggunakan PostgreSQL untuk performa kilat.

## Tech Stack
- **Backend:** Laravel 11
- **Database:** PostgreSQL
- **Frontend:** Tailwind CSS (Clean Neo-Dark Theme)
- **API:** Spotify Web API & Accounts API

## Instalasi
1. Clone repository ini.
2. Jalankan `composer install` dan `npm install`.
3. Salin `.env.example` menjadi `.env`.
4. Atur kredensial database PostgreSQL Anda di `.env`.
5. Dapatkan `SPOTIFY_CLIENT_ID` dan `SPOTIFY_CLIENT_SECRET` dari [Spotify Developer Dashboard](https://developer.spotify.com/dashboard) dan masukkan ke `.env`.
6. Jalankan migrasi: `php artisan migrate`.
7. Jalankan server lokal: `php artisan serve` dan `npm run dev`.

## Arsitektur
Aplikasi ini menggunakan pendekatan No-Login. Hasil pencarian dari Spotify API akan diagregasi menggunakan Laravel Collections dan di-cache ke dalam database agar pencarian untuk username yang sama tidak akan membebani kuota API Spotify.
# PeekSpoti
