@extends('admin.layout')
@section('title', 'Kelola User')
@section('page-title', 'Kelola User')

@section('content')

    {{-- Filter & Search --}}
    <div class="bg-white rounded-xl border border-cokelat-100 p-4 mb-5 flex flex-wrap gap-3 items-center">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 flex-1">
            <div
                class="flex items-center flex-1 min-w-[200px] bg-cokelat-50 border border-cokelat-200
                    rounded-lg px-3 gap-2">
                <svg class="w-3.5 h-3.5 text-cokelat-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
                    class="bg-transparent w-full py-2 text-xs outline-none text-cokelat-800
                          placeholder-cokelat-300">
            </div>
            <select name="role" onchange="this.form.submit()"
                class="bg-cokelat-50 border border-cokelat-200 rounded-lg px-3 py-2
                       text-xs text-cokelat-700 outline-none cursor-pointer">
                <option value="">Semua Role</option>
                <option value="member" {{ request('role') === 'member' ? 'selected' : '' }}>Member</option>
                <option value="contributor" {{ request('role') === 'contributor' ? 'selected' : '' }}>Contributor</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="bg-cokelat-700 text-white text-xs font-bold px-4 py-2 rounded-lg">
                Cari
            </button>
            @if (request('q') || request('role'))
                <a href="{{ route('admin.users.index') }}"
                    class="text-xs text-cokelat-400 hover:text-cokelat-600 self-center">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel User --}}
    <div class="bg-white rounded-xl border border-cokelat-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-cokelat-50 border-b border-cokelat-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-cokelat-600 uppercase tracking-wider">
                        User
                    </th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-cokelat-600 uppercase tracking-wider">
                        Role
                    </th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-cokelat-600 uppercase tracking-wider">
                        Nomor Telepon
                    </th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-cokelat-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="text-left px-4 py-3 text-xs font-bold text-cokelat-600 uppercase tracking-wider">
                        Bergabung
                    </th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cokelat-50">
                @forelse($users as $user)
                    <tr class="hover:bg-cokelat-50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-cokelat-700 flex items-center
                                        justify-center text-yellow-200 text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-cokelat-800">{{ $user->name }}</p>
                                    <p class="text-xs text-cokelat-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="text-xs px-2.5 py-1 rounded-full font-bold
                            {{ $user->role === 'admin'
                                ? 'bg-purple-100 text-purple-700'
                                : ($user->role === 'contributor'
                                    ? 'bg-cokelat-100 text-cokelat-700'
                                    : 'bg-blue-100 text-blue-700') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-cokelat-600">
                            {{ $user->phone_number ?: '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-1 items-start">
                                @if ($user->email_verified_at)
                                    <span class="text-[10px] font-bold text-green-600 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Terverifikasi
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold text-red-500 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Belum Verifikasi
                                    </span>
                                @endif

                                @if ($user->is_blocked)
                                    <span class="text-[10px] font-bold text-amber-600 flex items-center gap-1 bg-amber-50 px-1.5 py-0.5 rounded">
                                        🔒 Diblokir
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1 bg-emerald-50 px-1.5 py-0.5 rounded">
                                        ✓ Aktif
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-cokelat-400">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3 justify-end">
                                {{-- Ganti Role --}}
                                @if ($user->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                                        @csrf @method('PUT')
                                        <select name="role" onchange="this.form.submit()"
                                            class="text-xs bg-cokelat-50 border border-cokelat-200
                                                   rounded-lg px-2 py-1 outline-none cursor-pointer
                                                   text-cokelat-700">
                                            <option value="member" {{ $user->role === 'member' ? 'selected' : '' }}>Member
                                            </option>
                                            <option value="contributor" {{ $user->role === 'contributor' ? 'selected' : '' }}>Contributor
                                            </option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin
                                            </option>
                                        </select>
                                    </form>

                                    {{-- Blokir / Buka Blokir --}}
                                    <form method="POST" action="{{ route('admin.users.toggle-block', $user->id) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin {{ $user->is_blocked ? 'membuka blokir' : 'memblokir' }} user {{ $user->name }}?')">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs font-bold transition-colors {{ $user->is_blocked ? 'text-green-600 hover:text-green-800' : 'text-amber-600 hover:text-amber-800' }}">
                                            {{ $user->is_blocked ? 'Buka Blokir' : 'Blokir' }}
                                        </button>
                                    </form>

                                    {{-- Hapus --}}
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                        onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-xs text-red-400 hover:text-red-600 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-cokelat-300">Akun saya</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-cokelat-400 text-sm">
                            Tidak ada user ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($users->hasPages())
            <div class="px-5 py-4 border-t border-cokelat-50">
                {{ $users->withQueryString()->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

@endsection
