<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Google2FA;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MfaController extends Controller
{
    public function showVerifyForm()
    {
        // If MFA is not enabled or already verified, redirect away
        if (!Auth::check() || !Auth::user()->mfa_enabled || session('mfa_verified')) {
            return redirect()->route('home');
        }

        return view('auth.mfa');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ], [
            'code.required' => 'Kode TOTP wajib diisi.',
            'code.size' => 'Kode harus berupa 6 digit angka.',
        ]);

        $user = Auth::user();

        if (Google2FA::verifyKey($user->mfa_secret, $request->code)) {
            session(['mfa_verified' => true]);
            
            AuditLogger::log("MFA Login Verified Successfully", $user->id);

            $redirectRoute = match ($user->role) {
                'admin', 'superadmin' => 'admin.dashboard',
                'contributor' => 'contributor.dashboard',
                default => 'home',
            };

            return redirect()->route($redirectRoute)->with('success', 'Autentikasi dua faktor berhasil!');
        }

        AuditLogger::log("MFA Verification Failed (Invalid Code Attempt)", $user->id);

        return back()->withErrors(['code' => 'Kode autentikasi salah atau sudah kedaluwarsa.']);
    }

    public function setup()
    {
        $user = Auth::user();

        // Only Contributor and Admin can use MFA
        if (!$user->isAdmin() && !$user->isContributor()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        // If not already generating, generate a new secret
        $secret = session('mfa_setup_secret') ?: Google2FA::generateSecretKey();
        session(['mfa_setup_secret' => $secret]);

        $qrCodeUrl = Google2FA::getQRCodeUrl($user->email, $secret, 'Savora');

        return view('profile.mfa-setup', compact('secret', 'qrCodeUrl'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        $secret = session('mfa_setup_secret');

        if (!$secret) {
            return redirect()->route('profile.edit')->with('error', 'Sesi setup kedaluwarsa.');
        }

        if (Google2FA::verifyKey($secret, $request->code)) {
            $user->update([
                'mfa_secret' => $secret,
                'mfa_enabled' => true,
            ]);

            session()->forget('mfa_setup_secret');
            session(['mfa_verified' => true]);

            AuditLogger::log("MFA Enabled Successfully");

            return redirect()->route('profile.edit')->with('success', 'Autentikasi Dua Faktor (2FA) berhasil diaktifkan!');
        }

        return back()->withErrors(['code' => 'Kode verifikasi salah. Silakan coba lagi.']);
    }

    public function disable(Request $request)
    {
        $user = Auth::user();
        
        $user->update([
            'mfa_secret' => null,
            'mfa_enabled' => false,
        ]);

        session()->forget('mfa_verified');

        AuditLogger::log("MFA Disabled");

        return redirect()->route('profile.edit')->with('success', 'Autentikasi Dua Faktor (2FA) berhasil dinonaktifkan.');
    }
}
