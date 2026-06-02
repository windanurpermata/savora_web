@extends('layouts.app')

@section('title', 'Bookmark Saya')

@section('content')

<section class="bg-cokelat-800 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-serif text-3xl text-cokelat-50 mb-2">Bookmark Saya</h1>
        <p class="text-cokelat-300 text-sm">{{ $bookmarks->total() }} resep tersimpan</p>
    </div>
</section>

<section class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($bookmarks->isEmpty())
        <div class="text-center py-20">
            <div class="text-5xl mb-4">🔖</div>
            <p class="font-serif text-xl text-cokelat-700 mb-2">Belum ada resep yang disimpan</p>
            <p class="text-cokelat-400 text-sm mb-6">Temukan resep favorit dan simpan untuk dibaca nanti</p>
            <a href="{{ route('recipes.index') }}"
                class="bg-cokelat-700 text-white text-sm font-bold px-5 py-2.5 rounded-lg">
                Jelajahi Resep
            </a>
        </div>

        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
            @foreach($bookmarks as $bookmark)
            @php $resep = $bookmark->recipe; @endphp
            @if($resep)
            <div class="bg-white rounded-xl overflow-hidden border border-cokelat-100
                                    shadow-sm group relative">

                {{-- Tombol hapus bookmark --}}
                <button onclick="hapusBookmark({{ $resep->id }}, this)" class="absolute top-2 right-2 z-10 w-7 h-7 rounded-full
                                           bg-white/90 flex items-center justify-center
                                           text-red-400 hover:text-red-600 shadow-sm transition-colors">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </button>

                <a href="{{ route('recipes.show', $resep->id) }}">
                    {{-- Gambar --}}
                    <div class="h-36 sm:h-40 bg-cokelat-100 overflow-hidden">
                        @if($resep->gambar)
                        <img src="{{ asset('storage/' . $resep->gambar) }}" alt="{{ $resep->judul }}" class="w-full h-full object-cover group-hover:scale-105
                                                    transition-transform duration-300">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-3xl">
                            🍴
                        </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-3">
                        @if($resep->kategori)
                        <span class="text-xs bg-cokelat-100 text-cokelat-600
                                                     px-2 py-0.5 rounded-full font-bold">
                            {{ $resep->kategori->emoji ?? '' }} {{ $resep->kategori->nama }}
                        </span>
                        @endif
                        <h3 class="font-serif text-cokelat-800 font-semibold text-sm
                                               leading-snug mt-1.5 mb-1 line-clamp-2">
                            {{ $resep->judul }}
                        </h3>
                        <p class="text-cokelat-400 text-xs mb-2 truncate">
                            oleh {{ $resep->user->name ?? 'Chef' }}
                        </p>
                        <div class="flex items-center gap-3 text-xs text-cokelat-500">
                            <span>⏱ {{ $resep->waktu_memasak }} mnt</span>
                            <span>👥 {{ $resep->porsi }} porsi</span>
                        </div>
                    </div>
                </a>

            </div>
            @endif
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($bookmarks->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $bookmarks->withQueryString()->links('pagination::tailwind') }}
        </div>
        @endif
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
    function hapusBookmark(resepId, btn) {
        fetch(`/resep/${resepId}/bookmark`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
            .then(res => res.json())
            .then(() => {
                // Hapus card dari tampilan
                btn.closest('.bg-white').remove();
            })
            .catch(err => console.error(err));
    }
</script>
@endpush