# SpotiPeek 🎧✨

**Uncover Your Music Taste.**

SpotiPeek is a web application that analyzes your Spotify listening habits. Simply log in with your Spotify account, and SpotiPeek will display your personal statistics in a **Spotify Wrapped-style** dashboard — complete with an AI-powered music personality analysis.

---

## ✨ Features

| Feature | Description |
|---|---|
| 🔐 **Spotify Login** | Secure authentication via Spotify OAuth 2.0 |
| 🎤 **Top Artists** | Your 5 most listened-to artists this month |
| 🎵 **Top Songs** | The 5 tracks dominating your playlist |
| 🎸 **Top Genre** | Your favorite genre based on listening history |
| ⏱️ **Minutes Listened** | Estimated total listening time |
| 🤖 **AI Music Personality** | Personality analysis powered by Google Gemini AI |
| 🖼️ **Wrapped-Style Card** | Dashboard with a Spotify Wrapped aesthetic |
| 🎶 **Spotify Player** | Floating mini player for your top track |

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11.x (PHP)
- **Frontend:** Blade Templates + Tailwind CSS
- **Build Tool:** Vite
- **Database:** Supabase (PostgreSQL)
- **Auth:** Laravel Socialite + Spotify OAuth
- **AI:** Google Gemini API (Multi-model fallback)

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

# 3. Configure .env (see app/README.md for details)

# 4. Run database migrations
php artisan migrate:fresh

# 5. Start the app
npm run dev
php artisan serve
```

> 📖 **Full installation and technical configuration guide** available at [`app/README.md`](app/README.md)

---

## 🔒 Security

SpotiPeek implements the following security practices:
- ✅ Automatic SSL verification in production
- ✅ API keys sent via HTTP Headers (not URL query strings)
- ✅ Routes protected by authentication middleware
- ✅ Rate limiting on all sensitive endpoints
- ✅ Encrypted sessions
- ✅ Error handling without leaking internal information

---

**© 2026 SpotiPeek. All Rights Reserved.**
