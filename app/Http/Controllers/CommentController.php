<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $recipeId)
    {
        $request->validate([
            'isi' => 'required|string|min:2|max:1000',
        ], [
            'isi.required' => 'Komentar tidak boleh kosong.',
            'isi.min'      => 'Komentar minimal 2 karakter.',
            'isi.max'      => 'Komentar maksimal 1000 karakter.',
        ]);

        $recipe = Recipe::findOrFail($recipeId);

        Comment::create([
            'user_id'   => Auth::id(),
            'recipe_id' => $recipe->id,
            'isi'       => $request->isi,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $user = Auth::user();

        // Hanya pemilik komentar atau admin yang bisa menghapus
        if ($comment->user_id !== $user->id && !in_array($user->role, ['admin'])) {
            return back()->with('error', 'Anda tidak berhak menghapus komentar ini.');
        }

        $comment->delete();
        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
