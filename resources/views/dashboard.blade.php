@extends('layouts.app')

@section('content')
@if(isset($error))
    <div class="flex flex-col items-center justify-center text-center space-y-6 py-20">
        <div class="bg-red-500/10 border border-red-500/50 text-red-500 px-8 py-6 rounded-2xl max-w-lg shadow-lg">
            <h3 class="text-xl font-bold mb-2 flex items-center justify-center gap-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                Oops! Ada Masalah
            </h3>
            <p class="text-red-400 font-medium">{{ $error }}</p>
        </div>
        <a href="{{ route('home') }}" class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 text-white rounded-full transition-colors font-medium border border-zinc-700 hover:border-zinc-600">
            Kembali ke Pencarian
        </a>
    </div>
@else
    <div class="space-y-12">
        <!-- Profil Pengguna -->
        <div class="flex flex-col md:flex-row items-center gap-6 bg-zinc-900/50 p-6 rounded-3xl border border-zinc-800/80 shadow-2xl backdrop-blur-sm">
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-28 h-28 rounded-full shadow-lg ring-4 ring-zinc-800/50"> 
            @else
                <div class="w-28 h-28 rounded-full bg-zinc-800 flex items-center justify-center ring-4 ring-zinc-800/50">
                    🧑‍🎤
                </div>
            @endif 

            <div class="text-center md:text-left space-y-2">
                <h1 class="text-3xl font-bold tracking-tight text-white">{{ $user->display_name }}</h1>
                <p class="text-emerald-500 font-medium flex items-center justify-center md:justify-start gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Dianalisis dari {{ $user->total_public_playlist }} Playlist Publik
                </p>
            </div>

            <div class="md:ml-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 hover:text-red-300 text-sm font-semibold rounded-full transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Hasil Analisis -->
        <div class="space-y-6">
            <h2 class="text-2xl font-bold border-zinc-900 pb-4 text-white">
                🏆 Artis Paling Sering Didengar
            </h2>

            @php
                $artists = is_string($analysis->top_artists_json) ? json_decode($analysis->top_artists_json, true) : $analysis->top_artists_json;
            @endphp

            @if($artists && count($artists) > 0)
                @php
                    $maxMentions = max($artists)
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($artists as $name => $count)
                    <div class="bg-zinc-900/60 p-4 rounded-2xl border border-zinc-800 hover:border-emerald-500/50 transition-colors group">
                        <div class="flex justify-between items-end mb-3">
                            <span class="font-bold text-lg text-zinc-100 group-hover:text-emerald-400 transition-colors">
                                {{ $loop->iteration }}. {{ $name }}
                            </span>
                            <span class="text-sm font-medium text-zinc-500 bg-zinc-950 px-2.5 py-1 rounded-md">
                                {{ $count }} Lagu
                            </span>
                        </div>

                        <!-- Progress Bar Visualisasi -->
                        <div class="w-full bg-zinc-950 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ ($count / $maxMentions) * 100 }}%">
                            </div>
                        </div>
                    </div>
            
                    @endforeach 
                </div>
            
            @else
            <div class="text-center py-12 bg-zinc-900/30 rounded 3xl border border-zinc-800 border-dashed ">
                <p class="text-zinc-400 text-lg">
                    Wah, sepertinya tidak ada artis yang bisa dianalisis dari playlist publiknya.
                </p>
            </div>
            @endif 
        </div>
        </div>
    </div>
@endif
@endsection