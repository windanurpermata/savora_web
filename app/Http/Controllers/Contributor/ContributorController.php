<?php

namespace App\Http\Controllers\Contributor;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContributorController extends Controller
{
    public function index()
    {
        $chef = Auth::user();

        // Statistik utama
        $totalResep = Recipe::where('user_id', $chef->id)->count();
        $totalRating = Rating::whereHas('recipe', fn($q) => $q->where('user_id', $chef->id))->count();
        $rataRating = Rating::whereHas('recipe', fn($q) => $q->where('user_id', $chef->id))->avg('nilai');
        $resepTerpopuler = Recipe::where('user_id', $chef->id)
            ->withCount('ratings')
            ->orderByDesc('ratings_count')
            ->first();

        // Resep terbaru
        $resepTerbaru = Recipe::where('user_id', $chef->id)
            ->with('category')
            ->withAvg('ratings', 'nilai')
            ->latest()
            ->take(5)
            ->get();

        // Resep rating tertinggi
        $resepTerbaik = Recipe::where('user_id', $chef->id)
            ->withAvg('ratings', 'nilai')
            ->withCount('ratings')
            ->having('ratings_avg_nilai', '>', 0)
            ->orderByDesc('ratings_avg_nilai')
            ->take(5)
            ->get();

        // Data grafik rating per bulan (6 bulan terakhir)
        $grafikRating = Rating::whereHas('recipe', fn($q) => $q->where('user_id', $chef->id))
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('AVG(nilai) as rata'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        return view('contributor.dashboard', compact(
            'chef',
            'totalResep',
            'totalRating',
            'rataRating',
            'resepTerpopuler',
            'resepTerbaru',
            'resepTerbaik',
            'grafikRating'
        ));
    }
}