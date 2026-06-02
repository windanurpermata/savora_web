<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Resep populer — 8 resep dengan rating tertinggi
        $resepPopuler = Recipe::with(['user', 'kategori'])
            ->withAvg('ratings', 'nilai')
            ->orderByDesc('ratings_avg_nilai')
            ->take(8)
            ->get()
            ->map(function ($resep) {
                $resep->rating_avg = $resep->ratings_avg_nilai ?? 0;
                $resep->is_populer = true;
                // Cek apakah sudah di-bookmark oleh user yang login
                $resep->is_bookmarked = auth()->check()
                    ? $resep->bookmarks()->where('user_id', auth()->id())->exists()
                    : false;
                return $resep;
            });

        // Resep terbaru — 6 resep paling baru
        $resepTerbaru = Recipe::with(['user', 'kategori'])
            ->withAvg('ratings', 'nilai')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($resep) {
                $resep->rating_avg = $resep->ratings_avg_nilai ?? 0;
                return $resep;
            });

        // Chef terfeatured — chef dengan resep terbanyak
        $chefFeatured = User::where('role', 'chef')
            ->with('chefProfile')
            ->withCount('recipes')
            ->orderByDesc('recipes_count')
            ->take(8)
            ->get();

        // Semua kategori untuk dropdown & grid
        $kategoriList = Category::orderBy('nama')->get();

        // Statistik singkat untuk hero section
        $totalResep = Recipe::count();
        $totalChef = User::where('role', 'chef')->count();
        $totalMember = User::where('role', 'member')->count();
        $totalKategori = Category::count();

        return view('home', compact(
            'resepPopuler',
            'resepTerbaru',
            'chefFeatured',
            'kategoriList',
            'totalResep',
            'totalChef',
            'totalMember',
            'totalKategori',
        ));
    }
}