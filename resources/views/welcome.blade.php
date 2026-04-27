@extends('layouts.app')

@section('content')
<div class="grow flex flex-col items-center justify-center text-center space-y-10 mt-10 md:mt-20">

    <!-- Hero Title --> 
    <div class="space-y-4">
        <h1 class="text-4xl md:text-6xl font-extrabold trackiing-tight text-white drop-shadow-lg">
            Bongkar Selera Musik <br class="hidden md:block">
            <span class="text-emerald-500">Siapa Saja.</span>
        </h1>
        <p class="text-lg text-zinc-400 max-w-xl mx-auto font-medium">
            Kepo dengan musik yang sering didengar gebetan atau idola kamu? Masukkan *username* Spotify Mereka di bawah ini. 
        </p>
    </div>

    <!-- Search Form -->
    <div class="w-full max-w-xl">
        <form action="{{ route('search') }}" method="POST" class="relative group">
            @csrf

            <!-- Ikon Kaca Pembesar --> 
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <svg class="h-6 w-6 text-zinc-500 group-focus-within:text-emerald-500 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Input Form --> 
            <input
                type="text"
                name="username"
                placeholder="Username atau Link Profil Spotify..."
                required
                autocomplete="off"
                class="w-full bg-zinc-900 border border-zinc-800 text-zinc-100 rounded-full py-5 pl-14 pr-36 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all placeholder:text-zinc-600 shadow-2xl text-lg"
            >

            <!-- Tombol Submit --> 
            <button 
                type="submit"
                class="absolute inset-y-2 right-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-full px-8 transition-all duration-300 shadow-lg hover:shadow-emerald-500/30 flex items-center justify-center"
            >
                Cari Sekarang
            </button>
        </form>

        <!-- Throw error jika form kosong atau salah -->
        @error('username')
        <p class="text-red-500 text-sm mt-4 font-medium">
            {{ $message }}
            @enderror
        </p>
    </div>

    <!-- Badge fitur tambahan --> 
    <div class="flex flex-wrap justify-center gap-4 mt-8 opacity-80">
        <div class="flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-5 py-2 text-sm text-zinc-300 font-medium shadow-sm">
            <span class="text-emerald-550">✓</span> Tanpa Login
        </div>
        <div class="flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-5 py-2 text-sm text-zinc-300 font-medium shadow-sm">
            <span class="text-emerald-550">✓</span> 100% Gratis
        </div>
        <div class="flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-full px-5 py-2 text-sm text-zinc-300 font-medium shadow-sm">
            <span class="text-emerald-550">✓</span> Real-Time API
        </div>
    </div>
</div>
@endsection