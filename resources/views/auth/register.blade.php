@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
    <div class="min-h-screen bg-cokelat-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">

            <div class="flex flex-col items-center mb-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Savora Logo" class="w-16 h-16 object-cover rounded-xl shadow-sm border border-cokelat-100 mb-1">
                    <span class="font-serif text-2xl text-cokelat-800">Savora</span>
                </a>
                <p class="text-cokelat-400 text-xs mt-1">Buat akun baru</p>
            </div>

            <div class="bg-white rounded-xl border border-cokelat-100 shadow-sm p-6">

                @if ($errors->any())
                    <div
                        class="bg-red-50 border border-red-200 text-red-600 rounded-lg
                            px-3 py-2 mb-4 text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-3" id="registerForm">
                    @csrf

                    {{-- Pilih Role --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1.5">Daftar sebagai</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="member" class="peer hidden"
                                    {{ old('role', 'member') === 'member' ? 'checked' : '' }}>
                                <div
                                    class="border-2 border-cokelat-100 rounded-lg py-2.5 text-center
                                        peer-checked:border-cokelat-500 peer-checked:bg-cokelat-50
                                        hover:border-cokelat-300 transition-colors">
                                    <span class="text-lg">👤</span>
                                    <p class="text-xs font-bold text-cokelat-700 mt-0.5">Member</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="chef" class="peer hidden"
                                    {{ old('role') === 'chef' ? 'checked' : '' }}>
                                <div
                                    class="border-2 border-cokelat-100 rounded-lg py-2.5 text-center
                                        peer-checked:border-cokelat-500 peer-checked:bg-cokelat-50
                                        hover:border-cokelat-300 transition-colors">
                                    <span class="text-lg">👨‍🍳</span>
                                    <p class="text-xs font-bold text-cokelat-700 mt-0.5">Contributor</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap Anda"
                            required
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                  px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                  placeholder-cokelat-300 transition-colors
                                  @error('name') border-red-400 @enderror">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                            required
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                  px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                  placeholder-cokelat-300 transition-colors
                                  @error('email') border-red-400 @enderror">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="pwd" placeholder="Minimal 8 karakter" required
                                oninput="checkStrength(this.value)"
                                class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                      px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                      placeholder-cokelat-300 transition-colors pr-9
                                      @error('password') border-red-400 @enderror">
                            <button type="button" onclick="togglePwd('pwd')"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2
                                       text-cokelat-300 hover:text-cokelat-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542
                                             7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        {{-- Strength bar --}}
                        <div class="mt-1.5 space-y-1" id="strengthWrap" style="display:none">
                            <div class="flex gap-1">
                                <div class="h-1 flex-1 rounded-full bg-cokelat-100" id="bar1"></div>
                                <div class="h-1 flex-1 rounded-full bg-cokelat-100" id="bar2"></div>
                                <div class="h-1 flex-1 rounded-full bg-cokelat-100" id="bar3"></div>
                                <div class="h-1 flex-1 rounded-full bg-cokelat-100" id="bar4"></div>
                            </div>
                            <p class="text-xs" id="strengthLabel"></p>
                        </div>
                        {{-- Checklist --}}
                        <ul class="mt-1.5 space-y-0.5" id="pwdRules">
                            <li class="flex items-center gap-1.5 text-xs text-cokelat-300" id="rule-len">
                                <span class="rule-icon">○</span> Minimal 8 karakter
                            </li>
                            <li class="flex items-center gap-1.5 text-xs text-cokelat-300" id="rule-upper">
                                <span class="rule-icon">○</span> Huruf besar (A-Z)
                            </li>
                            <li class="flex items-center gap-1.5 text-xs text-cokelat-300" id="rule-number">
                                <span class="rule-icon">○</span> Angka (0-9)
                            </li>
                            <li class="flex items-center gap-1.5 text-xs text-cokelat-300" id="rule-symbol">
                                <span class="rule-icon">○</span> Simbol (!@#$%^&*)
                            </li>
                        </ul>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1">Konfirmasi Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="pwd2" placeholder="Ulangi password"
                                required oninput="checkConfirm(this.value)"
                                class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                      px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                      placeholder-cokelat-300 transition-colors pr-9">
                            <button type="button" onclick="togglePwd('pwd2')"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2
                                       text-cokelat-300 hover:text-cokelat-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542
                                             7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs mt-1 hidden text-red-500" id="confirmMsg">Password tidak cocok</p>
                        <p class="text-xs mt-1 hidden text-green-600" id="confirmOk">✓ Password cocok</p>
                    </div>

                    {{-- CAPTCHA --}}
                    <div class="flex flex-col items-center justify-center py-2">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        @error('g-recaptcha-response')
                            <p class="text-red-500 text-xs mt-1 text-center w-full">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold
                               py-2.5 rounded-lg text-sm transition-colors">
                        Buat Akun
                    </button>
                </form>

                <div class="my-4 flex items-center justify-between">
                    <span class="border-b border-cokelat-100 w-1/5 lg:w-1/4"></span>
                    <span class="text-xs text-center text-cokelat-400 uppercase">atau daftar dengan</span>
                    <span class="border-b border-cokelat-100 w-1/5 lg:w-1/4"></span>
                </div>

                <a href="{{ route('auth.google') }}"
                    class="w-full flex items-center justify-center gap-2 border border-cokelat-200 hover:bg-cokelat-50 text-cokelat-700 font-bold py-2.5 rounded-lg text-sm transition-colors mb-4">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                    </svg>
                    Google
                </a>

                {{-- Info verifikasi email --}}
                <div class="mt-4 bg-blue-50 border border-blue-100 rounded-lg px-3 py-2">
                    <p class="text-xs text-blue-600 text-center">
                        📧 Setelah daftar, cek email Anda untuk verifikasi akun sebelum bisa login
                    </p>
                </div>

                <p class="text-center text-xs text-cokelat-400 mt-4">
                    Sudah punya akun?
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

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        const rules = {
            len: v => v.length >= 8,
            upper: v => /[A-Z]/.test(v),
            number: v => /[0-9]/.test(v),
            symbol: v => /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(v),
        };

        function checkStrength(val) {
            document.getElementById('strengthWrap').style.display = val.length ? 'block' : 'none';
            let passed = 0;
            for (const [key, fn] of Object.entries(rules)) {
                const ok = fn(val);
                const li = document.getElementById('rule-' + key);
                li.querySelector('.rule-icon').textContent = ok ? '✓' : '○';
                li.classList.toggle('text-green-600', ok);
                li.classList.toggle('text-cokelat-300', !ok);
                if (ok) passed++;
            }
            const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
            const labels = [{
                    text: 'Sangat Lemah',
                    color: 'text-red-500'
                },
                {
                    text: 'Lemah',
                    color: 'text-orange-500'
                },
                {
                    text: 'Cukup',
                    color: 'text-yellow-600'
                },
                {
                    text: 'Kuat',
                    color: 'text-green-600'
                },
            ];
            [1, 2, 3, 4].forEach(i => {
                document.getElementById('bar' + i).className =
                    'h-1 flex-1 rounded-full ' + (i <= passed ? colors[passed - 1] : 'bg-cokelat-100');
            });
            const lbl = document.getElementById('strengthLabel');
            const info = labels[passed - 1] || labels[0];
            lbl.textContent = passed > 0 ? info.text : '';
            lbl.className = 'text-xs ' + (passed > 0 ? info.color : '');
            checkConfirm(document.getElementById('pwd2').value);
        }

        function checkConfirm(val) {
            const pwd = document.getElementById('pwd').value;
            const msg = document.getElementById('confirmMsg');
            const ok = document.getElementById('confirmOk');
            if (!val) {
                msg.classList.add('hidden');
                ok.classList.add('hidden');
                return;
            }
            msg.classList.toggle('hidden', pwd === val);
            ok.classList.toggle('hidden', pwd !== val);
        }

        function togglePwd(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endpush