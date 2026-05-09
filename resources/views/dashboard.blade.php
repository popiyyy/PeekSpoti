@extends('layouts.app')

@section('content')
    @if(isset($error))
        <div class="flex flex-col items-center justify-center text-center space-y-6 py-20">
            <div class="bg-red-500/10 border border-red-500/50 text-red-500 px-8 py-6 rounded-2xl max-w-lg shadow-lg">
                <h3 class="text-xl font-bold mb-2 flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Oops! Ada Masalah
                </h3>
                <p class="text-red-400 font-medium">{{ $error }}</p>
            </div>
            <a href="{{ route('home') }}"
                class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 text-white rounded-full transition-colors font-medium border border-zinc-700 hover:border-zinc-600">
                Kembali ke Beranda
            </a>
        </div>
    @else

    <style>
        /* Starburst animasi di belakang foto artis */
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.7; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }
        .animate-spin-slow { animation: spin-slow 20s linear infinite; }
        .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
    </style>

    <div class="flex flex-col items-center gap-8 pt-6 md:pt-10">

        <!-- Header: Nama User + Logout -->
        <div class="w-full max-w-md flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full ring-2 ring-emerald-500/40">
                @endif
                <div>
                    <h1 class="text-lg font-bold text-white leading-tight">{{ $user->display_name }}</h1>
                    <p class="text-xs text-zinc-500">Your Music Recap</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 text-zinc-400 hover:text-white text-xs font-semibold rounded-full transition-all">
                    Keluar
                </button>
            </form>
        </div>

        <!-- Kartu Utama Spotify Wrapped -->
        <div class="w-full max-w-md bg-zinc-900 rounded-3xl p-6 pt-8 pb-8 shadow-2xl border border-zinc-800/60 relative overflow-hidden">

            <!-- Starburst + Foto Artis #1 -->
            <div class="flex justify-center mb-8">
                <div class="relative w-52 h-52 md:w-64 md:h-64 flex items-center justify-center">
                    <!-- Layer: Starburst Background -->
                    <svg class="absolute w-full h-full animate-spin-slow animate-pulse-glow" viewBox="0 0 200 200" fill="none">
                        <polygon points="100,5 115,70 180,15 130,75 195,100 130,125 180,185 115,130 100,195 85,130 20,185 70,125 5,100 70,75 20,15 85,70"
                            fill="url(#starburst-gradient)" />
                        <defs>
                            <linearGradient id="starburst-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#facc15" />
                                <stop offset="40%" style="stop-color:#f97316" />
                                <stop offset="100%" style="stop-color:#7c3aed" />
                            </linearGradient>
                        </defs>
                    </svg>

                    <!-- Layer: Warna Radial -->
                    <div class="absolute w-[75%] h-[75%] rounded-full bg-gradient-to-br from-violet-600 via-purple-700 to-indigo-900 opacity-90"></div>

                    <!-- Layer: Foto Artis -->
                    @if($topArtistImage)
                        <img src="{{ $topArtistImage }}" alt="Top Artist"
                            class="relative z-10 w-[60%] h-[60%] object-cover rounded-xl shadow-2xl border-2 border-zinc-700/50">
                    @else
                        <div class="relative z-10 w-[60%] h-[60%] bg-zinc-800 rounded-xl flex items-center justify-center text-5xl border-2 border-zinc-700/50">
                            🎤
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dua Kolom: Top Artists + Top Songs -->
            @php
                $artists = is_string($analysis->top_artists_json) ? json_decode($analysis->top_artists_json, true) : $analysis->top_artists_json;
                $topArtistNames = $artists ? array_slice(array_keys($artists), 0, 5) : [];
            @endphp

            <div class="grid grid-cols-2 gap-x-6 gap-y-0 mb-6">
                <!-- Top Artists -->
                <div>
                    <h3 class="text-zinc-400 text-sm font-medium mb-3">Top Artists</h3>
                    @forelse($topArtistNames as $index => $name)
                        <p class="text-white font-bold text-sm leading-7 truncate">
                            <span class="text-emerald-500">{{ $index + 1 }}</span>
                            {{ Str::limit($name, 16) }}
                        </p>
                    @empty
                        <p class="text-zinc-500 text-sm italic">Belum ada data</p>
                    @endforelse
                </div>

                <!-- Top Songs -->
                <div>
                    <h3 class="text-zinc-400 text-sm font-medium mb-3">Top Songs</h3>
                    @forelse($topTracks as $index => $track)
                        <p class="text-white font-bold text-sm leading-7 truncate">
                            <span class="text-emerald-500">{{ $index + 1 }}</span>
                            {{ Str::limit($track, 16) }}
                        </p>
                    @empty
                        <p class="text-zinc-500 text-sm italic">Belum ada data</p>
                    @endforelse
                </div>
            </div>

            <!-- Divider -->
            <div class="w-full h-px bg-zinc-800 my-4"></div>

            <!-- Top Genre -->
            <div class="grid grid-cols-2 gap-x-6">
                <div>
                    <h3 class="text-zinc-400 text-sm font-medium mb-1">Minutes Listened</h3>
                    <p class="text-3xl font-extrabold text-emerald-400">
                        {{ number_format($minutesListened ?? 0) }}
                    </p>
                </div>
                <div>
                    <h3 class="text-zinc-400 text-sm font-medium mb-1">Top Genre</h3>
                    <p class="text-3xl font-extrabold text-emerald-400">{{ $topGenre }}</p>
                </div>
            </div>
        </div>

        <!-- Kartu AI Music Personality -->
        @if(isset($aiAnalysis))
        <div class="w-full max-w-md bg-gradient-to-br from-emerald-950/60 to-zinc-900 p-6 rounded-3xl border border-emerald-500/20 shadow-[0_0_40px_rgba(16,185,129,0.08)] relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 text-emerald-500/10 transition-transform duration-700 group-hover:rotate-12 group-hover:scale-110">
                <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-emerald-400 flex items-center gap-2 mb-3">
                ✨ AI Music Personality
            </h3>
            <p class="text-zinc-300 leading-relaxed relative z-10 text-sm italic">
                {{ str_replace(['"', '*'], '', $aiAnalysis) }}
            </p>
        </div>
        @endif

        <!-- Daftar Lengkap Artis (Expandable) -->
        @if(isset($artistMinutes) && count($artistMinutes) > 0)
        <div class="w-full max-w-md">
            <details class="group">
                <summary class="cursor-pointer text-center text-sm text-zinc-500 hover:text-emerald-400 transition-colors font-medium py-3 list-none flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    Lihat Semua Artis ({{ count($artistMinutes) }})
                </summary>
                <div class="grid grid-cols-1 gap-2 mt-3">
                    @foreach($artistMinutes as $name => $minutes)
                        <div class="flex items-center justify-between bg-zinc-900/80 px-4 py-3 rounded-xl border border-zinc-800/60 hover:border-emerald-500/30 transition-colors">
                            <span class="font-semibold text-sm text-zinc-200">
                                <span class="text-emerald-500 mr-2">{{ $loop->iteration }}.</span>{{ $name }}
                            </span>
                            <span class="text-xs font-medium text-zinc-500 bg-zinc-800 px-2.5 py-1 rounded-md">
                                {{ $minutes }} menit
                            </span>
                        </div>
                    @endforeach
                </div>
            </details>
        </div>
        @endif

    </div>

    @endif
@endsection