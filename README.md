# SpotiPeek 🎧✨

SpotiPeek adalah aplikasi web interaktif yang memungkinkan pengguna untuk menganalisis selera musik mereka secara personal. Dengan login menggunakan akun Spotify, aplikasi ini akan mengambil data statistik pendengaran (Top Artists) dan menggunakan kecerdasan buatan (Google Gemini AI) untuk memberikan analisis kepribadian musik yang asik dan *relatable*.

## 🚀 Fitur Utama

- **Autentikasi Aman:** Login terintegrasi menggunakan Spotify OAuth 2.0 (Laravel Socialite).
- **Statistik Personal:** Menampilkan Top Artists yang paling sering didengarkan oleh pengguna dalam 6 bulan terakhir.
- **AI Music Personality:** Analisis kepribadian instan berbasis AI menggunakan Google Gemini 1.5 Flash.
- **Visualisasi Menarik:** Progress bar dinamis bergaya modern dengan Tailwind CSS.
- **Cloud Database:** Penyimpanan data terintegrasi penuh dengan Supabase (PostgreSQL).

## 🛠️ Teknologi yang Digunakan

- **Backend:** [Laravel 11.x](https://laravel.com/) (PHP)
- **Frontend:** Blade Templates, [Tailwind CSS](https://tailwindcss.com/)
- **Build Tool:** [Vite](https://vitejs.dev/)
- **Database:** [Supabase](https://supabase.com/) (PostgreSQL)
- **Autentikasi:** Laravel Socialite + Provider Spotify
- **AI Integration:** Google Gemini API

---

## 💻 Persiapan Development (Prerequisites)

Sebelum menjalankan proyek ini di lokal (seperti Laragon, XAMPP, atau Laravel Herd), pastikan Anda telah menginstal:
- PHP >= 8.2 (dengan ekstensi `fileinfo` aktif)
- Composer
- Node.js & npm
- Akun Spotify Developer (untuk OAuth)
- Akun Google AI Studio (untuk API Key Gemini)
- Proyek Supabase (untuk Database)

---

## ⚙️ Langkah Instalasi

### 1. Clone & Install Dependencies
Buka terminal dan jalankan perintah berikut:
```bash
git clone <repository-url>
cd SpotiPeek
composer install
npm install
```

### 2. Konfigurasi Environment (`.env`)
Gandakan file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Generate APP_KEY Laravel:
```bash
php artisan key:generate
```

### 3. Setup Database (Supabase)
Ubah konfigurasi database di file `.env` Anda menggunakan Connection Pooler URL dari Supabase:
```env
DB_CONNECTION=pgsql
# Masukkan password Supabase Anda tanpa tanda kurung siku
DB_URL="postgresql://postgres.namaproyek:PasswordAnda@aws-0-ap-southeast-1.pooler.supabase.com:6543/postgres"
```

### 4. Setup Spotify API
1. Buka [Spotify Developer Dashboard](https://developer.spotify.com/dashboard).
2. Buat aplikasi baru dan dapatkan **Client ID** serta **Client Secret**.
3. Tambahkan `http://spotipeek.test/auth/callback` (sesuaikan dengan domain lokal Anda) ke bagian **Redirect URIs** di dashboard Spotify.
4. Masukkan kredensial ke file `.env`:
```env
SPOTIFY_CLIENT_ID=client_id_anda_disini
SPOTIFY_CLIENT_SECRET=client_secret_anda_disini
SPOTIFY_REDIRECT_URI=http://spotipeek.test/auth/callback
```

### 5. Setup Gemini AI API
1. Dapatkan API Key gratis di [Google AI Studio](https://aistudio.google.com/app/apikey).
2. Tambahkan ke file `.env`:
```env
GEMINI_API_KEY=api_key_gemini_anda_disini
```

### 6. Migrasi Database
Jalankan migrasi untuk membuat tabel yang dibutuhkan di Supabase:
```bash
php artisan migrate:fresh
```
*(Catatan: Anda bisa mengabaikan peringatan "RLS Disabled in Public" di Security Advisor Supabase, karena keamanan ditangani penuh oleh backend Laravel).*

### 7. Jalankan Aplikasi
Jalankan Vite untuk meng-compile aset CSS/JS:
```bash
npm run dev
```
Jika Anda tidak menggunakan Laragon/Herd, jalankan server PHP bawaan di tab terminal baru:
```bash
php artisan serve
```

Aplikasi sekarang dapat diakses di `http://spotipeek.test` (Laragon) atau `http://localhost:8000` (artisan serve).

---

## 🔒 Catatan Keamanan untuk Production

Jika aplikasi ini akan di-hosting secara publik (Production), pastikan Anda melakukan penyesuaian berikut:
1. Ubah `APP_DEBUG=true` menjadi `APP_DEBUG=false` di `.env`.
2. Aktifkan enkripsi session dengan mengatur `SESSION_ENCRYPT=true` di `.env`.
3. Hapus metode `withoutVerifying()` dari `Http` client di `DashboardController.php` dan pastikan server Anda memiliki konfigurasi sertifikat SSL (CA Bundle) yang valid.
4. Sesuaikan `SPOTIFY_REDIRECT_URI` di `.env` dan di Spotify Dashboard dengan domain asli web Anda (contoh: `https://spotipeek.com/auth/callback`).

---

**SpotiPeek. All Rights Reserved.**
