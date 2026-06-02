<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminRecipeController extends Controller
{
    public function index(Request $request)
    {
        $query = Recipe::with(['user', 'kategori'])
            ->withAvg('ratings', 'nilai')
            ->withCount('ratings');

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }

        $recipes = $query->latest()->paginate(15);
        $kategoriList = Category::orderBy('nama')->get();

        return view('admin.recipes', compact('recipes', 'kategoriList'));
    }

    public function destroy($id)
    {
        $resep = Recipe::findOrFail($id);

        if ($resep->gambar) {
            \Storage::disk('public')->delete($resep->gambar);
        }

        $resep->delete();
        return back()->with('success', 'Resep berhasil dihapus.');
    }
}