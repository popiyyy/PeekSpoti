# 📚 Dokumentasi Teknis — SpotiPeek

Dokumen ini berisi panduan teknis lengkap untuk developer yang ingin menjalankan, mengembangkan, atau berkontribusi pada proyek SpotiPeek.

---

## 💻 Prerequisites

Pastikan Anda telah menginstal:
- PHP >= 8.2 (dengan ekstensi `fileinfo` aktif)
- Composer
- Node.js & npm
- Akun [Spotify Developer](https://developer.spotify.com/dashboard) (untuk OAuth)
- Akun [Google AI Studio](https://aistudio.google.com/app/apikey) (untuk API Key Gemini)
- Proyek [Supabase](https://supabase.com/) (untuk Database PostgreSQL)

---

## ⚙️ Langkah Instalasi

### 1. Clone & Install Dependencies
```bash
git clone https://github.com/popiyyy/SpotiPeek.git
cd SpotiPeek
composer install
npm install
```

### 2. Konfigurasi Environment (`.env`)
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database (Supabase)
Ubah konfigurasi database di file `.env` menggunakan Connection Pooler URL dari Supabase:
```env
DB_CONNECTION=pgsql
DB_URL="postgresql://postgres.namaproyek:PasswordAnda@aws-0-ap-southeast-1.pooler.supabase.com:6543/postgres"
```

### 4. Setup Spotify API
1. Buka [Spotify Developer Dashboard](https://developer.spotify.com/dashboard).
2. Buat aplikasi baru, dapatkan **Client ID** dan **Client Secret**.
3. Tambahkan Redirect URI (sesuaikan domain lokal Anda):
   ```
   http://spotipeek.test/auth/callback
   ```
4. Masukkan kredensial ke `.env`:
```env
SPOTIFY_CLIENT_ID=client_id_anda
SPOTIFY_CLIENT_SECRET=client_secret_anda
SPOTIFY_REDIRECT_URI=http://spotipeek.test/auth/callback
```

### 5. Setup Gemini AI API
1. Dapatkan API Key gratis di [Google AI Studio](https://aistudio.google.com/app/apikey).
2. Tambahkan ke `.env`:
```env
GEMINI_API_KEY=api_key_gemini_anda
```

### 6. Migrasi Database
```bash
php artisan migrate:fresh
```
> *Catatan: Abaikan peringatan "RLS Disabled in Public" di Supabase Security Advisor — keamanan ditangani oleh backend Laravel.*

### 7. Jalankan Aplikasi
```bash
# Terminal 1: Compile aset CSS/JS
npm run dev

# Terminal 2: Server PHP (jika tidak pakai Laragon/Herd)
php artisan serve
```

Akses di: `http://spotipeek.test` (Laragon) atau `http://localhost:8000` (artisan serve).

---

## 🏗️ Arsitektur Proyek

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      # Login/Logout Spotify OAuth
│   │   └── DashboardController.php  # Fetch data Spotify + Gemini AI
│   └── Middleware/
│       └── EnsureSpotifyToken.php   # Proteksi route dashboard
├── Models/
│   └── SearchedUser.php             # Model database user
├── Services/
│   └── SpotifyAnalyzerService.php   # Client Credentials Spotify API
└── README.md                        # (File ini)
```

---

## 🔑 Konfigurasi Penting

### `config/services.php`
Semua kredensial pihak ketiga terpusat di sini:
```php
'spotify' => [
    'client_id'     => env('SPOTIFY_CLIENT_ID'),
    'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
    'redirect'      => env('SPOTIFY_REDIRECT_URI'),
],
'gemini' => [
    'api_key' => env('GEMINI_API_KEY'),
    'models'  => ['gemini-2.5-flash', 'gemini-2.0-flash', 'gemini-2.0-flash-lite'],
],
```

### `bootstrap/app.php`
Middleware custom didaftarkan di sini:
```php
$middleware->alias([
    'spotify.auth' => \App\Http\Middleware\EnsureSpotifyToken::class,
]);
```

### `routes/web.php`
Route yang dilindungi middleware dan rate limiting:
```php
// OAuth (max 10 req/menit)
Route::middleware('throttle:10,1')->group(function () {
    Route::get('/auth/redirect', ...)->name('spotify.login');
    Route::get('/auth/callback', ...)->name('spotify.callback');
});

// Dashboard (butuh login + max 30 req/menit)
Route::get('/dashboard', ...)
    ->middleware(['spotify.auth', 'throttle:30,1']);
```

---

## 🔒 Catatan Keamanan untuk Production

Jika aplikasi ini akan di-hosting publik, pastikan:

| # | Tindakan | Lokasi |
|---|---|---|
| 1 | Ubah `APP_DEBUG=false` | `.env` |
| 2 | Pastikan `SESSION_ENCRYPT=true` | `.env` |
| 3 | SSL bypass (`withoutVerifying()`) otomatis nonaktif saat `APP_ENV=production` | `DashboardController.php`, `SpotifyAnalyzerService.php` |
| 4 | Sesuaikan `SPOTIFY_REDIRECT_URI` dengan domain production | `.env` + Spotify Dashboard |
| 5 | Pastikan `.env` **tidak pernah** masuk ke Git | `.gitignore` (sudah dikonfigurasi) |

---

## 🔄 Alur Data

```
User → Login Spotify OAuth
        ↓
    AuthController (callback)
        ↓ simpan token ke session
    DashboardController (index)
        ├── GET /me              → Profil user
        ├── GET /me/top/artists  → Top 10 artis
        ├── GET /me/top/tracks   → Top 50 lagu
        ├── GET /v1/artists      → Detail genre artis
        └── POST Gemini AI       → Analisis kepribadian
        ↓
    dashboard.blade.php (tampilkan hasil)
```

---

## 📦 Caching Strategy

| Data | TTL | Key |
|---|---|---|
| Gemini AI Analysis | 5 menit | `gemini_analysis_{spotifyId}` |
| Spotify Access Token (Client Credentials) | ~58 menit | `spotify_access_token` |
