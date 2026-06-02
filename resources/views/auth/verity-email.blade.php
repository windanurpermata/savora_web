@extends('layouts.app')
@section('title', 'Verifikasi Email')

@section('content')
    <div class="min-h-screen bg-cokelat-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm text-center">

            <div class="bg-white rounded-xl border border-cokelat-100 shadow-sm p-8">
                <div class="text-5xl mb-4">📧</div>
                <h2 class="font-serif text-xl text-cokelat-800 mb-2">Cek Email Anda</h2>
                <p class="text-cokelat-400 text-sm mb-6 leading-relaxed">
                    Kami sudah mengirim link verifikasi ke
                    <strong class="text-cokelat-700">{{ Auth::user()->email }}</strong>.
                    Klik link tersebut untuk mengaktifkan akun Anda.
                </p>

                @if (session('success'))
                    <div
                        class="bg-green-50 border border-green-200 text-green-700 rounded-lg
                            px-3 py-2 mb-4 text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                    <button type="submit"
                        class="w-full border border-cokelat-300 hover:bg-cokelat-50
                               text-cokelat-600 text-sm font-bold py-2.5 rounded-lg
                               transition-colors mb-3">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-cokelat-400 hover:text-cokelat-600">
                        Keluar dari akun ini
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection