<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

// ============================================================
// FORGOT PASSWORD — Kirim link reset ke email
// ============================================================

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar di sistem kami.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        $message = match ($status) {
            Password::RESET_LINK_SENT => 'Link reset password berhasil dikirim ke email Anda!',
            Password::INVALID_USER => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.',
            Password::RESET_THROTTLED => 'Mohon tunggu sebelum meminta link kembali.',
            default => 'Terjadi kesalahan saat mengirim link reset password.',
        };

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', $message)
            : back()->withInput($request->only('email'))->withErrors(['email' => $message]);
    }
}