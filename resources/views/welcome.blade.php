@extends('layouts.app')

@section('content')
<div class="grow flex flex-col items-center justify-center text-center space-y-10 mt-10 md:mt-20">

    <!-- Hero Title --> 
    <div class="space-y-4">
        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-white drop-shadow-lg">
            Bongkar Selera Musik <br class="hidden md:block">
            <span class="text-emerald-500">Anda Sendiri.</span>
        </h1>
        <p class="text-lg text-zinc-400 max-w-xl mx-auto font-medium">
            Kepo dengan lagu dan artis yang paling sering Anda dengar? Hubungkan akun Spotify Anda dengan aman untuk melihat statistik aslinya!
        </p>
    </div>

    <!-- Login Button -->
    <div class="w-full max-w-md pt-4">
        <a href="{{ route('spotify.login') }}" class="group relative inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 font-bold text-white transition-all duration-300 bg-emerald-600 rounded-full hover:bg-emerald-500 hover:shadow-[0_0_40px_rgba(16,185,129,0.4)] focus:outline-none focus:ring-4 focus:ring-emerald-500/50">
            <!-- Spotify Icon -->
            <svg class="w-6 h-6 mr-3 transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.54.659.301 1.02zm1.44-3.3c-.301.42-.84.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.6.18-1.2.72-1.38 4.08-1.32 11.16-1.08 16.02 1.8.54.301.72.96.42 1.5-.299.54-.959.72-1.5.42z"/></svg>
            Masuk dengan Spotify
        </a>
    </div>

    <!-- Badge fitur tambahan --> 
    <div class="flex flex-wrap justify-center gap-4 mt-8 opacity-80">
        <div class="flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-5 py-2 text-sm text-zinc-300 font-medium shadow-sm">
            <span class="text-emerald-500">✓</span> 100% Aman & Pribadi
        </div>
        <div class="flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-5 py-2 text-sm text-zinc-300 font-medium shadow-sm">
            <span class="text-emerald-500">✓</span> Data Valid
        </div>
    </div>
</div>
@endsection
