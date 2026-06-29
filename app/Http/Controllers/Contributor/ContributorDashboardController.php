<?php

namespace App\Http\Controllers\Contributor;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class ContributorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $reseps = Recipe::where('user_id', $user->id)->latest()->get();
        $totalResep = $reseps->count();

        $avgRating = Rating::whereHas('recipe', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->avg('nilai');

        $avgRating = round($avgRating ?? 0, 1);

        return view('contributor.dashboard', compact('reseps', 'totalResep', 'avgRating'));
    }
}