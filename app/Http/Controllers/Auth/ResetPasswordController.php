<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        $email = $request->email;
        if ($email) {
            $email = str_replace(' ', '+', $email);
        }
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function reset(Request $request)
    {
        if ($request->has('email')) {
            $request->merge(['email' => str_replace(' ', '+', $request->input('email'))]);
        }

        \Log::info('Password reset attempt', [
            'email' => $request->input('email'),
            'token' => $request->input('token'),
            'token_length' => strlen($request->input('token')),
        ]);

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password' => 'Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, serta simbol.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        $message = match ($status) {
            Password::PASSWORD_RESET => 'Password berhasil direset!',
            Password::INVALID_USER => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.',
            Password::INVALID_TOKEN => 'Token reset password tidak valid atau sudah kedaluwarsa.',
            Password::RESET_THROTTLED => 'Mohon tunggu sebelum mencoba kembali.',
            default => 'Terjadi kesalahan saat mereset password.',
        };

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')
                ->with('success', 'Password berhasil direset! Silakan masuk dengan password baru.')
            : back()->withInput($request->only('email'))->withErrors(['email' => $message]);
    }
}