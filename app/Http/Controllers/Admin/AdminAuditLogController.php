<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminAuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $search = '%' . $request->q . '%';
            $query->where(function ($sub) use ($search) {
                $sub->where('activity', 'like', $search)
                    ->orWhere('ip_address', 'like', $search)
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', $search)
                          ->orWhere('email', 'like', $search);
                    });
            });
        }

        $logs = $query->paginate(20);

        return view('admin.audit-logs.index', compact('logs'));
    }
}
