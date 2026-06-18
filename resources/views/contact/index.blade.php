@extends('layouts.app')
@section('title', 'Hubungi Kami')

@section('content')

    {{-- Header --}}
    <section class="bg-cokelat-800 py-12">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <span
                class="inline-block bg-cokelat-500/20 text-yellow-300 text-xs px-4 py-1.5
                     rounded-full mb-4 tracking-widest font-bold uppercase">
                Hubungi Kami
            </span>
            <h1 class="font-serif text-3xl md:text-4xl text-cokelat-50 mb-3">
                Ada yang Ingin Ditanyakan?
            </h1>
            <p class="text-cokelat-300 text-sm">
                Tim admin kami siap membantu. Pesan Anda akan dibalas dalam 1x24 jam hari kerja.
            </p>
        </div>
    </section>

    <section class="py-12 bg-cokelat-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success --}}
            @if (session('success'))
                <div
                    class="bg-green-50 border border-green-200 text-green-700 rounded-xl
                        px-5 py-4 mb-8 flex items-start gap-3">
                    <span class="text-xl mt-0.5">✅</span>
                    <div>
                        <p class="font-bold text-sm mb-0.5">Pesan berhasil dikirim!</p>
                        <p class="text-xs">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- Info Kontak --}}
                <div class="space-y-4">
                    <h2 class="font-serif text-xl text-cokelat-800 mb-5">Info Kontak</h2>

                    <div class="bg-white border border-cokelat-100 rounded-xl p-4 flex gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-cokelat-100 flex items-center
                                justify-center text-cokelat-500 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0
                                         00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-cokelat-700 mb-0.5">Email</p>
                            <p class="text-xs text-cokelat-500">admin@savora.id</p>
                        </div>
                    </div>

                    <div class="bg-white border border-cokelat-100 rounded-xl p-4 flex gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-cokelat-100 flex items-center
                                justify-center text-cokelat-500 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-cokelat-700 mb-0.5">Jam Dukungan</p>
                            <p class="text-xs text-cokelat-500">Senin – Jumat<br>08.00 – 17.00 WIB</p>
                        </div>
                    </div>

                    <div class="bg-white border border-cokelat-100 rounded-xl p-4 flex gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-cokelat-100 flex items-center
                                justify-center text-cokelat-500 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863
                                         9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3
                                         12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-cokelat-700 mb-0.5">Rata-rata Respons</p>
                            <p class="text-xs text-cokelat-500">Kurang dari 24 jam</p>
                        </div>
                    </div>

                    {{-- Link ke FAQ --}}
                    <div class="bg-cokelat-800 rounded-xl p-4 text-center">
                        <p class="text-xs text-cokelat-300 mb-3">
                            Cek FAQ dulu — mungkin pertanyaanmu sudah terjawab!
                        </p>
                        <a href="{{ route('faq') }}"
                            class="text-xs font-bold text-cokelat-500 hover:text-cokelat-400 transition-colors">
                            Lihat FAQ →
                        </a>
                    </div>
                </div>

                {{-- Form --}}
                <div class="md:col-span-2">
                    <div class="bg-white border border-cokelat-100 rounded-2xl p-6 shadow-sm">
                        <h2 class="font-serif text-xl text-cokelat-800 mb-5">Kirim Pesan</h2>

                        @if ($errors->any())
                            <div
                                class="bg-red-50 border border-red-200 text-red-600 rounded-lg
                                    px-4 py-3 mb-5 text-xs space-y-1">
                                @foreach ($errors->all() as $error)
                                    <p>• {{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-cokelat-700 mb-1">
                                        Nama Lengkap
                                    </label>
                                    <input type="text" name="nama"
                                        value="{{ old('nama', auth()->user()->name ?? '') }}" placeholder="Nama kamu"
                                        required
                                        class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                              px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                              placeholder-cokelat-300 transition-colors
                                              @error('nama') border-red-400 @enderror">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-cokelat-700 mb-1">
                                        Email
                                    </label>
                                    <input type="email" name="email"
                                        value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="Email kamu"
                                        required
                                        class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                              px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                              placeholder-cokelat-300 transition-colors
                                              @error('email') border-red-400 @enderror">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-cokelat-700 mb-1">Topik</label>
                                <select name="topik" required
                                    class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                           px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                           text-cokelat-700 transition-colors
                                           @error('topik') border-red-400 @enderror">
                                    <option value="">Pilih topik...</option>
                                    <option value="pertanyaan_umum"
                                        {{ old('topik') === 'pertanyaan_umum' ? 'selected' : '' }}>Pertanyaan Umum
                                    </option>
                                    <option value="pemulihan_akun_terblokir"
                                        {{ old('topik') === 'pemulihan_akun_terblokir' ? 'selected' : '' }}>Pemulihan Akun Terblokir
                                    </option>
                                    <option value="laporan_masalah"
                                        {{ old('topik') === 'laporan_masalah' ? 'selected' : '' }}>Laporan Masalah
                                    </option>
                                    <option value="daftar_chef"
                                        {{ old('topik') === 'daftar_chef' ? 'selected' : '' }}>Daftar sebagai Chef
                                    </option>
                                    <option value="saran_masukan"
                                        {{ old('topik') === 'saran_masukan' ? 'selected' : '' }}>Saran & Masukan
                                    </option>
                                    <option value="lainnya" {{ old('topik') === 'lainnya' ? 'selected' : '' }}>
                                        Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-cokelat-700 mb-1">Pesan</label>
                                <textarea name="pesan" rows="5" required placeholder="Tulis pesan Anda di sini..."
                                    class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                             px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                             placeholder-cokelat-300 transition-colors resize-none
                                             @error('pesan') border-red-400 @enderror">{{ old('pesan') }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold
                                       py-3 rounded-lg text-sm transition-colors">
                                Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection