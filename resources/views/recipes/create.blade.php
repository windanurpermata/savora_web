@extends('contributor.layout')
@section('title', 'Buat Resep Baru')
@section('page-title', 'Buat Resep Baru')

@section('content')

    <div class="max-w-3xl mx-auto">

        {{-- Back --}}
        <a href="{{ route('contributor.dashboard') }}"
            class="inline-flex items-center gap-1 text-xs text-cokelat-400 hover:text-cokelat-600 mb-5 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Dashboard
        </a>

        <form method="POST" action="{{ route('contributor.recipes.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Judul --}}
            <div class="bg-white rounded-xl border border-cokelat-100 p-5">
                <h3 class="font-serif text-base text-cokelat-800 mb-4">Informasi Resep</h3>

                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Judul Resep <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required
                            placeholder="Contoh: Nasi Goreng Spesial"
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                               text-sm outline-none focus:border-cokelat-500 placeholder-cokelat-300
                               transition-colors @error('judul') border-red-400 @enderror">
                        @error('judul')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Kategori --}}
                        <div>
                            <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                                Kategori
                            </label>
                            <select name="category_id"
                                class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm outline-none focus:border-cokelat-500 transition-colors cursor-pointer">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoriList as $kat)
                                    <option value="{{ $kat->id }}"
                                        {{ old('category_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Waktu Memasak --}}
                        <div>
                            <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                                Waktu Memasak (menit) <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="waktu_memasak" value="{{ old('waktu_memasak', 30) }}" min="1"
                                required
                                class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm outline-none focus:border-cokelat-500 transition-colors
                                   @error('waktu_memasak') border-red-400 @enderror">
                            @error('waktu_memasak')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Porsi --}}
                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Jumlah Porsi <span class="text-red-400">*</span>
                        </label>
                        <input type="number" name="porsi" value="{{ old('porsi', 2) }}" min="1" required
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                               text-sm outline-none focus:border-cokelat-500 transition-colors
                               @error('porsi') border-red-400 @enderror">
                        @error('porsi')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" rows="4" placeholder="Ceritakan sedikit tentang resep ini..."
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                               text-sm outline-none focus:border-cokelat-500 placeholder-cokelat-300
                               transition-colors resize-none">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Upload Gambar --}}
            <div class="bg-white rounded-xl border border-cokelat-100 p-5">
                <h3 class="font-serif text-base text-cokelat-800 mb-4">Foto Resep</h3>

                <div class="border-2 border-dashed border-cokelat-200 rounded-xl p-6 text-center hover:border-cokelat-400 transition-colors cursor-pointer"
                    onclick="document.getElementById('gambar').click()">
                    <div id="preview-wrapper">
                        <svg class="w-10 h-10 text-cokelat-300 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm text-cokelat-400">Klik untuk upload foto resep</p>
                        <p class="text-xs text-cokelat-300 mt-1">JPG, PNG, WEBP — maks. 2MB</p>
                    </div>
                    <img id="preview-img" src="" alt="Preview"
                        class="hidden mx-auto max-h-48 rounded-lg object-cover mt-2">
                </div>
                <input type="file" id="gambar" name="gambar" accept="image/*" class="hidden"
                    onchange="previewGambar(this)">
                @error('gambar')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold px-6 py-2.5
                       rounded-xl text-sm transition-colors">
                    Publikasikan Resep
                </button>
                <a href="{{ route('contributor.dashboard') }}"
                    class="text-sm text-cokelat-400 hover:text-cokelat-600 transition-colors">
                    Batal
                </a>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
    <script>
        function previewGambar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-wrapper').classList.add('hidden');
                    const img = document.getElementById('preview-img');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
