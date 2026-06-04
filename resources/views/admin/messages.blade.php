@extends('admin.layout')
@section('title', 'Pesan Masuk')
@section('page-title', 'Pesan Masuk')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Daftar Pesan --}}
        <div class="bg-white rounded-xl border border-cokelat-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-cokelat-50 flex items-center justify-between">
                <h2 class="font-serif text-base text-cokelat-800">Semua Pesan</h2>
                <span class="text-xs bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded-full">
                    {{ $pesanBelumDibaca }} belum dibaca
                </span>
            </div>
            <div class="divide-y divide-cokelat-50 overflow-y-auto max-h-[600px]">
                @forelse($messages as $msg)
                    <a href="{{ route('admin.messages.show', $msg->id) }}"
                        class="flex items-start gap-3 px-4 py-3 hover:bg-cokelat-50 transition-colors block
                          {{ !$msg->is_read ? 'bg-cokelat-50' : '' }}
                          {{ isset($selectedMessage) && $selectedMessage->id === $msg->id ? 'border-l-2 border-cokelat-500' : '' }}">
                        <div
                            class="w-8 h-8 rounded-full bg-cokelat-700 flex items-center
                                justify-center text-yellow-200 text-xs font-bold flex-shrink-0 mt-0.5">
                            {{ strtoupper(substr($msg->nama, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <p class="text-xs font-bold text-cokelat-800 truncate">{{ $msg->nama }}</p>
                                @if (!$msg->is_read)
                                    <span class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>
                                @endif
                            </div>
                            <p class="text-xs text-cokelat-500 truncate">{{ $msg->pesan }}</p>
                            <p class="text-xs text-cokelat-300 mt-0.5">
                                {{ $msg->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-cokelat-400 text-sm py-10">Belum ada pesan masuk.</p>
                @endforelse
            </div>
            @if ($messages->hasPages())
                <div class="px-4 py-3 border-t border-cokelat-50">
                    {{ $messages->links('pagination::tailwind') }}
                </div>
            @endif
        </div>

        {{-- Detail Pesan --}}
        <div class="lg:col-span-2">
            @if (isset($selectedMessage))
                <div class="bg-white rounded-xl border border-cokelat-100 p-6">

                    {{-- Header --}}
                    <div class="flex items-start justify-between mb-5 pb-5 border-b border-cokelat-50">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-cokelat-700 flex items-center
                                    justify-center text-yellow-200 font-bold">
                                {{ strtoupper(substr($selectedMessage->nama, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-bold text-cokelat-800">{{ $selectedMessage->nama }}</p>
                                <p class="text-xs text-cokelat-400">{{ $selectedMessage->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs bg-cokelat-100 text-cokelat-600 px-2.5 py-1 rounded-full font-bold">
                                {{ str_replace('_', ' ', ucfirst($selectedMessage->topik)) }}
                            </span>
                            <p class="text-xs text-cokelat-400 mt-1">
                                {{ $selectedMessage->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>

                    {{-- Isi Pesan --}}
                    <div class="mb-6">
                        <p class="text-xs font-bold text-cokelat-600 uppercase tracking-wider mb-2">Pesan</p>
                        <p
                            class="text-sm text-cokelat-700 leading-relaxed bg-cokelat-50
                               rounded-lg p-4 border border-cokelat-100">
                            {{ $selectedMessage->pesan }}
                        </p>
                    </div>

                    {{-- Form Balas --}}
                    <div>
                        <p class="text-xs font-bold text-cokelat-600 uppercase tracking-wider mb-3">
                            Balas via Email
                        </p>
                        @if ($errors->any())
                            <div class="mb-3 p-3 bg-red-50 border border-red-200 text-red-650 rounded-lg text-xs">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.messages.reply', $selectedMessage->id) }}"
                            class="space-y-3">
                            @csrf
                            <textarea name="balasan" rows="4" required placeholder="Tulis balasan Anda..."
                                class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                         px-4 py-3 text-sm outline-none focus:border-cokelat-500
                                         placeholder-cokelat-300 transition-colors resize-none">{{ old('balasan') }}</textarea>
                            <button type="submit"
                                class="bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold
                                       px-5 py-2.5 rounded-lg text-sm transition-colors">
                                Kirim Balasan
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.messages.destroy', $selectedMessage->id) }}"
                            onsubmit="return confirm('Hapus pesan ini?')" class="mt-3 inline-block">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-sm text-red-400 hover:text-red-650 transition-colors font-bold">
                                Hapus Pesan
                            </button>
                        </form>
                    </div>

                </div>
            @else
                <div
                    class="bg-white rounded-xl border border-cokelat-100 h-64
                        flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-4xl mb-3">📬</div>
                        <p class="text-cokelat-400 text-sm">Pilih pesan untuk membacanya</p>
                    </div>
                </div>
            @endif
        </div>

    </div>

@endsection