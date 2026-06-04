<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\User;

class ChefProfileController extends Controller
{
    public function show($id)
    {
        $chef = User::where('role', 'chef')->findOrFail($id);
        return view('chef.profile', compact('chef'));
    }

    public function edit()
    {
        return view('chef.profile-edit');
    }

    public function update(\Illuminate\Http\Request $request)
    {
        // update profil nanti
        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}