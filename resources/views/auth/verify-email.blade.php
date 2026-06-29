@extends('layouts.app')
@section('title', 'Verifikasi OTP')

@section('content')
    <div class="min-h-screen bg-cokelat-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md text-center">

            <div class="bg-white rounded-xl border border-cokelat-100 shadow-sm p-8">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="w-20 h-20 object-cover rounded-2xl shadow-sm border border-cokelat-100">
                </div>
                <h2 class="font-serif text-2xl text-cokelat-800 mb-2">Verifikasi OTP</h2>
                <p class="text-cokelat-500 text-sm mb-6 leading-relaxed">
                    Kami sudah mengirim kode verifikasi ke email
                    <strong class="text-cokelat-700 block mt-1">{{ Auth::user()->email }}</strong>
                    Silakan masukkan 6 digit kode OTP di bawah ini.
                </p>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-3 py-2.5 mb-4 text-xs font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-3 py-2.5 mb-4 text-xs font-semibold">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.verify.otp') }}" id="otp-form" class="mb-6">
                    @csrf
                    <input type="hidden" name="otp" id="otp-hidden">
                    
                    <div class="flex justify-center gap-2.5 mb-6">
                        <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-cokelat-200 rounded-lg focus:outline-none focus:border-cokelat-500 focus:ring-1 focus:ring-cokelat-500 bg-white text-cokelat-800 transition-all shadow-sm" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-cokelat-200 rounded-lg focus:outline-none focus:border-cokelat-500 focus:ring-1 focus:ring-cokelat-500 bg-white text-cokelat-800 transition-all shadow-sm" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-cokelat-200 rounded-lg focus:outline-none focus:border-cokelat-500 focus:ring-1 focus:ring-cokelat-500 bg-white text-cokelat-800 transition-all shadow-sm" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-cokelat-200 rounded-lg focus:outline-none focus:border-cokelat-500 focus:ring-1 focus:ring-cokelat-500 bg-white text-cokelat-800 transition-all shadow-sm" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-cokelat-200 rounded-lg focus:outline-none focus:border-cokelat-500 focus:ring-1 focus:ring-cokelat-500 bg-white text-cokelat-800 transition-all shadow-sm" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border border-cokelat-200 rounded-lg focus:outline-none focus:border-cokelat-500 focus:ring-1 focus:ring-cokelat-500 bg-white text-cokelat-800 transition-all shadow-sm" inputmode="numeric" pattern="[0-9]*">
                    </div>

                    <button type="submit"
                        class="w-full bg-cokelat-500 hover:bg-cokelat-400 text-white text-sm font-bold py-3 rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-cokelat-500 focus:ring-offset-2">
                        Verifikasi Akun
                    </button>
                </form>

                <div class="border-t border-cokelat-100 pt-6">
                    <form method="POST" action="{{ route('verification.resend') }}" id="resend-form" class="mb-4">
                        @csrf
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                        <button type="submit" id="resend-btn"
                            class="w-full border border-cokelat-300 hover:bg-cokelat-50 text-cokelat-600 text-sm font-bold py-2.5 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Kirim Ulang OTP
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-cokelat-400 hover:text-cokelat-600 transition-colors">
                            Keluar dari akun ini
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('otp-hidden');
        const form = document.getElementById('otp-form');

        // Autofocus input pertama saat halaman dimuat
        if (inputs.length > 0) {
            inputs[0].focus();
        }

        // Satukan nilai dari seluruh kotak input ke hidden input
        function updateHiddenInput() {
            let value = '';
            inputs.forEach(input => {
                value += input.value;
            });
            hiddenInput.value = value;
        }

        inputs.forEach((input, index) => {
            // Validasi input hanya menerima angka
            input.addEventListener('input', function(e) {
                const val = e.target.value;
                if (!/^\d*$/.test(val)) {
                    e.target.value = '';
                    updateHiddenInput();
                    return;
                }

                if (val.length > 0) {
                    e.target.value = val.substring(0, 1);
                    // Pindah ke input berikutnya jika ada
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                }
                updateHiddenInput();
            });

            // Tangani penekanan tombol Backspace
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace') {
                    if (input.value === '') {
                        // Jika input kosong, pindah dan hapus input sebelumnya
                        if (index > 0) {
                            inputs[index - 1].focus();
                            inputs[index - 1].value = '';
                        }
                    } else {
                        input.value = '';
                    }
                    updateHiddenInput();
                    e.preventDefault();
                }
            });

            // Tangani event paste (tempel)
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                if (/^\d{6}$/.test(pasteData)) {
                    for (let i = 0; i < inputs.length; i++) {
                        inputs[i].value = pasteData[i];
                    }
                    updateHiddenInput();
                    inputs[inputs.length - 1].focus();
                }
            });
        });

        // Cegah submit jika OTP belum lengkap 6 digit
        form.addEventListener('submit', function(e) {
            updateHiddenInput();
            if (hiddenInput.value.length !== 6 || !/^\d{6}$/.test(hiddenInput.value)) {
                e.preventDefault();
                alert('Silakan masukkan 6 digit kode OTP dengan lengkap.');
            }
        });

        // --- TIMER COUNTDOWN RESEND BUTTON ---
        const resendBtn = document.getElementById('resend-btn');
        let countdown = parseInt(localStorage.getItem('otp_countdown')) || 0;
        let interval;

        function startTimer(duration) {
            countdown = duration;
            localStorage.setItem('otp_countdown', countdown);
            resendBtn.disabled = true;
            resendBtn.innerText = `Kirim Ulang OTP (${countdown}s)`;
            
            clearInterval(interval);
            interval = setInterval(() => {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(interval);
                    localStorage.removeItem('otp_countdown');
                    resendBtn.disabled = false;
                    resendBtn.innerText = 'Kirim Ulang OTP';
                } else {
                    localStorage.setItem('otp_countdown', countdown);
                    resendBtn.disabled = true;
                    resendBtn.innerText = `Kirim Ulang OTP (${countdown}s)`;
                }
            }, 1000);
        }

        // Jalankan timer jika countdown masih aktif dari kunjungan sebelumnya
        if (countdown > 0) {
            startTimer(countdown);
        }

        document.getElementById('resend-form').addEventListener('submit', function() {
            // Set timer ke 60 detik setelah melakukan request kirim ulang
            startTimer(60);
        });
    });
</script>
@endpush
