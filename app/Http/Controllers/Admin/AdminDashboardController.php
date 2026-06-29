<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalResep' => Recipe::count(),
            'resepBulanIni' => Recipe::whereMonth('created_at', now()->month)->count(),
            'totalUser' => User::whereIn('role', ['member', 'contributor'])->count(),
            'totalContributor' => User::where('role', 'contributor')->count(),
            'totalMember' => User::where('role', 'member')->count(),
            'totalPesan' => ContactMessage::count(),
            'pesanBelumDibaca' => ContactMessage::where('is_read', false)->count(),
            'totalSubscriber' => NewsletterSubscriber::where('is_active', true)->count(),
            'resepTerbaru' => Recipe::with(['user', 'kategori'])->latest()->take(6)->get(),
            'pesanTerbaru' => ContactMessage::latest()->take(5)->get(),
        ]);
    }
}