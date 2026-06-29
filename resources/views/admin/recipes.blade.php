@extends('admin.layout')
@section('title', 'Kelola Resep')
@section('page-title', 'Kelola Resep')

@section('content')

    <div class="bg-white rounded-xl border border-cokelat-100 p-4 mb-5 flex flex-wrap gap-3 items-center">
        <form method="GET" action="{{ route('admin.recipes.index') }}" class="flex flex-wrap gap-3 flex-1">
            <div
                class="flex items-center flex-1 min-w-[200px] bg-cokelat-50 border border-cokelat-200
                    rounded-lg px-3 gap-2">
                <svg class="w-3.5 h-3.5 text-cokelat-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul resep..."
                    class="bg-transparent w-full py-2 text-xs outline-none text-cokelat-800
                          placeholder-cokelat-300">
            </div>
            <select name="kategori" onchange="this.form.submit()"
                class="bg-cokelat-50 border border-cokelat-200 rounded-lg px-3 py-2
                       text-xs text-cokelat-700 outline-none cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach ($kategoriList as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-cokelat-700 text-white text-xs font-bold px-4 py-2 rounded-lg">Cari</button>
            @if (request('q') || request('kategori'))
                <a href="{{ route('admin.recipes.index') }}"
                    class="text-xs text-cokelat-400 hover:text-cokelat-600 self-center">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl border border-cokelat-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-cokelat-50 border-b border-cokelat-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-cokelat-600 uppercase tracking-wider">
                        Resep
                    </th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-cokelat-600 uppercase tracking-wider">
                        Chef
                    </th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-cokelat-600 uppercase tracking-wider">
                        Kategori
                    </th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-cokelat-600 uppercase tracking-wider">
                        Rating
                    </th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-cokelat-600 uppercase tracking-wider">
                        Dibuat
                    </th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cokelat-50">
                @forelse($recipes as $resep)
                    <tr class="hover:bg-cokelat-50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-cokelat-100 overflow-hidden flex-shrink-0">
                                    @if ($resep->gambar)
                                        <img src="{{ asset('storage/' . $resep->gambar) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">🍴</div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-cokelat-800">{{ $resep->judul }}</p>
                                    <p class="text-xs text-cokelat-400">⏱ {{ $resep->waktu_memasak }} mnt · 👥
                                        {{ $resep->porsi }} porsi</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-cokelat-600">
                            {{ $resep->user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs bg-cokelat-100 text-cokelat-600 px-2 py-0.5 rounded-full">
                                {{ $resep->kategori->nama ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-cokelat-600">
                            ⭐ {{ number_format($resep->ratings_avg_nilai ?? 0, 1) }}
                            <span class="text-cokelat-300">({{ $resep->ratings_count }})</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-cokelat-400">
                            {{ $resep->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3 justify-end">
                                <a href="{{ route('recipes.show', $resep->id) }}" target="_blank"
                                    class="text-xs text-cokelat-500 hover:text-cokelat-700">Lihat</a>
                                <form method="POST" action="{{ route('admin.recipes.destroy', $resep->id) }}"
                                    onsubmit="return confirm('Hapus resep ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-xs text-red-400 hover:text-red-600 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-cokelat-400 text-sm">
                            Tidak ada resep ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($recipes->hasPages())
            <div class="px-5 py-4 border-t border-cokelat-50">
                {{ $recipes->withQueryString()->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

@endsection
