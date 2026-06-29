@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
    <div class="min-h-screen bg-cokelat-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">

            <div class="flex flex-col items-center mb-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Savora Logo" class="w-16 h-16 object-cover rounded-xl shadow-sm border border-cokelat-100 mb-1">
                    <span class="font-serif text-2xl text-cokelat-800">Savora</span>
                </a>
                <p class="text-cokelat-400 text-xs mt-1">Buat password baru</p>
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

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf

                    {{-- Token dari link email --}}
                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ $email ?? old('email') }}"
                            placeholder="contoh@email.com" required
                            class="w-full bg-cokelat-50 border border-cokelat-200 rounded-lg
                                  px-3 py-2 text-sm outline-none focus:border-cokelat-500
                                  placeholder-cokelat-300 transition-colors
                                  @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1">Password Baru</label>
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
                        <ul class="mt-1.5 space-y-0.5">
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

                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-xs font-bold text-cokelat-700 mb-1">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="pwd2"
                                placeholder="Ulangi password baru" required oninput="checkConfirm(this.value)"
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

                    <button type="submit"
                        class="w-full bg-cokelat-700 hover:bg-cokelat-800 text-white font-bold
                               py-2.5 rounded-lg text-sm transition-colors">
                        Reset Password
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
