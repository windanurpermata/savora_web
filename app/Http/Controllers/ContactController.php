<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email',
            'topik' => 'required|string',
            'pesan' => 'required|string|min:10|max:1000',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'topik.required' => 'Pilih topik terlebih dahulu.',
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.min' => 'Pesan minimal 10 karakter.',
            'pesan.max' => 'Pesan maksimal 1000 karakter.',
        ]);

        // Simpan ke database
        ContactMessage::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'topik' => $request->topik,
            'pesan' => $request->pesan,
            'user_id' => auth()->id(),
            'is_read' => false,
        ]);

        // Kirim notifikasi email ke admin
        try {
            Mail::raw(
                "Pesan baru dari: {$request->nama} ({$request->email})\n" .
                "Topik: {$request->topik}\n\n" .
                "Pesan:\n{$request->pesan}",
                function ($m) use ($request) {
                    $m->to(config('mail.from.address'))
                        ->subject("[DapurNusantara] Pesan Baru: {$request->topik}");
                }
            );
        } catch (\Exception $e) {
            // Tetap lanjut meski email gagal terkirim
        }

        // Kirim email terima kasih ke pengirim
        try {
            Mail::raw(
                "Halo {$request->nama},\n\n" .
                "Terima kasih telah menghubungi Savora. Kami telah menerima pesan Anda dengan topik \"" . ucwords(str_replace('_', ' ', $request->topik)) . "\".\n" .
                "Tim kami akan meninjau pesan Anda dan segera memberikan tanggapan dalam waktu 1x24 jam kerja.\n\n" .
                "Salam hangat,\n" .
                "Tim Savora-DapurNusantara",
                function ($m) use ($request) {
                    $m->to($request->email)
                        ->subject("[Savora] Terima Kasih Telah Menghubungi Kami");
                }
            );
        } catch (\Exception $e) {
            // Tetap lanjut
        }

        return redirect()->route('contact')
            ->with('success', 'Pesan Anda sudah terkirim! Kami akan membalas dalam 1x24 jam.');
    }
}