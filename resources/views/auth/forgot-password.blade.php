@extends('layouts.app')
@section('title', 'Lupa Password')

@section('content')
    <div class="min-h-screen bg-cokelat-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">

            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="font-serif text-2xl text-cokelat-800">
                    Dapur<span class="text-cokelat-500">Nusantara</span>
                </a>
                <p class="text-cokelat-400 text-xs mt-1">Reset password akun Anda</p>
            </div>

            <div class="bg-white rounded-xl border border-cokelat-100 shadow-sm p-6">

                {{-- Success --}}
                @if (session('status'))
                    <div
                        class="bg-green-50 border border-green-200 text-green-700 rounded-lg
                            px-3 py-3 mb-4 text-xs text-center">
                        <div class="text-2xl mb-1">📧</div>
                        <p class="font-bold mb-1">Link reset password sudah dikirim!</p>
                        <p>Cek inbox email Anda dan klik link yang dikirimkan untuk mereset password.</p>
                    </div>
                @endif

                {{-- Error --}}
                @if ($errors->any())
                    <div
                        class="bg-red-50 border border-red-200 text-red-600 rounded-lg
                            px-3 py-2 mb-4 text-xs">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (!session('status'))
                    <div class="mb-5">
                        <p class="text-xs text-cokelat-500 leading-relaxed">
                            Masukkan email yang terdaftar. Kami akan mengirimkan link untuk mereset password Anda.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-cokelat-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                                required autofocus
                                class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                      px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                      placeholder-cokelat-300 transition-colors
                                      @error('email') border-red-400 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold
                                   py-2.5 rounded-lg text-sm transition-colors">
                            Kirim Link Reset Password
                        </button>
                    </form>
                @endif

                <p class="text-center text-xs text-cokelat-400 mt-4">
                    Ingat password?
                    <a href="{{ route('login') }}" class="font-bold text-cokelat-500 hover:text-cokelat-400">Masuk</a>
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
