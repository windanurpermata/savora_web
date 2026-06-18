<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);
        $pesanBelumDibaca = ContactMessage::where('is_read', false)->count();
        return view('admin.messages', compact('messages', 'pesanBelumDibaca'));
    }

    public function show($id)
    {
        $messages = ContactMessage::latest()->paginate(20);
        $pesanBelumDibaca = ContactMessage::where('is_read', false)->count();
        $selectedMessage = ContactMessage::findOrFail($id);

        // Tandai sudah dibaca
        if (!$selectedMessage->is_read) {
            $selectedMessage->update(['is_read' => true]);
        }

        return view('admin.messages', compact('messages', 'pesanBelumDibaca', 'selectedMessage'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string|min:5',
        ], [
            'balasan.required' => 'Balasan wajib diisi.',
            'balasan.min' => 'Balasan minimal 5 karakter.',
        ]);

        $msg = ContactMessage::findOrFail($id);

        $emailSent = true;
        try {
            Mail::raw(
                "Halo {$msg->nama},\n\n{$request->balasan}\n\n— Tim Savora",
                function ($m) use ($msg) {
                    $m->to($msg->email, $msg->nama)
                        ->subject('Re: Pesan Anda ke Savora');
                }
            );
        } catch (\Exception $e) {
            $emailSent = false;
            \Log::warning("Gagal mengirim email balasan ke {$msg->email}: " . $e->getMessage());
        }

        // Hapus pesan otomatis setelah dibalas
        $msg->delete();

        if ($emailSent) {
            return redirect()->route('admin.messages.index')
                ->with('success', 'Balasan berhasil dikirim ke ' . $msg->email . ' dan pesan telah dibersihkan.');
        }

        return redirect()->route('admin.messages.index')
            ->with('success', 'Balasan berhasil diproses (pesan dihapus), namun notifikasi email gagal dikirim ke ' . $msg->email . ' karena konfigurasi mail server belum aktif.');
    }

    public function destroy($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return redirect()->route('admin.messages.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}