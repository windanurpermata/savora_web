<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'bio'      => 'nullable|string|max:500',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'name.max'           => 'Nama maksimal 100 karakter.',
            'foto.image'         => 'File harus berupa gambar.',
            'foto.max'           => 'Ukuran gambar maksimal 2MB.',
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Update nama
        $user->update(['name' => $request->name]);

        // Update atau buat profile (menggunakan tabel chef_profiles)
        $profileData = ['bio' => $request->bio];

        if ($request->hasFile('foto')) {
            if ($user->chefProfile?->foto) {
                \Storage::disk('public')->delete($user->chefProfile->foto);
            }
            $profileData['foto'] = $request->file('foto')->store('profil', 'public');
        }

        $user->chefProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
