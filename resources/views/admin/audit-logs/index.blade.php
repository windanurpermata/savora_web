@extends('admin.layout')

@section('title', 'Log Audit Keamanan')
@section('page-title', 'Log Audit Keamanan')

@section('content')
    <div class="space-y-6">

        {{-- Top Bar & Search --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <p class="text-xs text-cokelat-500">Menampilkan seluruh catatan riwayat dan aktivitas keamanan administratif sistem Savora.</p>
            </div>
            
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="w-full sm:w-80">
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari aktivitas, user, IP..."
                        class="w-full bg-white border border-cokelat-100 rounded-lg pl-9 pr-4 py-2 text-sm outline-none focus:border-cokelat-500 transition-colors">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-cokelat-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-cokelat-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-cokelat-50 border-b border-cokelat-100 text-xs font-bold uppercase tracking-wider text-cokelat-600">
                            <th class="px-5 py-3.5">Waktu</th>
                            <th class="px-5 py-3.5">Pengguna</th>
                            <th class="px-5 py-3.5">Aktivitas</th>
                            <th class="px-5 py-3.5">IP Address</th>
                            <th class="px-5 py-3.5">User Agent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cokelat-50 text-sm">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-cokelat-50/50 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-cokelat-500">
                                    {{ $log->created_at }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($log->user)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-cokelat-100 text-cokelat-700 flex items-center justify-center text-xs font-bold overflow-hidden">
                                                @if ($log->user->foto)
                                                    <img src="{{ asset('storage/' . $log->user->foto) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-xs text-cokelat-800">{{ $log->user->name }}</p>
                                                <p class="text-[10px] text-cokelat-400 leading-none">{{ $log->user->email }} ({{ $log->user->role }})</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-cokelat-400 font-italic">Guest / Anonim</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-xs font-semibold text-cokelat-700">
                                    {{ $log->activity }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs font-mono text-cokelat-500">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-5 py-4 text-xs text-cokelat-400 max-w-xs truncate" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-cokelat-400 text-xs">
                                    Belum ada log audit keamanan yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($logs->hasPages())
                <div class="px-5 py-4 border-t border-cokelat-50 bg-cokelat-50/20">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
