<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpotiPeek</title>
    <link rel="icon" type="image/png" href="{{ asset('spotify.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Menggabungkan 2 font: Roboto untuk teks biasa, Montserrat untuk Judul */
        body { font-family: 'Roboto', sans-serif; }
        .font-serif { font-family: 'Montserrat', sans-serif !important; }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans antialiased min-h-screen flex flex-col">
    
    <!-- Navbar -->
    <nav class="w-full bg-transparent pt-6 pb-4 absolute top-0 left-0 z-50">
        <div class="w-full px-8 md:px-12 flex items-center">
            <a href="{{ route('home') }}" class="text-xl font-serif text-zinc-100 flex items-center gap-2.5 transition hover:opacity-80">
                <!-- Ikon Spotify -->
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.54.659.301 1.02zm1.44-3.3c-.301.42-.84.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.6.18-1.2.72-1.38 4.08-1.32 11.16-1.08 16.02 1.8.54.301.72.96.42 1.5-.299.54-.959.72-1.5.42z"/></svg>
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
    <footer class="py-6 text-center text-zinc-600 text-sm">
        <p>&copy; {{ date('Y') }} SpotiPeek. All Right Reserved.</p>
    </footer>
</body>
</html>