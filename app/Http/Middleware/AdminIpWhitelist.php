<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminIpWhitelist
{
    private array $allowedIps = [
        '127.0.0.1', // localhost
        '::1',       // localhost IPv6
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->ip(), $this->allowedIps)) {
            abort(403, 'Akses ditolak. IP Anda tidak diizinkan.');
        }

        return $next($request);
    }
}