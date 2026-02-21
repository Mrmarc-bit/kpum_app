<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::orderBy('created_at', 'desc')->paginate(20);
        $blockedIps = \App\Models\BlockedIp::latest()->get();

        return view('admin.audit.index', [
            'logs' => $logs,
            'blockedIps' => $blockedIps,
            'title' => 'Audit Logs & Security'
        ]);
    }
    
    public function unblock($id)
    {
        $blocked = \App\Models\BlockedIp::findOrFail($id);
        $ip = $blocked->ip_address;
        $blocked->delete();

        // Log the unblock action
        AuditLog::create([
             'user_id' => auth()->id(),
             'user_name' => auth()->user()->name,
             'action' => 'IP UNBLOCKED',
             'details' => "Administrator membuka blokir IP: $ip",
             'ip_address' => request()->ip(),
             'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', "IP $ip berhasil dibuka blokirnya.");
    }

    public function print()
    {
        $logs = AuditLog::orderBy('created_at', 'desc')->get();

        return view('admin.audit.print', [
            'logs' => $logs,
            'title' => 'Laporan Audit Log (Full Admin View)'
        ]);
    }

    public function destroy()
    {
        AuditLog::truncate();

        return back()->with('success', 'Seluruh log aktivitas berhasil dihapus permanen.');
    }
}
