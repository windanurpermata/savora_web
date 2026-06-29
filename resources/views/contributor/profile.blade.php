@extends('layouts.app')

@section('title', 'Profil Chef ' . $contributor->name)

@section('content')

    {{-- ===== HEADER PROFIL ===== --}}
    <section class="bg-cokelat-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6">

                {{-- Foto Chef --}}
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-cokelat-600 flex-shrink-0 bg-cokelat-700">
                    @if ($contributor->foto)
                        <img src="{{ asset('storage/' . $contributor->foto) }}" alt="{{ $contributor->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-3xl text-cokelat-300">
                            👨‍🍳
                        </div>
                    @endif
                </div>

                {{-- Info Chef --}}
                <div class="text-center sm:text-left">
                    <p class="text-cokelat-400 text-xs uppercase tracking-widest mb-1">Chef</p>
                    <h1 class="font-serif text-2xl md:text-3xl text-cokelat-50 mb-1">{{ $contributor->name }}</h1>
                    @if ($contributor->bio)
                        <p class="text-cokelat-300 text-sm max-w-xl">{{ $contributor->bio }}</p>
                    @endif
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-1 sm:gap-4 mt-2">
                        <p class="text-cokelat-500 text-xs">{{ $reseps->count() }} resep dipublikasikan</p>
                        <p class="text-cokelat-500 text-xs">
                            Bergabung sejak {{ $contributor->created_at->translatedFormat('F Y') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===== GRID RESEP ===== --}}
    <section class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="font-serif text-xl text-cokelat-800 mb-6">Resep dari {{ $contributor->name }}</h2>

            @if ($reseps->isEmpty())
                <div class="text-center py-20">
                    <div class="text-5xl mb-4">🍽</div>
                    <p class="font-serif text-xl text-cokelat-700 mb-2">Contributor ini belum memiliki resep</p>
                    <p class="text-cokelat-400 text-sm">Nantikan resep dari contributor ini ya!</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                    @foreach ($reseps as $resep)
                        <a href="{{ route('recipes.show', $resep->id) }}"
                            class="bg-white rounded-xl overflow-hidden border border-cokelat-100
                                  hover:-translate-y-1 transition-transform shadow-sm group">

                            {{-- Gambar --}}
                            <div class="relative h-36 sm:h-40 bg-cokelat-100 overflow-hidden">
                                @if ($resep->gambar)
                                    <img src="{{ asset('storage/' . $resep->gambar) }}" alt="{{ $resep->judul }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl">
                                        🍴
                                    </div>
                                @endif

                                {{-- Badge Kategori --}}
                                @if ($resep->kategori)
                                    <span
                                        class="absolute top-2 left-2 bg-cokelat-800/70 backdrop-blur-sm
                                                 text-cokelat-50 text-xs px-2 py-0.5 rounded-full">
                                        {{ $resep->kategori->emoji ?? '' }} {{ $resep->kategori->nama }}
                                    </span>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-3">
                                <h3
                                    class="font-serif text-cokelat-800 font-semibold text-sm
                                           leading-snug mb-1 line-clamp-2">
                                    {{ $resep->judul }}
                                </h3>
                                <p class="text-cokelat-400 text-xs mb-2 line-clamp-2">
                                    {{ Str::limit($resep->deskripsi, 60) }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-cokelat-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $resep->waktu_memasak }} mnt
                                    </span>
                                    <span>{{ $resep->porsi }} porsi</span>
                                </div>
                            </div>

                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

@endsection
