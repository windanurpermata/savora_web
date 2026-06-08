@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Back --}}
        <a href="{{ route('chef.dashboard') }}"
            class="inline-flex items-center gap-1 text-xs text-cokelat-400 hover:text-cokelat-600 mb-5 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Dashboard
        </a>

        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('chef.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Foto Profil --}}
            <div class="bg-white rounded-xl border border-cokelat-100 p-5">
                <h3 class="font-serif text-base text-cokelat-800 mb-4">Foto Profil</h3>
                <div class="flex items-center gap-5">
                    <div
                        class="w-20 h-20 rounded-full overflow-hidden bg-cokelat-100 flex-shrink-0 border-2 border-cokelat-200">
                        @if (Auth::user()->foto)
                            <img id="foto-preview" src="{{ asset('storage/' . Auth::user()->foto) }}"
                                alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <div id="foto-placeholder"
                                class="w-full h-full flex items-center justify-center bg-cokelat-700 text-white text-2xl font-serif">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <img id="foto-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                    <div>
                        <button type="button" onclick="document.getElementById('foto').click()"
                            class="text-sm font-semibold text-cokelat-700 border border-cokelat-300
                                   px-4 py-2 rounded-lg hover:bg-cokelat-50 transition-colors">
                            Ganti Foto
                        </button>
                        <p class="text-xs text-cokelat-400 mt-1">JPG, PNG — maks. 2MB</p>
                        <input type="file" id="foto" name="foto" accept="image/*" class="hidden"
                            onchange="previewFoto(this)">
                        @error('foto')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Info Pribadi --}}
            <div class="bg-white rounded-xl border border-cokelat-100 p-5">
                <h3 class="font-serif text-base text-cokelat-800 mb-4">Informasi Pribadi</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm outline-none focus:border-cokelat-500 transition-colors
                                   @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Email
                        </label>
                        <input type="email" value="{{ Auth::user()->email }}" disabled
                            class="w-full bg-cokelat-100 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm text-cokelat-400 cursor-not-allowed">
                        <p class="text-xs text-cokelat-400 mt-1">Email tidak dapat diubah.</p>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Bio
                        </label>
                        <textarea name="bio" rows="3" placeholder="Ceritakan sedikit tentang dirimu..."
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm outline-none focus:border-cokelat-500 placeholder-cokelat-300
                                   transition-colors resize-none">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Ganti Password --}}
            <div class="bg-white rounded-xl border border-cokelat-100 p-5">
                <h3 class="font-serif text-base text-cokelat-800 mb-4">Ganti Password</h3>
                <p class="text-xs text-cokelat-400 mb-4">Kosongkan jika tidak ingin mengganti password.</p>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Password Baru
                        </label>
                        <input type="password" name="password"
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm outline-none focus:border-cokelat-500 transition-colors
                                   @error('password') border-red-400 @enderror"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation"
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm outline-none focus:border-cokelat-500 transition-colors"
                            placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center gap-3">
