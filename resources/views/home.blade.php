@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    {{-- ===================== HERO SECTION ===================== --}}
    <section class="bg-gradient-to-br from-cokelat-800 via-cokelat-700 to-cokelat-600 py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-10">

                {{-- Teks Hero --}}
                <div class="flex-1 text-center md:text-left">
                    <span
                        class="inline-block bg-cokelat-500/20 text-yellow-300 text-xs px-4 py-1.5
                             rounded-full mb-4 tracking-widest font-bold uppercase">
                        🍽 Resep Terpercaya dari Contributor Berpengalaman
                    </span>
                    <h1 class="font-serif text-4xl md:text-5xl text-cokelat-50 leading-tight mb-4">
                        Temukan Resep<br>
                        <span class="text-cokelat-500">Lezat & Autentik</span><br>
                        untuk Keluarga
                    </h1>
                    <p class="text-cokelat-200 text-base md:text-lg leading-relaxed mb-8 max-w-lg">
                        Ribuan resep masakan Nusantara dan dunia, dikurasi langsung oleh Contributor pengalaman.
                        Mudah diikuti, hasil pasti memuaskan.
                    </p>
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        <a href="{{ route('recipes.index') }}"
                            class="bg-cokelat-500 hover:bg-cokelat-400 text-white font-bold
                              px-6 py-3 rounded-lg transition-colors text-sm">
                            Jelajahi Resep
                        </a>
                        @guest
                            <a href="{{ route('register') }}"
                                class="border border-cokelat-200/40 hover:border-cokelat-50 text-cokelat-50
                                  font-bold px-6 py-3 rounded-lg transition-colors text-sm">
                                Daftar Gratis
                            </a>
                        @endguest
                    </div>
                </div>

                {{-- Ilustrasi / Stats --}}
                <div class="flex-shrink-0 grid grid-cols-2 gap-4 w-full md:w-auto">
                    <div class="bg-cokelat-500/20 border border-cokelat-500/30 rounded-2xl p-5 text-center">
                        <p class="font-serif text-3xl text-yellow-300 font-bold">{{ $totalResep }}+</p>
                        <p class="text-cokelat-200 text-xs mt-1">Resep Tersedia</p>
                    </div>
                    <div class="bg-cokelat-500/20 border border-cokelat-500/30 rounded-2xl p-5 text-center">
                        <p class="font-serif text-3xl text-yellow-300 font-bold">{{ $totalChef }}+</p>
                        <p class="text-cokelat-200 text-xs mt-1">Contributor Aktif</p>
                    </div>
                    <div class="bg-cokelat-500/20 border border-cokelat-500/30 rounded-2xl p-5 text-center">
                        <p class="font-serif text-3xl text-yellow-300 font-bold">{{ $totalMember }}+</p>
                        <p class="text-cokelat-200 text-xs mt-1">Member</p>
                    </div>
                    <div class="bg-cokelat-500/20 border border-cokelat-500/30 rounded-2xl p-5 text-center">
                        <p class="font-serif text-3xl text-yellow-300 font-bold">{{ $totalKategori }}</p>
                        <p class="text-cokelat-200 text-xs mt-1">Kategori</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== SEARCH BAR ===================== --}}
    <section class="bg-white shadow-md sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form action="{{ route('recipes.index') }}" method="GET"
                class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

                {{-- Input Cari --}}
                <div
                    class="flex items-center flex-1 bg-cokelat-50 border border-cokelat-200
                        rounded-lg px-4 gap-2">
                    <svg class="w-4 h-4 text-cokelat-400 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari resep, bahan, atau Contributor..."
                        class="bg-transparent w-full py-2.5 text-sm text-cokelat-800
                              placeholder-cokelat-300 outline-none">
                </div>

                {{-- Filter Kategori --}}
                <select name="kategori"
                    class="bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                           text-sm text-cokelat-700 outline-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold
                           px-5 py-2.5 rounded-lg text-sm transition-colors flex-shrink-0">
                    Cari
                </button>

            </form>
        </div>
    </section>

    {{-- ===================== RESEP TERPOPULER ===================== --}}
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-baseline justify-between mb-6">
                <h2 class="font-serif text-2xl md:text-3xl text-cokelat-800">Resep Terpopuler</h2>
                <a href="{{ route('recipes.index') }}"
                    class="text-cokelat-500 hover:text-cokelat-400 text-sm transition-colors">
                    Lihat semua →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @forelse($resepPopuler as $resep)
                    <a href="{{ route('recipes.show', $resep->id) }}"
                        class="bg-white rounded-xl overflow-hidden border border-cokelat-100
                          hover:-translate-y-1 transition-transform shadow-sm group">

                        {{-- Gambar --}}
                        <div class="relative h-40 bg-cokelat-100 overflow-hidden">
                            @if ($resep->gambar)
                                <img src="{{ asset('storage/' . $resep->gambar) }}" alt="{{ $resep->judul }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-cokelat-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Badge --}}
                            @if ($resep->is_populer)
                                <span
                                    class="absolute top-2 left-2 bg-cokelat-500 text-white
                                         text-xs px-2.5 py-0.5 rounded-full font-bold">
                                    Populer
                                </span>
                            @endif

                            {{-- Tombol Bookmark (hanya untuk member) --}}
                            @auth
                                @if (Auth::user()->role === 'member')
                                    <button onclick="event.preventDefault(); toggleBookmark({{ $resep->id }}, this)"
                                        class="absolute top-2 right-2 text-cokelat-400
                                               hover:text-cokelat-600 transition-colors"
                                        data-bookmarked="{{ $resep->is_bookmarked ? 'true' : 'false' }}">
                                        <svg class="w-5 h-5" fill="{{ $resep->is_bookmarked ? 'currentColor' : 'none' }}"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                @endif
                            @endauth
                        </div>

                        {{-- Info Resep --}}
                        <div class="p-3.5">
                            <h3 class="font-serif text-cokelat-800 font-semibold text-sm leading-snug mb-1">
                                {{ $resep->judul }}
                            </h3>
                            <p class="text-cokelat-400 text-xs mb-2">
                                oleh {{ $resep->user->name ?? 'Contributor' }}
                            </p>
                            <div class="flex items-center gap-3 text-xs text-cokelat-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $resep->waktu_memasak }} mnt
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0
                                                 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0
                                                 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538
                                                 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838
                                                 -.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c
                                                 -.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    {{ number_format($resep->rating_avg, 1) }}
                                </span>
                            </div>
                        </div>

                    </a>
                @empty
                    <div class="col-span-4 text-center py-12 text-cokelat-400">
                        <p class="text-lg">Belum ada resep tersedia.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    {{-- ===================== KATEGORI ===================== --}}
    <section id="kategori" class="py-10 bg-cokelat-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-8">
                <h2 class="font-serif text-2xl md:text-3xl text-cokelat-800 mb-2">Jelajahi Kategori</h2>
                <p class="text-cokelat-500 text-sm">Temukan resep sesuai selera dan kebutuhan Anda</p>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                @foreach ($kategoriList as $kat)
                    <a href="{{ route('recipes.index', ['kategori' => $kat->id]) }}"
                        class="bg-white border border-cokelat-100 rounded-xl p-4 text-center
                          hover:bg-cokelat-700 hover:border-cokelat-700 group transition-colors">
                        <div class="text-2xl mb-2">{{ $kat->emoji ?? '🍽' }}</div>
                        <p class="text-xs font-bold text-cokelat-700 group-hover:text-cokelat-50 transition-colors">
                            {{ $kat->nama }}
                        </p>
                    </a>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ===================== RESEP TERBARU ===================== --}}
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-baseline justify-between mb-6">
                <h2 class="font-serif text-2xl md:text-3xl text-cokelat-800">Resep Terbaru</h2>
                <a href="{{ route('recipes.index', ['sort' => 'terbaru']) }}"
                    class="text-cokelat-500 hover:text-cokelat-400 text-sm transition-colors">
                    Lihat semua →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($resepTerbaru as $resep)
                    <a href="{{ route('recipes.show', $resep->id) }}"
                        class="bg-white rounded-xl overflow-hidden border border-cokelat-100
                          flex gap-4 p-4 hover:shadow-md transition-shadow group">

                        {{-- Thumbnail kecil --}}
                        <div class="flex-shrink-0 w-24 h-24 rounded-lg bg-cokelat-100 overflow-hidden">
                            @if ($resep->gambar)
                                <img src="{{ asset('storage/' . $resep->gambar) }}" alt="{{ $resep->judul }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-cokelat-300 text-2xl">
                                    🍴
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <span
                                class="text-xs bg-cokelat-100 text-cokelat-600 px-2 py-0.5
                                     rounded-full font-bold">
                                {{ $resep->kategori->nama ?? 'Umum' }}
                            </span>
                            <h3
                                class="font-serif text-cokelat-800 font-semibold text-sm
                                   leading-snug mt-1.5 mb-1 truncate">
                                {{ $resep->judul }}
                            </h3>
                            <p class="text-xs text-cokelat-400 mb-2">
                                oleh {{ $resep->user->name ?? 'Contributor' }}
                            </p>
                            <div class="flex items-center gap-3 text-xs text-cokelat-500">
                                <span>⏱ {{ $resep->waktu_memasak }} mnt</span>
                                <span>👥 {{ $resep->porsi }} porsi</span>
                            </div>
                        </div>

                    </a>
                @empty
                    <div class="col-span-3 text-center py-10 text-cokelat-400">
                        Belum ada resep terbaru.
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    {{-- ===================== CHEF TERFEATURED ===================== --}}
    <section id="chef" class="py-12 bg-cokelat-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-8">
                <h2 class="font-serif text-2xl md:text-3xl text-cokelat-50 mb-2">Contributor Terfeatured</h2>
                <p class="text-cokelat-300 text-sm">Kenali para Contributor berbakat di balik resep-resep lezat kami</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5">
                @forelse($chefFeatured as $chef)
                    <a href="{{ route('chef.profile', $chef->id) }}"
                        class="bg-cokelat-700 hover:bg-cokelat-600 rounded-xl p-5 text-center
                          transition-colors border border-cokelat-600">

                        {{-- Avatar --}}
                        <div
                            class="w-14 h-14 rounded-full mx-auto mb-3 overflow-hidden
                                bg-cokelat-500 flex items-center justify-center">
                            @if ($chef->chefProfile && $chef->chefProfile->foto)
                                <img src="{{ asset('storage/' . $chef->chefProfile->foto) }}" alt="{{ $chef->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="font-serif text-xl font-bold text-yellow-200">
                                    {{ strtoupper(substr($chef->name, 0, 2)) }}
                                </span>
                            @endif
                        </div>

                        <p class="font-bold text-cokelat-50 text-sm mb-0.5">{{ $chef->name }}</p>
                        <p class="text-cokelat-300 text-xs">
                            {{ $chef->chefProfile->spesialisasi ?? 'Masakan Nusantara' }}
                        </p>
                        <p class="text-cokelat-400 text-xs mt-1">
                            {{ $chef->recipes_count }} resep
                        </p>

                    </a>
                @empty
                    <div class="col-span-4 text-center py-10 text-cokelat-300">
                        Belum ada Contributor terdaftar.
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    {{-- ===================== CTA DAFTAR ===================== --}}
    @guest
        <section class="py-16 bg-cokelat-50">
            <div class="max-w-3xl mx-auto px-4 text-center">
                <div class="bg-cokelat-800 rounded-2xl p-10">
                    <h2 class="font-serif text-3xl text-cokelat-50 mb-3">
                        Bergabung & Mulai Memasak
                    </h2>
                    <p class="text-cokelat-300 text-sm leading-relaxed mb-8 max-w-lg mx-auto">
                        Daftar gratis sebagai member untuk menyimpan resep favorit,
                        atau daftar sebagai Contributor dan bagikan keahlian Anda kepada jutaan pengguna.
                    </p>
                    <div class="flex flex-wrap gap-3 justify-center">
                        <a href="{{ route('register') }}"
                            class="bg-cokelat-500 hover:bg-cokelat-400 text-white font-bold
                          px-6 py-3 rounded-lg transition-colors text-sm">
                            Daftar sebagai Member
                        </a>
                        <a href="{{ route('register') }}?role=chef"
                            class="border border-cokelat-200/40 hover:border-cokelat-50 text-cokelat-50
                          font-bold px-6 py-3 rounded-lg transition-colors text-sm">
                            Daftar sebagai Contributor
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endguest

@endsection

@push('scripts')
    <script>
        function toggleBookmark(resepId, btn) {
            fetch(`/resep/${resepId}/bookmark`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const svg = btn.querySelector('svg');
                    if (data.bookmarked) {
                        svg.setAttribute('fill', 'currentColor');
                        btn.dataset.bookmarked = 'true';
                    } else {
                        svg.setAttribute('fill', 'none');
                        btn.dataset.bookmarked = 'false';
                    }
                })
                .catch(err => console.error('Bookmark error:', err));
        }
    </script>
@endpush