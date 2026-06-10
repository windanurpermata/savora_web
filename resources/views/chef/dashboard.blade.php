@extends('layouts.app')

@section('title', 'Dashboard Chef')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row gap-6">

            {{-- ===== KARTU PROFIL KIRI ===== --}}
            <div class="lg:w-72 flex-shrink-0">
                <div class="rounded-2xl overflow-hidden border border-cokelat-100 shadow-sm bg-white">

                    {{-- Banner + Avatar --}}
                    <div class="h-28 bg-gradient-to-br from-cokelat-700 to-cokelat-900 relative">
                        <div class="absolute -bottom-10 left-1/2 -translate-x-1/2">
                            <div class="w-20 h-20 rounded-full border-4 border-white overflow-hidden bg-cokelat-200">
                                @if (Auth::user()->foto)
                                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="{{ Auth::user()->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center
                                            bg-cokelat-700 text-white text-2xl font-serif">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="pt-12 pb-6 px-5 text-center">
                        <h2 class="font-serif text-cokelat-800 text-lg font-semibold">
                            {{ Auth::user()->name }}
                        </h2>
                        <p class="text-cokelat-400 text-xs mt-0.5">{{ Auth::user()->email }}</p>

                        <span
                            class="inline-block mt-2 text-xs px-3 py-1 rounded-full
                                 bg-green-100 text-green-700 font-medium">
                            Chef Terverifikasi
                        </span>

                        {{-- Stats --}}
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="bg-cokelat-50 rounded-xl p-3">
                                <p class="text-cokelat-400 text-xs uppercase tracking-wide">Resep</p>
                                <p class="font-serif text-cokelat-800 text-xl font-semibold mt-0.5">
                                    {{ $totalResep }}
                                </p>
                            </div>
                            <div class="bg-cokelat-50 rounded-xl p-3">
                                <p class="text-cokelat-400 text-xs uppercase tracking-wide">Rating</p>
                                <p class="font-serif text-cokelat-800 text-xl font-semibold mt-0.5">
                                    {{ number_format($avgRating ?? 0, 1) }}
                                </p>
                            </div>
                        </div>

                        {{-- Bergabung --}}
                        <p class="text-cokelat-400 text-xs mt-4">
                            Bergabung sejak {{ Auth::user()->created_at->translatedFormat('F Y') }}
                        </p>

                        {{-- Tombol Edit Profil --}}
                        <a href="{{ route('chef.profile.edit') }}"
                            class="mt-4 block w-full text-center text-sm font-semibold
                              bg-cokelat-700 hover:bg-cokelat-800 text-white
                              py-2 rounded-xl transition-colors">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>

            {{-- ===== KONTEN KANAN ===== --}}
            <div class="flex-1 space-y-6">

                {{-- Selamat Datang --}}
                <div class="bg-cokelat-800 rounded-2xl px-6 py-5">
                    <p class="text-cokelat-300 text-sm">Selamat datang kembali,</p>
                    <h1 class="font-serif text-cokelat-50 text-2xl mt-0.5">
                        {{ Auth::user()->name }} 👋
                    </h1>
                </div>

                {{-- Aksi Cepat --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <a href="{{ route('chef.recipes.create') }}"
                        class="bg-white border border-cokelat-100 rounded-xl p-4 flex flex-col items-center justify-center
                          hover:-translate-y-0.5 transition-transform shadow-sm text-center">
                        <svg class="w-6 h-6 mb-2 text-cokelat-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <p class="text-cokelat-700 text-sm font-semibold">Tambah Resep</p>
                    </a>
                    <a href="{{ route('chef.profile.edit') }}"
                        class="bg-white border border-cokelat-100 rounded-xl p-4 flex flex-col items-center justify-center
                          hover:-translate-y-0.5 transition-transform shadow-sm text-center">
                        <svg class="w-6 h-6 mb-2 text-cokelat-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 19.89a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                        <p class="text-cokelat-700 text-sm font-semibold">Edit Profil</p>
                    </a>
                    <a href="{{ route('chef.profile', Auth::id()) }}"
                        class="bg-white border border-cokelat-100 rounded-xl p-4 flex flex-col items-center justify-center
                          hover:-translate-y-0.5 transition-transform shadow-sm text-center">
                        <svg class="w-6 h-6 mb-2 text-cokelat-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.43 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="text-cokelat-700 text-sm font-semibold">Lihat Profil</p>
                    </a>
                </div>


                {{-- Resep Terbaru --}}
                <div class="bg-white border border-cokelat-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-cokelat-50">
                        <h3 class="font-serif text-cokelat-800 font-semibold">Resep Saya</h3>
                        <a href="{{ route('chef.recipes.create') }}"
                            class="text-xs text-cokelat-500 hover:text-cokelat-700">
                            + Tambah baru
                        </a>
                    </div>

                    @if ($reseps->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-4xl mb-3">🍽</div>
                            <p class="text-cokelat-400 text-sm">Belum ada resep. Yuk tambahkan!</p>
                        </div>
                    @else
                        <div class="divide-y divide-cokelat-50">
                            @foreach ($reseps as $resep)
                                <div class="flex items-center gap-4 px-6 py-3">
                                    {{-- Thumbnail --}}
                                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-cokelat-100 flex-shrink-0">
                                        @if ($resep->gambar)
                                            <img src="{{ asset('storage/' . $resep->gambar) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-lg">🍴</div>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-cokelat-800 text-sm font-semibold truncate">
                                            {{ $resep->judul }}
                                        </p>
                                        <p class="text-cokelat-400 text-xs">
                                            {{ $resep->waktu_memasak }} mnt · {{ $resep->porsi }} porsi
                                        </p>
                                    </div>

                                    {{-- Aksi --}}
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <a href="{{ route('chef.recipes.edit', $resep->id) }}"
                                            class="text-xs text-cokelat-500 hover:text-cokelat-700 px-2 py-1
                                              border border-cokelat-200 rounded-lg">
                                            Edit
                                        </a>
                                        <form action="{{ route('chef.recipes.destroy', $resep->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus resep ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs text-red-400 hover:text-red-600 px-2 py-1
                                                       border border-red-100 rounded-lg">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

@endsection
