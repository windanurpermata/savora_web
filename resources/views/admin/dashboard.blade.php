@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Statistik --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-white rounded-xl border border-cokelat-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-cokelat-400 font-bold uppercase tracking-wider">Total Resep</p>
                <div class="w-8 h-8 rounded-lg bg-cokelat-100 flex items-center justify-center text-cokelat-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <p class="font-serif text-3xl font-bold text-cokelat-800">{{ $totalResep }}</p>
            <p class="text-xs text-cokelat-400 mt-1">+{{ $resepBulanIni }} bulan ini</p>
        </div>

        @if(auth()->user()->isSuperAdmin())
        <div class="bg-white rounded-xl border border-cokelat-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-cokelat-400 font-bold uppercase tracking-wider">Total User</p>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <p class="font-serif text-3xl font-bold text-cokelat-800">{{ $totalUser }}</p>
            <p class="text-xs text-cokelat-400 mt-1">{{ $totalContributor }} contributor · {{ $totalMember }} member</p>
        </div>
        @endif

        <div class="bg-white rounded-xl border border-cokelat-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-cokelat-400 font-bold uppercase tracking-wider">Pesan Masuk</p>
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <p class="font-serif text-3xl font-bold text-cokelat-800">{{ $totalPesan }}</p>
            <p class="text-xs text-red-500 mt-1 font-bold">{{ $pesanBelumDibaca }} belum dibaca</p>
        </div>

        <div class="bg-white rounded-xl border border-cokelat-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-cokelat-400 font-bold uppercase tracking-wider">Newsletter</p>
                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
            <p class="font-serif text-3xl font-bold text-cokelat-800">{{ $totalSubscriber }}</p>
            <p class="text-xs text-cokelat-400 mt-1">subscriber aktif</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Resep Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-cokelat-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-cokelat-50">
                <h2 class="font-serif text-base text-cokelat-800">Resep Terbaru</h2>
                <a href="{{ route('admin.recipes.index') }}" class="text-xs text-cokelat-500 hover:text-cokelat-400">Lihat
                    semua →</a>
            </div>
            <div class="divide-y divide-cokelat-50">
                @forelse($resepTerbaru as $resep)
                    <div class="flex items-center gap-4 px-5 py-3">
                        <div class="w-10 h-10 rounded-lg bg-cokelat-100 overflow-hidden flex-shrink-0">
                            @if ($resep->gambar)
                                <img src="{{ asset('storage/' . $resep->gambar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-lg">🍴</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-cokelat-800 truncate">{{ $resep->judul }}</p>
                            <p class="text-xs text-cokelat-400">oleh {{ $resep->user->name ?? '-' }}</p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-xs bg-cokelat-100 text-cokelat-600 px-2 py-0.5 rounded-full">
                                {{ $resep->kategori->nama ?? 'Umum' }}
                            </span>
                            <a href="{{ route('admin.recipes.edit', $resep->id) }}"
                                class="text-xs text-cokelat-500 hover:text-cokelat-700">Edit</a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-cokelat-400 text-sm py-8">Belum ada resep.</p>
                @endforelse
            </div>
        </div>

        {{-- Pesan Terbaru --}}
        <div class="bg-white rounded-xl border border-cokelat-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-cokelat-50">
                <h2 class="font-serif text-base text-cokelat-800">Pesan Terbaru</h2>
                <a href="{{ route('admin.messages.index') }}" class="text-xs text-cokelat-500 hover:text-cokelat-400">Lihat
                    semua →</a>
            </div>
            <div class="divide-y divide-cokelat-50">
                @forelse($pesanTerbaru as $pesan)
                    <a href="{{ route('admin.messages.show', $pesan->id) }}"
                        class="flex items-start gap-3 px-5 py-3 hover:bg-cokelat-50 transition-colors block
                          {{ !$pesan->is_read ? 'bg-cokelat-50' : '' }}">
                        <div
                            class="w-8 h-8 rounded-full bg-cokelat-700 flex items-center
                                justify-center text-yellow-200 text-xs font-bold flex-shrink-0 mt-0.5">
                            {{ strtoupper(substr($pesan->nama, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-bold text-cokelat-800 truncate">{{ $pesan->nama }}</p>
                                @if (!$pesan->is_read)
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                                @endif
                            </div>
                            <p class="text-xs text-cokelat-400 truncate">{{ $pesan->pesan }}</p>
                            <p class="text-xs text-cokelat-300 mt-0.5">
                                {{ $pesan->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-cokelat-400 text-sm py-8">Belum ada pesan.</p>
                @endforelse
            </div>
        </div>

    </div>

@endsection
