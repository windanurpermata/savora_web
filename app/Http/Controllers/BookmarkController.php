<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Daftar resep yang di-bookmark oleh member
     */
    public function index()
    {
        $bookmarks = Bookmark::with(['recipe.user', 'recipe.kategori'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('bookmarks.index', compact('bookmarks'));
    }

    /**
     * Toggle bookmark — tambah atau hapus
     */
    public function toggle($id)
    {
        $recipe = Recipe::findOrFail($id);

        $existing = Bookmark::where('user_id', Auth::id())
            ->where('recipe_id', $id)
            ->first();

        if ($existing) {
            $existing->delete();
            $bookmarked = false;
        } else {
            Bookmark::create([
                'user_id' => Auth::id(),
                'recipe_id' => $id,
            ]);
            $bookmarked = true;
        }

        return response()->json(['bookmarked' => $bookmarked]);
    }
}