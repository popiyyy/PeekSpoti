# SpotiPeek

**Bongkar Selera Musik Anda Sendiri.**

SpotiPeek adalah aplikasi web yang menganalisis kebiasaan mendengarkan musik Anda di Spotify. Cukup login dengan akun Spotify, dan SpotiPeek akan menampilkan statistik personal Anda lengkap dengan analisis kepribadian musik dari AI.

---

## ✨ Fitur

| Fitur | Deskripsi |
|---|---|
| 🔐 **Login Spotify** | Autentikasi aman via Spotify OAuth 2.0 |
| 🎤 **Top Artists** | 5 artis yang paling sering Anda dengar bulan ini |
| 🎵 **Top Songs** | 5 lagu yang mendominasi playlist Anda |
| 🎸 **Top Genre** | Genre musik favorit berdasarkan lagu-lagu Anda |
| ⏱️ **Minutes Listened** | Estimasi total menit mendengarkan |
| 🤖 **AI Music Personality** | Analisis kepribadian dari Google Gemini AI |
| 🖼️ **Wrapped-Style Card** | Tampilan dashboard bergaya Spotify Wrapped |
| 🎶 **Spotify Player** | Mini player mengambang untuk memutar lagu teratas |

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11.x (PHP)
- **Frontend:** Blade Templates + Tailwind CSS
- **Build Tool:** Vite
- **Database:** Supabase (PostgreSQL)
- **Auth:** Laravel Socialite + Spotify OAuth
- **AI:** Google Gemini API (Multi-model fallback)

---

## 🖼️ Preview

### Halaman Utama
> Landing page dengan animasi background album cover yang mengalir.

### Dashboard
> Kartu recap bergaya Spotify Wrapped akan menampilkan artis, lagu, genre, dan analisis AI.

---

## 🚀 Quick Start

```bash
# 1. Clone & install
git clone https://github.com/popiyyy/SpotiPeek.git
cd SpotiPeek
composer install && npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Konfigurasi .env (lihat app/README.md untuk detail)

# 4. Migrasi database
php artisan migrate:fresh

# 5. Jalankan
npm run dev
php artisan serve
```

---

## 🔒 Keamanan

SpotiPeek menerapkan praktik keamanan berikut:
- ✅ SSL verification otomatis di production
- ✅ API Key dikirim via HTTP Header (bukan URL)
- ✅ Route dilindungi middleware autentikasi
- ✅ Rate limiting pada semua endpoint sensitif
- ✅ Session terenkripsi
- ✅ Error handling tanpa kebocoran informasi internal

---

**© 2026 SpotiPeek. All Rights Reserved.**
