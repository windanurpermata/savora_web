@extends('layouts.app')

@section('title', $resep->judul)

@section('content')

    {{-- ===================== HERO GAMBAR ===================== --}}
    <div class="relative bg-cokelat-800 h-56 sm:h-72 md:h-80 overflow-hidden">
        @if ($resep->gambar)
            <img src="{{ asset('storage/' . $resep->gambar) }}" alt="{{ $resep->judul }}"
                class="w-full h-full object-cover opacity-60">
        @else
            <div class="w-full h-full flex items-center justify-center text-6xl">🍴</div>
        @endif

        {{-- Overlay teks --}}
        <div
            class="absolute inset-0 flex flex-col justify-end p-5 sm:p-8
                bg-gradient-to-t from-cokelat-900/80 to-transparent">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-1.5 text-cokelat-300 text-xs mb-3">
                <a href="{{ route('home') }}" class="hover:text-cokelat-50 transition-colors">Beranda</a>
                <span>/</span>
                <a href="{{ route('recipes.index') }}" class="hover:text-cokelat-50 transition-colors">Resep</a>
                <span>/</span>
                <span class="text-cokelat-100 truncate max-w-[180px]">{{ $resep->judul }}</span>
            </div>

            @if ($resep->kategori)
                <span
                    class="inline-block bg-cokelat-500 text-white text-xs px-3 py-0.5
                         rounded-full mb-2 w-fit">
                    {{ $resep->kategori->emoji ?? '' }} {{ $resep->kategori->nama }}
                </span>
            @endif

            <h1 class="font-serif text-2xl sm:text-3xl md:text-4xl text-white font-bold leading-tight">
                {{ $resep->judul }}
            </h1>
        </div>
    </div>

    {{-- ===================== KONTEN UTAMA ===================== --}}
    <div id="protected-recipe-content" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ===== KOLOM KIRI (konten resep) ===== --}}
            <div class="flex-1 min-w-0">

                {{-- Info singkat --}}
                <div class="flex flex-wrap items-center gap-4 mb-6 pb-6 border-b border-cokelat-100">

                    {{-- Chef --}}
                    <a href="{{ route('contributor.profile', $resep->user_id) }}" class="flex items-center gap-2 group">
                        <div
                            class="w-9 h-9 rounded-full bg-cokelat-700 flex items-center
                                justify-center overflow-hidden flex-shrink-0">
                            @if ($resep->user->contributorProfile?->foto)
                                <img src="{{ asset('storage/' . $resep->user->contributorProfile->foto) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="font-serif text-sm font-bold text-yellow-200">
                                    {{ strtoupper(substr($resep->user->name ?? 'C', 0, 2)) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-cokelat-400">Chef</p>
                            <p
                                class="text-sm font-bold text-cokelat-700 group-hover:text-cokelat-500
                                  transition-colors">
                                {{ $resep->user->name ?? 'Chef' }}
                            </p>
                        </div>
                    </a>

                    <div class="h-8 w-px bg-cokelat-100 hidden sm:block"></div>

                    {{-- Waktu --}}
                    <div class="text-center">
                        <p class="text-xs text-cokelat-400">Waktu</p>
                        <p class="text-sm font-bold text-cokelat-700">{{ $resep->waktu_memasak }} mnt</p>
                    </div>

                    <div class="h-8 w-px bg-cokelat-100 hidden sm:block"></div>

                    {{-- Porsi --}}
                    <div class="text-center">
                        <p class="text-xs text-cokelat-400">Porsi</p>
                        <p class="text-sm font-bold text-cokelat-700">{{ $resep->porsi }} orang</p>
                    </div>

                    <div class="h-8 w-px bg-cokelat-100 hidden sm:block"></div>

                    {{-- Rating --}}
                    <div class="text-center">
                        <p class="text-xs text-cokelat-400">Rating</p>
                        <p class="text-sm font-bold text-cokelat-700 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0
                                         00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0
                                         00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538
                                         1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838
                                         -.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c
                                         -.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            {{ number_format($resep->rating_avg, 1) }}
                            <span class="text-cokelat-400 font-normal">({{ $resep->ratings->count() }})</span>
                        </p>
                    </div>

                    {{-- Tombol bookmark & aksi --}}
                    <div class="ml-auto flex items-center gap-2">
                        @auth
                            @if (Auth::user()->role === 'member')
                                <button onclick="toggleBookmark({{ $resep->id }}, this)"
                                    data-bookmarked="{{ $resep->is_bookmarked ? 'true' : 'false' }}"
                                    class="flex items-center gap-1.5 border border-cokelat-200
                                           hover:border-cokelat-500 text-cokelat-500 text-xs font-bold
                                           px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="{{ $resep->is_bookmarked ? 'currentColor' : 'none' }}"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                    <span id="bookmarkText">
                                        {{ $resep->is_bookmarked ? 'Tersimpan' : 'Simpan' }}
                                    </span>
                                </button>
                            @endif

                            {{-- Tombol edit/hapus untuk chef pemilik atau admin --}}
                            @if (Auth::id() === $resep->user_id || Auth::user()->role === 'admin')
                                <a href="{{ route('contributor.recipes.edit', $resep->id) }}"
                                    class="flex items-center gap-1.5 bg-cokelat-700 hover:bg-cokelat-800
                                      text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                                     m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('contributor.recipes.destroy', $resep->id) }}"
                                    onsubmit="return confirm('Hapus resep ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center gap-1.5 border border-red-200
                                               hover:bg-red-50 text-red-500 text-xs font-bold
                                               px-3 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                                         01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                                         00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>

                </div>

                {{-- Deskripsi --}}
                @if ($resep->deskripsi)
                    <div class="mb-8">
                        <h2 class="font-serif text-xl text-cokelat-800 mb-3">Tentang Resep</h2>
                        <p class="text-cokelat-600 text-sm leading-relaxed">{{ $resep->deskripsi }}</p>
                    </div>
                @endif

                {{-- Bahan-bahan --}}
                @if ($resep->ingredients && $resep->ingredients->count())
                    <div class="mb-8">
                        <h2 class="font-serif text-xl text-cokelat-800 mb-4">Bahan-bahan</h2>
                        <ul class="space-y-2">
                            @foreach ($resep->ingredients as $bahan)
                                <li class="flex items-start gap-2 text-sm text-cokelat-600">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-cokelat-500
                                             flex-shrink-0 mt-1.5"></span>
                                    <span>{{ $bahan->jumlah }} {{ $bahan->satuan }}
                                        <strong class="text-cokelat-800">{{ $bahan->nama }}</strong>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Langkah Memasak --}}
                @if ($resep->steps && $resep->steps->count())
                    <div class="mb-8">
                        <h2 class="font-serif text-xl text-cokelat-800 mb-4">Cara Memasak</h2>
                        <ol class="space-y-4">
                            @foreach ($resep->steps->sortBy('urutan') as $step)
                                <li class="flex gap-4">
                                    <div
                                        class="flex-shrink-0 w-7 h-7 rounded-full bg-cokelat-700
                                            flex items-center justify-center">
                                        <span class="text-xs font-bold text-white">{{ $step->urutan }}</span>
                                    </div>
                                    <p class="text-sm text-cokelat-600 leading-relaxed pt-1">
                                        {{ $step->instruksi }}
                                    </p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                {{-- ===== FORM RATING ===== --}}
                @auth
                    @if (Auth::user()->role === 'member')
                        <div class="border border-cokelat-100 rounded-xl p-5 mb-8 bg-cokelat-50">
                            <h2 class="font-serif text-lg text-cokelat-800 mb-3">Beri Rating</h2>

                            @php
                                $myRating = $resep->ratings->where('user_id', Auth::id())->first();
                            @endphp

                            <form method="POST" action="{{ route('recipes.rate', $resep->id) }}">
                                @csrf
                                <div class="flex items-center gap-1 mb-3" id="starRating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" onclick="setRating({{ $i }})"
                                            class="text-2xl transition-transform hover:scale-110
                                                   {{ $myRating && $myRating->nilai >= $i ? 'text-yellow-400' : 'text-cokelat-200' }}"
                                            data-star="{{ $i }}">★</button>
                                    @endfor
                                </div>
                                <input type="hidden" name="nilai" id="ratingValue" value="{{ $myRating->nilai ?? '' }}">
                                <button type="submit"
                                    class="bg-cokelat-700 hover:bg-cokelat-800 text-white text-xs
                                           font-bold px-4 py-2 rounded-lg transition-colors">
                                    {{ $myRating ? 'Perbarui Rating' : 'Kirim Rating' }}
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                {{-- ===== ULASAN ===== --}}
                @if ($resep->ratings->count())
                    <div class="mb-8">
                        <h2 class="font-serif text-xl text-cokelat-800 mb-4">
                            Ulasan ({{ $resep->ratings->count() }})
                        </h2>
                        <div class="space-y-3">
                            @foreach ($resep->ratings->sortByDesc('created_at')->take(5) as $rating)
                                <div class="flex gap-3 p-3 bg-cokelat-50 rounded-lg">
                                    <div
                                        class="w-8 h-8 rounded-full bg-cokelat-700 flex items-center
                                            justify-center flex-shrink-0">
                                        <span class="text-xs font-bold text-yellow-200">
                                            {{ strtoupper(substr($rating->user->name ?? 'U', 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <p class="text-xs font-bold text-cokelat-700">
                                                {{ $rating->user->name ?? 'Member' }}
                                            </p>
                                            <div class="flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span
                                                        class="text-xs {{ $i <= $rating->nilai ? 'text-yellow-400' : 'text-cokelat-200' }}">★</span>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-xs text-cokelat-400">
                                            {{ $rating->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ===== KOMENTAR ===== --}}
                <div class="mb-8 pt-6 border-t border-cokelat-100">
                    <h2 class="font-serif text-xl text-cokelat-800 mb-4">
                        Komentar ({{ $resep->comments->count() }})
                    </h2>

                    {{-- Form Komentar --}}
                    @auth
                        <div class="mb-6">
                            <form method="POST" action="{{ route('comments.store', $resep->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="isi" class="sr-only">Tulis komentar</label>
                                    <textarea name="isi" id="isi" rows="3"
                                        class="w-full rounded-lg border-cokelat-200 focus:border-cokelat-500 focus:ring focus:ring-cokelat-200 focus:ring-opacity-50 text-sm p-3 text-cokelat-800 placeholder-cokelat-400"
                                        placeholder="Bagikan pendapat Anda tentang resep ini..." required></textarea>
                                    @error('isi')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                    class="bg-cokelat-700 hover:bg-cokelat-800 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors">
                                    Kirim Komentar
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="p-4 bg-cokelat-50 border border-cokelat-100 rounded-lg text-sm text-cokelat-600 mb-6">
                            Silakan <a href="{{ route('login') }}" class="text-cokelat-700 font-bold hover:underline">masuk</a> untuk menulis komentar.
                        </div>
                    @endauth

                    {{-- Daftar Komentar --}}
                    @if($resep->comments->count())
                        <div class="space-y-4">
                            @foreach ($resep->comments as $comment)
                                <div class="flex gap-3 p-4 bg-white border border-cokelat-100 rounded-lg shadow-sm">
                                    {{-- Avatar --}}
                                    <div class="w-9 h-9 rounded-full bg-cokelat-700 flex items-center justify-center flex-shrink-0 text-white font-bold text-xs overflow-hidden">
                                        @if ($comment->user->contributorProfile?->foto)
                                            <img src="{{ asset('storage/' . $comment->user->contributorProfile->foto) }}" class="w-full h-full object-cover rounded-full">
                                        @else
                                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 2)) }}
                                        @endif
                                    </div>
                                    
                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="text-sm font-bold text-cokelat-800">
                                                    {{ $comment->user->name ?? 'Pengguna' }}
                                                </span>
                                                @if ($comment->user->role === 'admin')
                                                    <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded-full">Admin</span>
                                                @elseif ($comment->user->role === 'contributor')
                                                    <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2 py-0.5 rounded-full">Contributor</span>
                                                @endif
                                            </div>
                                            <span class="text-xs text-cokelat-400 whitespace-nowrap">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-cokelat-600 leading-relaxed break-words">
                                            {{ $comment->isi }}
                                        </p>
                                        
                                        {{-- Delete Button (untuk owner comment atau admin) --}}
                                        @auth
                                            @if ($comment->user_id === Auth::id() || Auth::user()->role === 'admin')
                                                <div class="mt-2 text-right">
                                                    <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" onsubmit="return confirm('Hapus komentar ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold transition-colors flex items-center gap-1 ml-auto">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-cokelat-400 italic">Belum ada komentar untuk resep ini.</p>
                    @endif
                </div>

            </div>

            {{-- ===== KOLOM KANAN (sidebar) ===== --}}
            <div class="lg:w-64 flex-shrink-0 space-y-6">

                {{-- Profil Chef --}}
                <div class="bg-white border border-cokelat-100 rounded-xl p-4 text-center">
                    <div
                        class="w-14 h-14 rounded-full bg-cokelat-700 flex items-center
                            justify-center overflow-hidden mx-auto mb-3">
                        @if ($resep->user->contributorProfile?->foto)
                            <img src="{{ asset('storage/' . $resep->user->contributorProfile->foto) }}"
                                class="w-full h-full object-cover">
                        @else
                            <span class="font-serif text-lg font-bold text-yellow-200">
                                {{ strtoupper(substr($resep->user->name ?? 'C', 0, 2)) }}
                            </span>
                        @endif
                    </div>
                    <p class="font-bold text-sm text-cokelat-800 mb-0.5">{{ $resep->user->name }}</p>
                    <p class="text-xs text-cokelat-400 mb-3">
                        {{ $resep->user->contributorProfile->spesialisasi ?? 'Chef' }}
                    </p>
                    <a href="{{ route('contributor.profile', $resep->user_id) }}"
                        class="block w-full border border-cokelat-200 hover:bg-cokelat-50
                          text-cokelat-700 text-xs font-bold py-2 rounded-lg transition-colors">
                        Lihat Profil
                    </a>
                </div>

                {{-- Resep Lain dari Chef --}}
                @if ($resepLain->count())
                    <div class="bg-white border border-cokelat-100 rounded-xl p-4">
                        <h3 class="font-serif text-sm font-bold text-cokelat-800 mb-3">
                            Resep Lain dari Chef Ini
                        </h3>
                        <div class="space-y-3">
                            @foreach ($resepLain as $lain)
                                <a href="{{ route('recipes.show', $lain->id) }}" class="flex gap-2.5 group">
                                    <div class="w-12 h-12 rounded-lg bg-cokelat-100 overflow-hidden flex-shrink-0">
                                        @if ($lain->gambar)
                                            <img src="{{ asset('storage/' . $lain->gambar) }}" alt="{{ $lain->judul }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-lg">🍴</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-xs font-bold text-cokelat-700 group-hover:text-cokelat-500
                                               transition-colors line-clamp-2 leading-snug">
                                            {{ $lain->judul }}
                                        </p>
                                        <p class="text-xs text-cokelat-400 mt-0.5">
                                            ⏱ {{ $lain->waktu_memasak }} mnt
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Rating bintang
        function setRating(val) {
            document.getElementById('ratingValue').value = val;
            document.querySelectorAll('#starRating button').forEach((btn, i) => {
                btn.classList.toggle('text-yellow-400', i < val);
                btn.classList.toggle('text-cokelat-200', i >= val);
            });
        }

        // Bookmark toggle
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
                    const text = document.getElementById('bookmarkText');
                    svg.setAttribute('fill', data.bookmarked ? 'currentColor' : 'none');
                    text.textContent = data.bookmarked ? 'Tersimpan' : 'Simpan';
                    btn.dataset.bookmarked = data.bookmarked ? 'true' : 'false';
                });
        }

        // ===== PROTEKSI ANTI COPY-PASTE =====
        document.addEventListener('DOMContentLoaded', function () {
            const protectedArea = document.getElementById('protected-recipe-content');
            if (!protectedArea) return;

            // Blokir copy, cut, paste
            ['copy', 'cut', 'paste'].forEach(function (evt) {
                protectedArea.addEventListener(evt, function (e) {
                    e.preventDefault();
                    return false;
                });
            });

            // Blokir klik kanan (context menu)
            protectedArea.addEventListener('contextmenu', function (e) {
                e.preventDefault();
                return false;
            });

            // Blokir drag
            protectedArea.addEventListener('dragstart', function (e) {
                e.preventDefault();
                return false;
            });

            // Blokir shortcut keyboard Ctrl+C, Ctrl+A, Ctrl+X, Ctrl+P pada area resep
            protectedArea.addEventListener('keydown', function (e) {
                if (e.ctrlKey && ['c', 'a', 'x', 'p'].includes(e.key.toLowerCase())) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Proteksi anti-select pada konten resep */
        #protected-recipe-content {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Pastikan form input tetap bisa digunakan */
        #protected-recipe-content textarea,
        #protected-recipe-content input,
        #protected-recipe-content select,
        #protected-recipe-content button {
            -webkit-user-select: auto;
            -moz-user-select: auto;
            -ms-user-select: auto;
            user-select: auto;
        }
    </style>
@endpush

