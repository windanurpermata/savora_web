<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChefProfileController extends Controller
{
    public function show($id)
    {
        $chef = User::where('role', 'chef')->findOrFail($id);
        $reseps = Recipe::where('user_id', $chef->id)->latest()->get();
        return view('chef.profile', compact('chef', 'reseps'));
    }

    public function edit()
    {
        return view('chef.profile-edit');
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

        // Update atau buat chef profile
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