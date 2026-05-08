<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpotiPeek</title>
    <link rel="icon" type="image/png" href="{{ asset('spotify.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans antialiased min-h-screen flex flex-col">
    
    <!-- Navbar -->
    <nav class="w-full border-b border-zinc-900 bg-zinc-950/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-emerald-500 flex items-center gap-2 transition hover:text-emerald-400">
                <!-- Ikon Spotify -->
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.54.659.301 1.02zm1.44-3.3c-.301.42-.84.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.6.18-1.2.72-1.38 4.08-1.32 11.16-1.08 16.02 1.8.54.301.72.96.42 1.5-.299.54-.959.72-1.5.42z"/></svg>
                SpotiPeek
            </a>

        </div>
    </nav>

    <!-- Kontainer Konten Utama -->
    <main class="grow flex flex-col max-w-4xl mx-auto w-full px-6 py-12">
        <!-- Ini nanti akan diganti halaman welcome atau dashboard -->
        @yield('content')
    </main>

    <!-- Footer --> 
    <footer class="border-t border-zinc-900 py-6 text-center text-zinc-600 text-sm">
        <p>&copy; {{ date('Y') }} SpotiPeek. Bukan layanan resmi yang berafiliasi dengan Spotify.</p>
    </footer>
</body>
</html>