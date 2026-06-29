<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    // ===================== PUBLIC =====================

    /**
     * Daftar semua resep — dengan filter & sort
     */
    public function index(Request $request)
    {
        $query = Recipe::with(['user', 'kategori'])
            ->withAvg('ratings', 'nilai');

        // Filter: pencarian judul
        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        // Filter: kategori
        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }

        // Sort
        $sort = $request->input('sort', 'terbaru');
        if ($sort === 'populer') {
            $query->orderByDesc('ratings_avg_nilai');
        } else {
            $query->latest();
        }

        $recipes = $query->paginate(12);

        // Tambahkan rating_avg & is_bookmarked ke tiap resep
        $recipes->getCollection()->transform(function ($resep) {
            $resep->rating_avg = $resep->ratings_avg_nilai ?? 0;
            $resep->is_bookmarked = Auth::check()
                ? $resep->bookmarks()->where('user_id', Auth::id())->exists()
                : false;
            return $resep;
        });

        $kategoriList = Category::orderBy('nama')->get();
        $kategoriAktif = $request->filled('kategori')
            ? Category::find($request->kategori)
            : null;

        return view('recipes.index', compact(
            'recipes',
            'kategoriList',
            'kategoriAktif',
        ));
    }

    /**
     * Detail resep
     */
    public function show($id)
    {
        $resep = Recipe::with(['user', 'kategori', 'ratings.user', 'comments.user'])
            ->withAvg('ratings', 'nilai')
            ->findOrFail($id);

        $resep->rating_avg = $resep->ratings_avg_nilai ?? 0;
        $resep->is_bookmarked = Auth::check()
            ? $resep->bookmarks()->where('user_id', Auth::id())->exists()
            : false;

        // Resep lain dari chef yang sama
        $resepLain = Recipe::with('kategori')
            ->where('user_id', $resep->user_id)
            ->where('id', '!=', $resep->id)
            ->latest()
            ->take(4)
            ->get();

        return view('recipes.show', compact('resep', 'resepLain'));
    }

    // ===================== CHEF =====================

    /**
     * Form buat resep baru
     */
    public function create()
    {
        $kategoriList = Category::orderBy('nama')->get();
        return view('recipes.create', compact('kategoriList'));
    }

    /**
     * Simpan resep baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'category_id' => 'nullable|exists:categories,id',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'waktu_memasak' => 'required|integer|min:1',
            'porsi' => 'required|integer|min:1',
        ], [
            'judul.required' => 'Judul resep wajib diisi.',
            'judul.max' => 'Judul maksimal 150 karakter.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'waktu_memasak.required' => 'Waktu memasak wajib diisi.',
            'waktu_memasak.min' => 'Waktu memasak minimal 1 menit.',
            'porsi.required' => 'Jumlah porsi wajib diisi.',
            'porsi.min' => 'Jumlah porsi minimal 1.',
        ]);

        $data = $request->only([
            'judul',
            'category_id',
            'deskripsi',
            'waktu_memasak',
            'porsi'
        ]);
        $data['user_id'] = Auth::id();

        // Upload gambar
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('resep', 'public');
        }

        Recipe::create($data);

        return redirect()->route('contributor.dashboard')
            ->with('success', 'Resep berhasil dibuat!');
    }

    /**
     * Form edit resep
     */
    public function edit($id)
    {
        $resep = Recipe::where('user_id', Auth::id())->findOrFail($id);
        $kategoriList = Category::orderBy('nama')->get();
        return view('recipes.edit', compact('resep', 'kategoriList'));
    }

    /**
     * Update resep
     */
    public function update(Request $request, $id)
    {
        $resep = Recipe::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:150',
            'category_id' => 'nullable|exists:categories,id',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'waktu_memasak' => 'required|integer|min:1',
            'porsi' => 'required|integer|min:1',
        ]);

        $data = $request->only([
            'judul',
            'category_id',
            'deskripsi',
            'waktu_memasak',
            'porsi'
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($resep->gambar) {
                \Storage::disk('public')->delete($resep->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('resep', 'public');
        }

        $resep->update($data);

        return redirect()->route('contributor.dashboard')
            ->with('success', 'Resep berhasil diperbarui!');
    }

    /**
     * Hapus resep
     */
    public function destroy($id)
    {
        $resep = Recipe::where('user_id', Auth::id())->findOrFail($id);

        if ($resep->gambar) {
            \Storage::disk('public')->delete($resep->gambar);
        }

        $resep->delete();

        return redirect()->route('contributor.dashboard')
            ->with('success', 'Resep berhasil dihapus.');
    }
}