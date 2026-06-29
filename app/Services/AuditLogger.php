<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function log($activity, $userId = null)
    {
        try {
            AuditLog::create([
                'user_id' => $userId ?: Auth::id(),
                'activity' => $activity,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently ignore or log to Laravel file log if DB insert fails
            \Log::error("Failed to write audit log: " . $e->getMessage());
        }
    }
}
