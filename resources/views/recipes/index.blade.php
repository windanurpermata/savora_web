@extends('layouts.app')

@section('title', 'Daftar Resep')

@section('content')

    {{-- ===================== HEADER ===================== --}}
    <section class="bg-cokelat-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-serif text-3xl md:text-4xl text-cokelat-50 mb-2">Semua Resep</h1>
            <p class="text-cokelat-300 text-sm">
                Menampilkan {{ $recipes->total() }} resep
                @if (request('q'))
                    untuk "<span class="text-cokelat-200 font-bold">{{ request('q') }}</span>"
                @endif
                @if (request('kategori') && $kategoriAktif)
                    dalam kategori
                    <span class="text-cokelat-200 font-bold">{{ $kategoriAktif->nama }}</span>
                @endif
            </p>
        </div>
    </section>

    {{-- ===================== FILTER & SORT ===================== --}}
    <section class="bg-white border-b border-cokelat-100 sticky top-16 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <form method="GET" action="{{ route('recipes.index') }}" class="flex flex-wrap gap-2 items-center">

                {{-- Search --}}
                <div
                    class="flex items-center flex-1 min-w-[180px] bg-cokelat-50
                        border border-cokelat-200 rounded-lg px-3 gap-2">
                    <svg class="w-3.5 h-3.5 text-cokelat-400 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari resep..."
                        class="bg-transparent w-full py-2 text-xs text-cokelat-800
                              placeholder-cokelat-300 outline-none">
                </div>

                {{-- Filter Kategori --}}
                <select name="kategori" onchange="this.form.submit()"
                    class="bg-cokelat-50 border border-cokelat-200 rounded-lg px-3 py-2
                           text-xs text-cokelat-700 outline-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->emoji ?? '' }} {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>

                {{-- Sort --}}
                <select name="sort" onchange="this.form.submit()"
                    class="bg-cokelat-50 border border-cokelat-200 rounded-lg px-3 py-2
                           text-xs text-cokelat-700 outline-none cursor-pointer">
                    <option value="terbaru" {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>
                        Terbaru
                    </option>
                    <option value="populer" {{ request('sort') === 'populer' ? 'selected' : '' }}>
                        Terpopuler
                    </option>
                </select>

                {{-- Tombol Cari --}}
                <button type="submit"
                    class="bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold
                           px-4 py-2 rounded-lg text-xs transition-colors">
                    Cari
                </button>

                {{-- Reset filter --}}
                @if (request('q') || request('kategori') || request('sort'))
                    <a href="{{ route('recipes.index') }}"
                        class="text-cokelat-400 hover:text-cokelat-600 text-xs transition-colors">
                        Reset
                    </a>
                @endif

            </form>
        </div>
    </section>

    {{-- ===================== GRID RESEP ===================== --}}
    <section class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($recipes->isEmpty())
                {{-- Empty state --}}
                <div class="text-center py-20">
                    <div class="text-5xl mb-4">🍽</div>
                    <p class="font-serif text-xl text-cokelat-700 mb-2">Resep tidak ditemukan</p>
                    <p class="text-cokelat-400 text-sm mb-6">Coba ubah kata kunci atau filter pencarian</p>
                    <a href="{{ route('recipes.index') }}"
                        class="bg-cokelat-700 text-white text-sm font-bold px-5 py-2.5 rounded-lg">
                        Lihat Semua Resep
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                    @foreach ($recipes as $resep)
                        <a href="{{ route('recipes.show', $resep->id) }}"
                            class="bg-white rounded-xl overflow-hidden border border-cokelat-100
                              hover:-translate-y-1 transition-transform shadow-sm group">

                            {{-- Gambar --}}
                            <div class="relative h-36 sm:h-40 bg-cokelat-100 overflow-hidden">
                                @if ($resep->gambar)
                                    <img src="{{ asset('storage/' . $resep->gambar) }}" alt="{{ $resep->judul }}"
                                        class="w-full h-full object-cover group-hover:scale-105
                                            transition-transform duration-300">
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

                                {{-- Tombol Bookmark --}}
                                @auth
                                    @if (Auth::user()->role === 'member')
                                        <button onclick="event.preventDefault(); toggleBookmark({{ $resep->id }}, this)"
                                            data-bookmarked="{{ $resep->is_bookmarked ? 'true' : 'false' }}"
                                            class="absolute top-2 right-2 w-7 h-7 rounded-full
                                                   bg-white/80 backdrop-blur-sm flex items-center
                                                   justify-center text-cokelat-500 hover:text-cokelat-700
                                                   transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5"
                                                fill="{{ $resep->is_bookmarked ? 'currentColor' : 'none' }}"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                            </svg>
                                        </button>
                                    @endif
                                @endauth
                            </div>

                            {{-- Info --}}
                            <div class="p-3">
                                <h3
                                    class="font-serif text-cokelat-800 font-semibold text-sm
                                       leading-snug mb-1 line-clamp-2">
                                    {{ $resep->judul }}
                                </h3>
                                <p class="text-cokelat-400 text-xs mb-2 truncate">
                                    oleh {{ $resep->user->name ?? 'Chef' }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-cokelat-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $resep->waktu_memasak }} mnt
                                    </span>
                                    <span class="flex items-center gap-0.5">
                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0
                                                     00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0
                                                     00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538
                                                     1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838
                                                     -.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c
                                                     -.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                        {{ number_format($resep->rating_avg ?? 0, 1) }}
                                    </span>
                                </div>
                            </div>

                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($recipes->hasPages())
                    <div class="mt-10 flex justify-center">
                        {{ $recipes->withQueryString()->links('pagination::tailwind') }}
                    </div>
                @endif

            @endif

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        function toggleBookmark(resepId, btn) {
            fetch(`/resep/${resepId}/bookmark`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const svg = btn.querySelector('svg');
                    svg.setAttribute('fill', data.bookmarked ? 'currentColor' : 'none');
                    btn.dataset.bookmarked = data.bookmarked ? 'true' : 'false';
                })
                .catch(err => console.error(err));
        }
    </script>
@endpush
