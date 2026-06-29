<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $requiredLevel): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = auth()->user();

        // Hanya role admin/superadmin yang bisa lewat middleware ini
        if (!$user->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Tentukan level admin:
        // admin biasa: hanya bisa kelola resep, newsletter, dan balas pesan.
        // super_admin: bisa akses semuanya termasuk user, kategori, dan log audit.
        
        $isSuperAdmin = $user->isSuperAdmin();

        if ($requiredLevel === 'super' && !$isSuperAdmin) {
            abort(403, 'Akses khusus Super Admin.');
        }

        return $next($request);
    }
}
