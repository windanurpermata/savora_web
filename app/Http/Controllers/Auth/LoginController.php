<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // Maksimal percobaan login sebelum dikunci
    const MAX_ATTEMPTS = 3;
    // Durasi kunci dalam detik (15 menit)
    const DECAY_SECONDS = 900;

    public function showLoginForm(Request $request)
    {
        // Generate CAPTCHA baru setiap kali halaman login dibuka
        $this->generateCaptcha($request);
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'captcha.required' => 'Jawaban captcha wajib diisi.',
        ]);

        // ===== CEK CAPTCHA =====
        if ((int) $request->captcha !== (int) session('captcha_answer')) {
            $this->generateCaptcha($request); // generate captcha baru
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['captcha' => 'Jawaban captcha salah.']);
        }

        // ===== CEK RATE LIMITER (lockout) =====
        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            $this->generateCaptcha($request);
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$minutes} menit."
                ]);
        }

        // ===== COBA LOGIN =====
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

            $remaining = self::MAX_ATTEMPTS - RateLimiter::attempts($throttleKey);
            $this->generateCaptcha($request);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Email atau password salah. Sisa percobaan: {$remaining}x."
                ]);
        }

        // ===== LOGIN BERHASIL =====
        RateLimiter::clear($throttleKey); // reset counter
        $request->session()->regenerate();

        // Cek verifikasi email
        if (!Auth::user()->hasVerifiedEmail()) {
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email belum diverifikasi. Cek inbox email Anda.'
                ]);
        }

        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda berhasil keluar.');
    }

    // ===== RESEND VERIFIKASI EMAIL =====
    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Email sudah terverifikasi.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi sudah dikirim ulang ke email Anda.');
    }

    // ===== HELPERS =====

    private function throttleKey(Request $request): string
    {
        // Kunci berdasarkan email + IP agar lebih akurat
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    private function generateCaptcha(Request $request): void
    {
        $a = rand(1, 20);
        $b = rand(1, 20);
        $request->session()->put('captcha_question', "{$a} + {$b}");
        $request->session()->put('captcha_answer', $a + $b);
    }

    private function redirectByRole()
    {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'chef' => redirect()->route('chef.dashboard'),
            default => redirect()->route('home'),
        };
    }
}