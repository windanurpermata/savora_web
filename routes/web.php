<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\Chef\ChefDashboardController;
use App\Http\Controllers\Chef\ChefProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\CommentController;

// ===== PUBLIC =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/resep', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/resep/{id}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/chef/{id}', [ChefProfileController::class, 'show'])->name('chef.profile')->whereNumber('id');

// ===== GOOGLE LOGIN =====
Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ===== AUTH =====
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/masuk', [LoginController::class, 'login']);
    Route::get('/daftar', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'register']);
    Route::get('/lupa-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/lupa-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ===== KOMENTAR RESEP =====
Route::post('/resep/{id}/komentar', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');
Route::delete('/komentar/{id}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware('auth');

Route::post('/keluar', [LoginController::class, 'logout'])
    ->middleware('auth')->name('logout');

// ===== VERIFIKASI EMAIL =====
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verify', function (Request $request) {
    $request->validate([
        'otp' => 'required|string|size:6',
    ], [
        'otp.required' => 'Kode OTP wajib diisi.',
        'otp.size' => 'Kode OTP harus berupa 6 digit angka.',
    ]);

    $user = $request->user();

    if ($user->hasVerifiedEmail()) {
        return redirect()->route('home');
    }

    if ($user->otp_code !== $request->otp) {
        return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.']);
    }

    if (now()->greaterThan($user->otp_expires_at)) {
        return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan kirim ulang kode baru.']);
    }

    $user->markEmailAsVerified();
    
    $user->forceFill([
        'otp_code' => null,
        'otp_expires_at' => null,
    ])->save();

    $redirectRoute = match ($user->role) {
        'admin' => 'admin.dashboard',
        'chef' => 'chef.dashboard',
        default => 'home',
    };

    return redirect()->route($redirectRoute)->with('success', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.verify.otp');

Route::post('/email/verification-notification', [LoginController::class, 'resendVerification'])
    ->middleware('throttle:6,1')->name('verification.resend');

// ===== USER PROFILE =====
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profil/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// ===== MEMBER =====
Route::middleware(['auth', 'role:member', 'verified'])->group(function () {
    Route::get('/bookmark', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/resep/{id}/bookmark', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::post('/resep/{id}/rating', [RatingController::class, 'store'])->name('recipes.rate');
});

// ===== CHEF =====
Route::middleware(['auth', 'role:chef', 'verified'])
    ->prefix('chef')->name('chef.')->group(function () {
        Route::get('/dashboard', [ChefDashboardController::class, 'index'])->name('dashboard');
        Route::get('/resep/buat', [RecipeController::class, 'create'])->name('recipes.create');
        Route::post('/resep', [RecipeController::class, 'store'])->name('recipes.store');
        Route::get('/resep/{id}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
        Route::put('/resep/{id}', [RecipeController::class, 'update'])->name('recipes.update');
        Route::delete('/resep/{id}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
        Route::get('/profil/edit', [ChefProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profil', [ChefProfileController::class, 'update'])->name('profile.update');
    });

// ===== ADMIN =====
Route::middleware(['auth', 'role:admin', 'admin.ip'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('recipes', \App\Http\Controllers\Admin\AdminRecipeController::class);

        // Super Admin Only
        Route::middleware(['admin.role:super'])->group(function () {
            Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);
            Route::post('users/{id}/toggle-block', [\App\Http\Controllers\Admin\AdminUserController::class, 'toggleBlock'])->name('users.toggle-block');
            Route::resource('categories', \App\Http\Controllers\Admin\AdminCategoryController::class);
        });

        // Messages
        Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{id}', [AdminMessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{id}/reply', [AdminMessageController::class, 'reply'])->name('messages.reply');
        Route::delete('/messages/{id}/reply', [AdminMessageController::class, 'destroyReply'])->name('messages.reply.destroy');
        Route::delete('/messages/{id}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

        // Newsletter
        Route::get('/newsletter', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'index'])->name('newsletter.index');
        Route::delete('/newsletter/{id}', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'destroy'])->name('newsletter.destroy');
    });

// ===== FAQ =====
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// ===== HUBUNGI KAMI =====
Route::get('/hubungi-kami', [ContactController::class, 'index'])->name('contact');
Route::post('/hubungi-kami', [ContactController::class, 'store'])->name('contact.store');

// ===== NEWSLETTER =====
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');