@extends('admin.layout')
@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Form Tambah --}}
        <div class="bg-white rounded-xl border border-cokelat-100 p-5 h-fit">
            <h2 class="font-serif text-base text-cokelat-800 mb-4">Tambah Kategori</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 rounded-lg px-3 py-2 mb-4 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-cokelat-700 mb-1">Nama Kategori</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="contoh: Sarapan" required
                        class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                              px-3 py-2 text-sm outline-none focus:border-cokelat-500
                              placeholder-cokelat-300 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-cokelat-700 mb-1">Emoji</label>
                    <input type="text" name="emoji" value="{{ old('emoji') }}" placeholder="contoh: 🌅" maxlength="5"
                        class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                              px-3 py-2 text-sm outline-none focus:border-cokelat-500
                              placeholder-cokelat-300 transition-colors">
                </div>
                <button type="submit"
                    class="w-full bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold
                           py-2.5 rounded-lg text-sm transition-colors">
                    Tambah Kategori
                </button>
            </form>
        </div>

        {{-- Daftar Kategori --}}
        <div class="md:col-span-2 bg-white rounded-xl border border-cokelat-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-cokelat-50">
                <h2 class="font-serif text-base text-cokelat-800">
                    Semua Kategori ({{ $categories->count() }})
                </h2>
            </div>
            <div class="divide-y divide-cokelat-50">
                @forelse($categories as $kat)
                    <div class="flex items-center gap-4 px-5 py-3">
                        <span class="text-2xl w-8 text-center">{{ $kat->emoji ?? '🍽' }}</span>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-cokelat-800">{{ $kat->nama }}</p>
                            <p class="text-xs text-cokelat-400">{{ $kat->recipes_count }} resep</p>
                        </div>

                        {{-- Form Edit Inline --}}
                        <form method="POST" action="{{ route('admin.categories.update', $kat->id) }}"
                            class="flex items-center gap-2">
                            @csrf @method('PUT')
                            <input type="text" name="nama" value="{{ $kat->nama }}"
                                class="text-xs bg-cokelat-50 border border-cokelat-200 rounded-lg
                                      px-2 py-1.5 outline-none focus:border-cokelat-500 w-28">
                            <input type="text" name="emoji" value="{{ $kat->emoji }}" maxlength="5"
                                class="text-xs bg-cokelat-50 border border-cokelat-200 rounded-lg
                                      px-2 py-1.5 outline-none focus:border-cokelat-500 w-12 text-center">
                            <button type="submit"
                                class="text-xs bg-cokelat-100 hover:bg-cokelat-200 text-cokelat-700
                                       px-2.5 py-1.5 rounded-lg transition-colors font-bold">
                                Simpan
                            </button>
                        </form>

                        {{-- Hapus --}}
                        <form method="POST" action="{{ route('admin.categories.destroy', $kat->id) }}"
                            onsubmit="return confirm('Hapus kategori {{ $kat->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-center text-cokelat-400 text-sm py-10">Belum ada kategori.</p>
                @endforelse
            </div>
        </div>

    </div>

@endsection
