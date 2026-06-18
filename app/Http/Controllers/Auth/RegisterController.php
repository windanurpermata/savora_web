<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ChefProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'role' => 'required|in:member,chef',
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
            'g-recaptcha-response' => ['required', new \App\Rules\Recaptcha()],
        ], [
            'role.required' => 'Pilih jenis akun terlebih dahulu.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA wajib dicentang.',
        ]);

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Buat profil chef jika role chef
        if ($user->role === 'chef') {
            ChefProfile::create(['user_id' => $user->id]);
        }

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        // Login user secara otomatis
        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}