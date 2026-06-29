@extends(Auth::user()->role === 'admin' ? 'admin.layout' : 'layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Back --}}
        @php
            $backRoute = route('home');
            if (Auth::user()->isAdmin()) {
                $backRoute = route('admin.dashboard');
            } elseif (Auth::user()->isContributor()) {
                $backRoute = route('contributor.dashboard');
            }
        @endphp
        <a href="{{ $backRoute }}"
            class="inline-flex items-center gap-1 text-xs text-cokelat-400 hover:text-cokelat-600 mb-5 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>

        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Foto Profil --}}
            <div class="bg-white rounded-xl border border-cokelat-100 p-5 shadow-sm">
                <h3 class="font-serif text-base text-cokelat-800 mb-4">Foto Profil</h3>
                <div class="flex items-center gap-5">
                    <div
                        class="w-20 h-20 rounded-full overflow-hidden bg-cokelat-100 flex-shrink-0 border-2 border-cokelat-200">
                        @if (Auth::user()->foto)
                            <img id="foto-preview" src="{{ asset('storage/' . Auth::user()->foto) }}"
                                alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <div id="foto-placeholder"
                                class="w-full h-full flex items-center justify-center bg-cokelat-700 text-white text-2xl font-serif">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <img id="foto-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                    <div>
                        <button type="button" onclick="document.getElementById('foto').click()"
                            class="text-sm font-semibold text-cokelat-700 border border-cokelat-300
                                   px-4 py-2 rounded-lg hover:bg-cokelat-50 transition-colors">
                            Ganti Foto
                        </button>
                        <p class="text-xs text-cokelat-400 mt-1">JPG, PNG — maks. 2MB</p>
                        <input type="file" id="foto" name="foto" accept="image/*" class="hidden"
                            onchange="previewFoto(this)">
                        @error('foto')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Info Pribadi --}}
            <div class="bg-white rounded-xl border border-cokelat-100 p-5 shadow-sm">
                <h3 class="font-serif text-base text-cokelat-800 mb-4">Informasi Pribadi</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm outline-none focus:border-cokelat-500 transition-colors
                                   @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Email
                        </label>
                        <input type="email" value="{{ Auth::user()->email }}" disabled
                            class="w-full bg-cokelat-100 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm text-cokelat-400 cursor-not-allowed">
                        <p class="text-xs text-cokelat-400 mt-1">Email tidak dapat diubah.</p>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Nomor Telepon
                        </label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}"
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm outline-none focus:border-cokelat-500 transition-colors
                                   @error('phone_number') border-red-400 @enderror"
                            placeholder="Contoh: 081234567890">
                        @error('phone_number')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            @if (Auth::user()->isAdmin() || Auth::user()->isContributor())
                {{-- Multi-Factor Authentication --}}
                <div class="bg-white rounded-xl border border-cokelat-100 p-5 shadow-sm space-y-4">
                    <div>
                        <h3 class="font-serif text-base text-cokelat-800">Autentikasi Dua Faktor (2FA)</h3>
                        <p class="text-xs text-cokelat-400">Meningkatkan keamanan akun Anda dengan menambahkan verifikasi TOTP dari aplikasi Authenticator.</p>
                    </div>
                    
                    @if (Auth::user()->mfa_enabled)
                        <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-green-800">2FA Aktif</p>
                                    <p class="text-xs text-green-600">Akun Anda dilindungi dengan autentikasi tambahan.</p>
                                </div>
                            </div>
                            <form action="{{ route('mfa.disable') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan 2FA? Keamanan akun Anda akan berkurang.');">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-red-600 border border-red-200 bg-white hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">
                                    Nonaktifkan 2FA
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center justify-between bg-cokelat-50 border border-cokelat-200 rounded-xl p-4">
                            <div>
                                <p class="text-sm font-semibold text-cokelat-800">2FA Belum Aktif</p>
                                <p class="text-xs text-cokelat-500">Aktifkan 2FA untuk melindungi akun koki atau admin Anda.</p>
                            </div>
                            <a href="{{ route('mfa.setup') }}" class="text-xs font-semibold text-white bg-cokelat-700 hover:bg-cokelat-800 px-3.5 py-1.5 rounded-lg transition-colors shadow-sm">
                                Setup 2FA
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Ganti Password --}}
            <div class="bg-white rounded-xl border border-cokelat-100 p-5 shadow-sm">
                <h3 class="font-serif text-base text-cokelat-800 mb-4">Ganti Password</h3>
                <p class="text-xs text-cokelat-400 mb-4">Kosongkan jika tidak ingin mengganti password.</p>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Password Baru
                        </label>
                        <input type="password" name="password"
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm outline-none focus:border-cokelat-500 transition-colors
                                   @error('password') border-red-400 @enderror"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-cokelat-600 uppercase tracking-wider block mb-1.5">
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation"
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg px-4 py-2.5
                                   text-sm outline-none focus:border-cokelat-500 transition-colors"
                            placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold px-6 py-2.5
                           rounded-xl text-sm transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ $backRoute }}"
                    class="text-sm text-cokelat-400 hover:text-cokelat-600 transition-colors">
                    Batal
                </a>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const placeholder = document.getElementById('foto-placeholder');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                    const img = document.getElementById('foto-preview');
                    if (img) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
