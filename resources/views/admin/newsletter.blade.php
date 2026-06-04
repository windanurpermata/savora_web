@extends('admin.layout')
@section('title', 'Kelola Newsletter')
@section('page-title', 'Kelola Newsletter')

@section('content')
<div class="bg-white rounded-xl border border-cokelat-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-cokelat-50 flex items-center justify-between">
        <h2 class="font-serif text-base text-cokelat-800">Daftar Subscriber Newsletter</h2>
        <span class="text-xs bg-cokelat-100 text-cokelat-600 font-bold px-2.5 py-1 rounded-full">
            Total: {{ $subscribers->total() }} subscriber
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-cokelat-50 border-b border-cokelat-100 text-xs font-bold text-cokelat-600 uppercase">
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Tanggal Join</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cokelat-50 text-sm text-cokelat-850">
                @forelse ($subscribers as $sub)
                    <tr class="hover:bg-cokelat-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-cokelat-400">
                            {{ $loop->iteration + ($subscribers->currentPage() - 1) * $subscribers->perPage() }}
                        </td>
                        <td class="px-6 py-4 font-semibold">{{ $sub->email }}</td>
                        <td class="px-6 py-4">
                            @if ($sub->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-bold bg-green-50 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-cokelat-500">
                            {{ $sub->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('admin.newsletter.destroy', $sub->id) }}"
                                onsubmit="return confirm('Hapus subscriber ini dari list newsletter?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs bg-red-50 hover:bg-red-100 text-red-600 font-bold px-3 py-1.5 rounded-lg transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-cokelat-400">
                            Belum ada subscriber newsletter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($subscribers->hasPages())
        <div class="px-6 py-4 border-t border-cokelat-50 bg-cokelat-50/30">
            {{ $subscribers->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
