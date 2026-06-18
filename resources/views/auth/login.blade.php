@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
    <div class="min-h-screen bg-cokelat-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">

            <div class="flex flex-col items-center mb-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Savora Logo" class="w-16 h-16 object-cover rounded-xl shadow-sm border border-cokelat-100 mb-1">
                    <span class="font-serif text-2xl text-cokelat-800">Savora</span>
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
                            <p>• {!! $error !!}</p>
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
                    <div class="flex flex-col items-center justify-center py-2">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        @error('g-recaptcha-response')
                            <p class="text-red-500 text-xs mt-1 text-center w-full">{{ $message }}</p>
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

                <div class="my-4 flex items-center justify-between">
                    <span class="border-b border-cokelat-100 w-1/5 lg:w-1/4"></span>
                    <span class="text-xs text-center text-cokelat-400 uppercase">atau masuk dengan</span>
                    <span class="border-b border-cokelat-100 w-1/5 lg:w-1/4"></span>
                </div>

                <a href="{{ route('auth.google') }}"
                    class="w-full flex items-center justify-center gap-2 border border-cokelat-200 hover:bg-cokelat-50 text-cokelat-700 font-bold py-2.5 rounded-lg text-sm transition-colors mb-4">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                    </svg>
                    Google
                </a>

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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function togglePwd(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endpush