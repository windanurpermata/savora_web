@extends('layouts.app')
@section('title', 'Verifikasi 2FA')

@section('content')
    <div class="min-h-screen bg-cokelat-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">

            <div class="flex flex-col items-center mb-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Savora Logo" class="w-16 h-16 object-cover rounded-xl shadow-sm border border-cokelat-100 mb-1">
                    <span class="font-serif text-2xl text-cokelat-800">Savora</span>
                </a>
                <p class="text-cokelat-400 text-xs mt-1">Autentikasi Dua Faktor</p>
            </div>

            <div class="bg-white rounded-xl border border-cokelat-100 shadow-sm p-6 space-y-4">
                <div class="text-center space-y-1">
                    <h3 class="font-serif text-base text-cokelat-800 font-bold">Verifikasi Identitas</h3>
                    <p class="text-xs text-cokelat-400">Masukkan 6 digit kode dari aplikasi authenticator Anda.</p>
                </div>

                {{-- Error --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 rounded-lg px-3 py-2 text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('mfa.verify.post') }}" class="space-y-4">
                    @csrf

                    <div>
                        <input type="text" name="code" required max="6" pattern="[0-9]*" inputmode="numeric" autofocus
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-3 text-center
                                   text-2xl font-mono font-bold tracking-widest outline-none focus:border-cokelat-500 transition-colors
                                   @error('code') border-red-400 @enderror"
                            placeholder="000000">
                    </div>

                    <button type="submit"
                        class="w-full bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold
                               py-2.5 rounded-lg text-sm transition-colors shadow-sm">
                        Verifikasi
                    </button>
                </form>

                <div class="border-t border-cokelat-100 pt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center text-xs text-cokelat-400 hover:text-cokelat-600 transition-colors">
                            Batal & Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
