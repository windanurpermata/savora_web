@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
    <div class="min-h-screen bg-cokelat-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">

            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="font-serif text-2xl text-cokelat-800">
                    Dapur<span class="text-cokelat-500">Nusantara</span>
                </a>
                <p class="text-cokelat-400 text-xs mt-1">Masuk ke akun Anda</p>
            </div>

            <div class="bg-white rounded-xl border border-cokelat-100 shadow-sm p-6">

                {{-- Success / Info --}}
                @if (session('success'))
                    <div
                        class="bg-green-50 border border-green-200 text-green-700 rounded-lg
                            px-3 py-2 mb-4 text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error --}}
                @if ($errors->any())
                    <div
                        class="bg-red-50 border border-red-200 text-red-600 rounded-lg
                            px-3 py-2 mb-4 text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>

                    {{-- Tombol kirim ulang verifikasi jika email belum diverifikasi --}}
                    @if (str_contains($errors->first(), 'belum diverifikasi'))
                        <form method="POST" action="{{ route('verification.resend') }}" class="mb-4">
                            @csrf
                            <input type="hidden" name="email" value="{{ old('email') }}">
                            <button type="submit"
                                class="w-full border border-cokelat-300 hover:bg-cokelat-50
                                       text-cokelat-600 text-xs font-bold py-2 rounded-lg transition-colors">
                                Kirim Ulang Email Verifikasi
                            </button>
                        </form>
                    @endif
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                            required autofocus
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                  px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                  placeholder-cokelat-300 transition-colors
                                  @error('email') border-red-400 @enderror">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="pwd" placeholder="••••••••" required
                                class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                      px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                      placeholder-cokelat-300 transition-colors pr-9
                                      @error('password') border-red-400 @enderror">
                            <button type="button" onclick="togglePwd('pwd')"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2
                                       text-cokelat-300 hover:text-cokelat-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542
                                             7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-xs font-bold text-cokelat-700">Password</label> <a href="{{ route('password.request') }}"class="text-xs text-cokelat-400 hover:text-cokelat-600">Lupa password?</a></div>

                    {{-- CAPTCHA --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1">
                            Verifikasi: Berapa hasil dari
                            <span class="text-cokelat-500 font-extrabold">
                                {{ session('captcha_question', '? + ?') }} =
                            </span>
                        </label>
                        <input type="number" name="captcha" placeholder="Jawaban" required
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                  px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                  placeholder-cokelat-300 transition-colors
                                  @error('captcha') border-red-400 @enderror">
                        @error('captcha')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" id="remember"
                            class="w-3.5 h-3.5 accent-cokelat-500 cursor-pointer">
                        <label for="remember" class="text-xs text-cokelat-400 cursor-pointer">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold
                               py-2.5 rounded-lg text-sm transition-colors">
                        Masuk
                    </button>
                </form>

                {{-- Info keamanan --}}
                <div class="mt-4 bg-cokelat-50 rounded-lg px-3 py-2">
                    <p class="text-xs text-cokelat-400 text-center">
                        🔒 Akun akan dikunci sementara setelah
                        <strong class="text-cokelat-600">3x</strong> percobaan gagal
                    </p>
                </div>

                <p class="text-center text-xs text-cokelat-400 mt-4">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-cokelat-500 hover:text-cokelat-400">Daftar</a>
                </p>
            </div>

            <p class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-cokelat-400 hover:text-cokelat-600 text-xs">
                    ← Kembali ke Beranda
                </a>
            </p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePwd(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endpush