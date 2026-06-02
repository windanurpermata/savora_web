<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqGroups = [
            [
                'emoji' => '👤',
                'title' => 'Akun & Pendaftaran',
                'items' => [
                    [
                        'q' => 'Apakah DapurNusantara gratis digunakan?',
                        'a' => 'Ya, DapurNusantara sepenuhnya gratis untuk dilihat dan digunakan oleh siapa saja. Untuk fitur seperti menyimpan bookmark, Anda perlu mendaftar sebagai member — juga gratis.',
                    ],
                    [
                        'q' => 'Bagaimana cara mendaftar sebagai Chef?',
                        'a' => 'Klik tombol "Daftar" di navbar, pilih role "Chef", lalu isi data diri Anda. Setelah verifikasi email, Anda langsung bisa membuat dan mengelola resep dari dashboard chef.',
                    ],
                    [
                        'q' => 'Apa perbedaan Member dan Chef?',
                        'a' => 'Member bisa menyimpan bookmark dan memberi rating resep. Chef bisa melakukan semua itu, plus membuat, mengedit, dan mengelola resep mereka sendiri di halaman profil chef.',
                    ],
                    [
                        'q' => 'Lupa password, bagaimana cara resetnya?',
                        'a' => 'Di halaman login, klik "Lupa password?". Masukkan email terdaftar Anda, dan kami akan mengirimkan link reset password ke inbox email Anda dalam beberapa menit.',
                    ],
                ],
            ],
            [
                'emoji' => '🍽',
                'title' => 'Resep & Konten',
                'items' => [
                    [
                        'q' => 'Bagaimana cara menyimpan resep favorit?',
                        'a' => 'Login sebagai member, lalu klik ikon bookmark di pojok kanan atas kartu resep atau di halaman detail resep. Semua resep tersimpan bisa dilihat di halaman "Bookmark Saya".',
                    ],
                    [
                        'q' => 'Apakah bisa memberi rating pada resep?',
                        'a' => 'Ya, member yang sudah login bisa memberikan rating bintang (1–5) pada halaman detail resep. Rating akan mempengaruhi urutan resep di halaman "Terpopuler".',
                    ],
                    [
                        'q' => 'Apakah resep yang diunggah bisa diedit?',
                        'a' => 'Ya, chef bisa mengedit dan menghapus resep kapan saja melalui dashboard chef. Admin juga memiliki akses untuk mengelola semua resep di platform.',
                    ],
                    [
                        'q' => 'Format gambar apa yang didukung untuk resep?',
                        'a' => 'Kami mendukung format JPG, JPEG, PNG, dan WebP dengan ukuran maksimal 2MB per gambar. Pastikan gambar memiliki resolusi yang cukup agar terlihat jelas di semua perangkat.',
                    ],
                ],
            ],
            [
                'emoji' => '🔒',
                'title' => 'Keamanan & Privasi',
                'items' => [
                    [
                        'q' => 'Apakah data pribadi saya aman?',
                        'a' => 'Ya, kami menjaga keamanan data Anda dengan enkripsi password, verifikasi email, dan pembatasan akses berdasarkan role. Data Anda tidak akan dibagikan kepada pihak ketiga.',
                    ],
                    [
                        'q' => 'Mengapa akun saya terkunci saat login?',
                        'a' => 'Akun akan dikunci sementara selama 15 menit setelah 5 kali percobaan login yang gagal. Ini adalah fitur keamanan untuk melindungi akun Anda dari akses tidak sah.',
                    ],
                ],
            ],
        ];

        return view('faq.index', compact('faqGroups'));
    }
}