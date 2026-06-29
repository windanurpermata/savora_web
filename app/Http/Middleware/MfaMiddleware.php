<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MfaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Only enforce MFA for roles that can have it enabled
            if ($user->mfa_enabled && !session('mfa_verified')) {
                // Allow access to: MFA routes, logout, auth routes, email verification
                $allowedRoutes = [
                    'mfa.verify',
                    'mfa.verify.post',
                    'logout',
                    'login',
                    'register',
                    'verification.notice',
                    'verification.verify.otp',
                    'verification.resend',
                    'password.request',
                    'password.email',
                    'password.reset',
                    'password.update',
                ];

                if (!$request->routeIs(...$allowedRoutes) && !$request->expectsJson()) {
                    return redirect()->route('mfa.verify');
                }
            }
        }

        return $next($request);
    }
}
