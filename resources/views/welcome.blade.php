@extends('layouts.app')

@section('content')

<!-- Animasi Marquee Album/Penyanyi sebagai Background -->
<style>
    @keyframes marquee-left {
        0% { transform: translateX(0%); }
        100% { transform: translateX(-50%); }
    }
    @keyframes marquee-right {
        0% { transform: translateX(-50%); }
        100% { transform: translateX(0%); }
    }
    .animate-marquee-left {
        animation: marquee-left 40s linear infinite;
    }
    .animate-marquee-right {
        animation: marquee-right 40s linear infinite;
    }
</style>

<div class="fixed inset-0 w-full h-full overflow-hidden -z-10 bg-zinc-950 pointer-events-none flex flex-col justify-center gap-6 opacity-20 blur-[2px]">
    @php
        $images = [
            'https://images.unsplash.com/photo-1614613535308-eb5fbd3d2c17?q=80&w=400&h=400&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?q=80&w=400&h=400&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1493225457124-a1a2a5ea2eb3?q=80&w=400&h=400&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=400&h=400&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=400&h=400&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?q=80&w=400&h=400&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1521335629791-ce4aec67dd15?q=80&w=400&h=400&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?q=80&w=400&h=400&auto=format&fit=crop',
        ];
        $marqueeImages = array_merge($images, $images, $images);
    @endphp

    <!-- Baris 1: Bergerak ke Kiri -->
    <div class="flex gap-6 w-max animate-marquee-left -rotate-6 scale-110 translate-y-[-10vh]">
        @foreach($marqueeImages as $index => $img)
            <div class="w-40 h-40 md:w-56 md:h-56 flex-shrink-0 rounded-3xl overflow-hidden shadow-2xl grayscale">
                <img src="{{ $img }}" class="w-full h-full object-cover">
            </div>
        @endforeach
    </div>

    <!-- Baris 2: Bergerak ke Kanan -->
    <div class="flex gap-6 w-max animate-marquee-right -rotate-6 scale-110 translate-y-[5vh]">
        @foreach($marqueeImages as $index => $img)
            <div class="w-40 h-40 md:w-56 md:h-56 flex-shrink-0 rounded-3xl overflow-hidden shadow-2xl grayscale">
                <img src="{{ $img }}" class="w-full h-full object-cover">
            </div>
        @endforeach
    </div>
</div>

<div class="grow flex flex-col items-center justify-center text-center space-y-10 mt-10 md:mt-20 relative z-10 px-4">

    <!-- Hero Title --> 
    <div class="space-y-6">
        <h1 class="text-5xl md:text-7xl font-serif font-semibold tracking-tight text-zinc-100 leading-tight drop-shadow-2xl">
            Bongkar Selera Musik <br class="hidden md:block">
            <span class="text-emerald-500 italic">Anda Sendiri.</span>
        </h1>
        <p class="text-lg md:text-xl text-zinc-300 max-w-2xl mx-auto font-light leading-relaxed drop-shadow-md">
            Kepo dengan lagu dan artis yang paling sering Anda dengar? Hubungkan akun Spotify Anda dengan aman untuk melihat statistik aslinya!
        </p>
    </div>

    <!-- Login Button -->
    <div class="w-full max-w-md pt-8">
        <a href="{{ route('spotify.login') }}" class="group relative inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 font-semibold text-white transition-all duration-300 bg-emerald-600 rounded-full hover:bg-emerald-500 hover:scale-105 shadow-[0_0_40px_rgba(16,185,129,0.4)] focus:outline-none">
            <!-- Spotify Icon -->
            <svg class="w-6 h-6 mr-3 transition-transform duration-300 group-hover:rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.54.659.301 1.02zm1.44-3.3c-.301.42-.84.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.6.18-1.2.72-1.38 4.08-1.32 11.16-1.08 16.02 1.8.54.301.72.96.42 1.5-.299.54-.959.72-1.5.42z"/></svg>
            Masuk dengan Spotify
        </a>
    </div>

</div>

@endsection
