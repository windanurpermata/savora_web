<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    /**
     * Daftar newsletter
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $existing = NewsletterSubscriber::where('email', $request->email)->first();

        // Sudah terdaftar dan aktif
        if ($existing && $existing->is_active) {
            return $this->jsonOrRedirect(
                $request,
                'info',
                'Email ini sudah terdaftar di newsletter kami.'
            );
        }

        // Pernah daftar tapi berhenti — aktifkan lagi
        if ($existing && !$existing->is_active) {
            $existing->update(['is_active' => true]);

            $this->sendWelcomeEmail($request->email);

            return $this->jsonOrRedirect(
                $request,
                'success',
                'Selamat datang kembali! Anda sudah aktif menerima newsletter.'
            );
        }

        // Daftar baru
        NewsletterSubscriber::create([
            'email' => $request->email,
            'is_active' => true,
            'unsubscribe_token' => Str::random(32),
        ]);

        $this->sendWelcomeEmail($request->email);

        return $this->jsonOrRedirect(
            $request,
            'success',
            'Berhasil! Anda akan menerima resep pilihan setiap minggu.'
        );
    }

    /**
     * Berhenti langganan via link di email
     */
    public function unsubscribe($token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')
                ->with('error', 'Link tidak valid atau sudah kadaluarsa.');
        }

        $subscriber->update(['is_active' => false]);

        return view('newsletter.unsubscribed');
    }

    // ===== HELPERS =====

    private function sendWelcomeEmail(string $email): void
    {
        try {
            Mail::raw(
                "Halo!\n\nTerima kasih sudah mendaftar newsletter Savora-DapurNusantara.\n\n" .
                "Setiap minggu Anda akan mendapatkan resep-resep pilihan dari chef terbaik kami.\n\n" .
                "Selamat memasak! 🍽\n\n— Tim Savora-DapurNusantara",
                function ($m) use ($email) {
                    $m->to($email)
                        ->subject('Selamat Datang di Newsletter Savora-DapurNusantara!');
                }
            );
        } catch (\Exception $e) {
            // Tetap lanjut meski email gagal
        }
    }

    private function jsonOrRedirect(Request $request, string $type, string $message)
    {
        // Kalau request dari fetch/axios (footer form) → return JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'type' => $type,
                'message' => $message,
            ]);
        }

        // Kalau dari form biasa → redirect back
        return back()->with($type, $message);
    }
}