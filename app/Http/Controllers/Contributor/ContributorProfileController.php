<?php

namespace App\Http\Controllers\Contributor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ContributorProfileController extends Controller
{
    public function show($id)
    {
        $contributor = User::whereIn('role', ['contributor', 'chef'])->findOrFail($id);
        $reseps = Recipe::where('user_id', $contributor->id)->latest()->get();
        return view('contributor.profile', compact('contributor', 'reseps'));
    }

    public function edit()
    {
        return view('contributor.profile-edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'bio'      => 'nullable|string|max:500',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Update nama
        $user->update(['name' => $request->name]);

        // Update atau buat contributor profile
        $profileData = ['bio' => $request->bio];

        if ($request->hasFile('foto')) {
            if ($user->contributorProfile?->foto) {
                \Storage::disk('public')->delete($user->contributorProfile->foto);
            }
            $profileData['foto'] = $request->file('foto')->store('profil', 'public');
        }

        $user->contributorProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}