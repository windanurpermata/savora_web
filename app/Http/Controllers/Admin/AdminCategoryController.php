<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('recipes')->orderBy('nama')->get();
        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:categories,nama',
            'emoji' => 'nullable|string|max:5',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori sudah ada.',
        ]);

        Category::create($request->only('nama', 'emoji'));
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kat = Category::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:50|unique:categories,nama,' . $id,
            'emoji' => 'nullable|string|max:5',
        ]);

        $kat->update($request->only('nama', 'emoji'));
        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kat = Category::withCount('recipes')->findOrFail($id);

        if ($kat->recipes_count > 0) {
            return back()->with('error', 'Tidak bisa menghapus kategori yang masih memiliki resep.');
        }

        $kat->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}