@extends(Auth::user()->role === 'admin' ? 'admin.layout' : 'layouts.app')

@section('title', 'Setup 2FA')
@section('page-title', 'Setup 2FA')

@section('content')
    <div class="max-w-md mx-auto px-4 py-8">
        {{-- Back --}}
        <a href="{{ route('profile.edit') }}"
            class="inline-flex items-center gap-1 text-xs text-cokelat-400 hover:text-cokelat-600 mb-5 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Edit Profil
        </a>

        <div class="bg-white rounded-2xl border border-cokelat-100 p-6 shadow-md space-y-6">
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-cokelat-100 text-cokelat-700 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="font-serif text-lg text-cokelat-800 font-bold">Setup Autentikasi Dua Faktor (2FA)</h2>
                <p class="text-xs text-cokelat-400">Ikuti langkah di bawah ini untuk menghubungkan aplikasi Authenticator Anda.</p>
            </div>

            <div class="space-y-4">
                {{-- Step 1 --}}
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-cokelat-600 uppercase tracking-wider">Langkah 1: Scan QR Code</h4>
                    <p class="text-xs text-cokelat-500">Scan QR Code di bawah menggunakan aplikasi Google Authenticator, Microsoft Authenticator, atau sejenisnya.</p>
                    <div class="flex justify-center p-3 bg-cokelat-50 rounded-xl border border-cokelat-100 w-fit mx-auto">
                        <img src="{{ $qrCodeUrl }}" alt="QR Code 2FA" class="w-48 h-48">
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-cokelat-600 uppercase tracking-wider">Langkah 2: Salin Kunci Manual (Opsional)</h4>
                    <p class="text-xs text-cokelat-500">Jika tidak bisa men-scan QR, masukkan kunci rahasia berikut secara manual ke aplikasi Anda:</p>
                    <div class="bg-cokelat-50 border border-cokelat-200 rounded-xl px-4 py-2 text-center">
                        <code class="text-sm font-mono font-bold text-cokelat-800 tracking-widest">{{ $secret }}</code>
                    </div>
                </div>

                <hr class="border-cokelat-100">

                {{-- Step 3 --}}
                <form action="{{ route('mfa.enable') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <h4 class="text-xs font-bold text-cokelat-600 uppercase tracking-wider">Langkah 3: Masukkan Kode Verifikasi</h4>
                        <p class="text-xs text-cokelat-500 mb-2">Masukkan 6 digit kode yang tampil di aplikasi authenticator Anda untuk memverifikasi.</p>
                        <input type="text" name="code" required max="6" pattern="[0-9]*" inputmode="numeric"
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-xl px-4 py-3 text-center
                                   text-lg font-mono font-bold tracking-widest outline-none focus:border-cokelat-500 transition-colors
                                   @error('code') border-red-400 @enderror"
                            placeholder="000000">
                        @error('code')
                            <p class="text-xs text-red-500 text-center mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold py-3
                               rounded-xl text-sm transition-colors shadow-sm">
                        Aktifkan & Simpan 2FA
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
