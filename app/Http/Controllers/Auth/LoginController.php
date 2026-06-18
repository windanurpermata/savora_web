<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    // Maksimal percobaan login sebelum dikunci
    const MAX_ATTEMPTS = 3;
    // Durasi kunci dalam detik (5 menit)
    const DECAY_SECONDS = 300;

    public function showLoginForm(Request $request)
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => ['required', new \App\Rules\Recaptcha()],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA wajib dicentang.',
        ]);

        // ===== CEK RATE LIMITER (lockout) =====
        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
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

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Email atau password salah. Sisa percobaan: {$remaining}x."
                ]);
        }

        // Cek jika akun diblokir
        if (Auth::user()->is_blocked) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun Anda telah diblokir. Silakan ke halaman <a href="/hubungi-kami" class="underline font-bold text-red-700 hover:text-red-900">Hubungi Kami</a> dan pilih pemulihan akun terblokir.'
                ]);
        }

        // ===== LOGIN BERHASIL =====
        RateLimiter::clear($throttleKey); // reset counter
        $request->session()->regenerate();

        // Cek verifikasi email
        if (!Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
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

    // ===== GOOGLE SOCIALITE =====

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Login Google gagal. Silakan coba lagi.']);
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (!$user) {
            // Buat akun baru
            $user = User::create([
                'name'              => $googleUser->name,
                'email'             => $googleUser->email,
                'google_id'         => $googleUser->id,
                'password'          => bcrypt(Str::random(24)),
                'role'              => 'member',
                'email_verified_at' => now(),
            ]);
        } else {
            // Update google_id jika belum ada
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->id]);
            }
        }

        // Cek jika akun diblokir
        if ($user->is_blocked) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda telah diblokir. Silakan ke halaman <a href="/hubungi-kami" class="underline font-bold text-red-700 hover:text-red-900">Hubungi Kami</a> dan pilih pemulihan akun terblokir.'
            ]);
        }

        Auth::login($user, true);

        return $this->redirectByRole();
    }

    // ===== HELPERS =====

    private function throttleKey(Request $request): string
    {
        // Kunci berdasarkan email + IP agar lebih akurat
        return Str::lower($request->input('email')) . '|' . $request->ip();
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