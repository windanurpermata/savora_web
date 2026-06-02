@extends('layouts.app')
@section('title', 'Berhenti Langganan')

@section('content')
    <div class="min-h-screen bg-cokelat-50 flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-sm text-center">
            <div class="bg-white rounded-2xl border border-cokelat-100 shadow-sm p-8">
                <div class="text-5xl mb-4">👋</div>
                <h2 class="font-serif text-xl text-cokelat-800 mb-2">
                    Anda telah berhenti berlangganan
                </h2>
                <p class="text-cokelat-400 text-sm leading-relaxed mb-6">
                    Email Anda sudah dihapus dari daftar newsletter kami.
                    Anda tidak akan menerima email dari kami lagi.
                </p>
                <p class="text-cokelat-400 text-xs mb-6">
                    Berubah pikiran? Daftar lagi kapan saja melalui footer website kami.
                </p>
                <a href="{{ route('home') }}"
                    class="inline-block bg-cokelat-700 hover:bg-cokelat-800 text-white
                      font-bold px-6 py-2.5 rounded-lg text-sm transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
