# Technical Documentation SpotiPeek

This document contains the full technical guide for developers who want to run, develop, or contribute to the SpotiPeek project.

---

## Prerequisites

Make sure you have the following installed:
- PHP >= 8.2 (with the `fileinfo` extension enabled)
- Composer
- Node.js & npm
- A [Spotify Developer](https://developer.spotify.com/dashboard) account (for OAuth)
- A [Google AI Studio](https://aistudio.google.com/app/apikey) account (for Gemini API Key)
- A [Supabase](https://supabase.com/) project (for PostgreSQL database)

---

## Installation Steps

### 1. Clone & Install Dependencies
```bash
git clone https://github.com/popiyyy/SpotiPeek.git
cd SpotiPeek
composer install
npm install
```

### 2. Configure Environment (`.env`)
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database (Supabase)
Update the database configuration in your `.env` file using the Connection Pooler URL from Supabase:
```env
DB_CONNECTION=pgsql
DB_URL="postgresql://postgres.projectname:YourPassword@aws-0-ap-southeast-1.pooler.supabase.com:6543/postgres"
```

### 4. Setup Spotify API
1. Go to the [Spotify Developer Dashboard](https://developer.spotify.com/dashboard).
2. Create a new application and obtain the **Client ID** and **Client Secret**.
3. Add a Redirect URI (adjust to your local domain):
   ```
   http://spotipeek.test/auth/callback
   ```
4. Add the credentials to your `.env`:
```env
SPOTIFY_CLIENT_ID=your_client_id
SPOTIFY_CLIENT_SECRET=your_client_secret
SPOTIFY_REDIRECT_URI=http://spotipeek.test/auth/callback
```

### 5. Setup Gemini AI API
1. Get a free API Key from [Google AI Studio](https://aistudio.google.com/app/apikey).
2. Add it to your `.env`:
```env
GEMINI_API_KEY=your_gemini_api_key
```

### 6. Run Database Migrations
```bash
php artisan migrate:fresh
```
> *Note: You can safely ignore the "RLS Disabled in Public" warning in Supabase Security Advisor — security is fully handled by the Laravel backend.*

### 7. Start the Application
```bash
# Terminal 1: Compile CSS/JS assets
npm run dev

# Terminal 2: PHP server (if not using Laragon/Herd)
php artisan serve
```

Access at: `http://spotipeek.test` (Laragon) or `http://localhost:8000` (artisan serve).

---

## Project Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      # Spotify OAuth Login/Logout
│   │   └── DashboardController.php  # Fetch Spotify data + Gemini AI
│   └── Middleware/
│       └── EnsureSpotifyToken.php   # Dashboard route protection
├── Models/
│   └── SearchedUser.php             # User database model
├── Services/
│   └── SpotifyAnalyzerService.php   # Spotify API Client Credentials
└── README.md                        # (This file)
```

---

## Key Configuration

### `config/services.php`
All third-party credentials are centralized here:
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
Custom middleware is registered here:
```php
$middleware->alias([
    'spotify.auth' => \App\Http\Middleware\EnsureSpotifyToken::class,
]);
```

### `routes/web.php`
Routes protected by middleware and rate limiting:
```php
// OAuth (max 10 req/min)
Route::middleware('throttle:10,1')->group(function () {
    Route::get('/auth/redirect', ...)->name('spotify.login');
    Route::get('/auth/callback', ...)->name('spotify.callback');
});

// Dashboard (requires login + max 30 req/min)
Route::get('/dashboard', ...)
    ->middleware(['spotify.auth', 'throttle:30,1']);
```

---

## Production Security Checklist

Before deploying to a public server, ensure the following:

| # | Action | Location |
|---|---|---|
| 1 | Set `APP_DEBUG=false` | `.env` |
| 2 | Ensure `SESSION_ENCRYPT=true` | `.env` |
| 3 | SSL bypass (`withoutVerifying()`) is automatically disabled when `APP_ENV=production` | `DashboardController.php`, `SpotifyAnalyzerService.php` |
| 4 | Update `SPOTIFY_REDIRECT_URI` to match production domain | `.env` + Spotify Dashboard |
| 5 | Ensure `.env` is **never** committed to Git | `.gitignore` (already configured) |

---

## Data Flow

```
User → Spotify OAuth Login
        ↓
    AuthController (callback)
        ↓ store token in session
    DashboardController (index)
        ├── GET /me              → User profile
        ├── GET /me/top/artists  → Top 10 artists
        ├── GET /me/top/tracks   → Top 50 tracks
        ├── GET /v1/artists      → Artist genre details
        └── POST Gemini AI       → Personality analysis
        ↓
    dashboard.blade.php (render results)
```

---

## Caching Strategy

| Data | TTL | Cache Key |
|---|---|---|
| Gemini AI Analysis | 5 minutes | `gemini_analysis_{spotifyId}` |
| Spotify Access Token (Client Credentials) | ~58 minutes | `spotify_access_token` |
