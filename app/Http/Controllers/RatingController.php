<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate(['nilai' => 'required|integer|min:1|max:5']);

        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'recipe_id' => $id],
            ['nilai' => $request->nilai]
        );

        return back()->with('success', 'Rating berhasil disimpan!');
    }
}