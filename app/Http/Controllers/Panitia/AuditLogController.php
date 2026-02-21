<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        // STRICT FILTERING: 
        // 1. Exclude any action done by a User with role 'admin'
        // 2. Include actions by 'panitia'
        // 3. Include actions by 'mahasiswa' (if using User model, checks role)
        // 4. Include Guest/System actions (user_id null) BUT careful not to leak admin login attempts if user_id is null but ip matches admin... 
        //    (For simplicity, we assume 'admin' actions that matter have a user_id attached or distinctive context)

        // Get IDs of all admins
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');

        $logs = AuditLog::query()
            ->where(function($query) use ($adminIds) {
                // Scenario A: Action by Authenticated User (NOT Admin)
                $query->whereNotIn('user_id', $adminIds)
                      ->whereNotNull('user_id'); 
            })
            ->orWhere(function($query) {
                // Scenario B: Action by Guest / System / Mahasiswa (if listed separately in a diff table, but AuditLog typically links to User)
                // If Mahasiswa are in a separate table, they might have user_id NULL in this table if it references `users` table via foreign key?
                // OR if AuditLog is polymorphic (user_type + user_id).
                // Assuming standard 'user_id' refers to 'users' table or is nullable for guests.
               
                // We want to see guest actions (like Login Attempts by panitia/mahasiswa)
                // But we must assume we can't easily distinguish a guest "Admin Login Attempt" from a "Panitia Login Attempt" 
                // unless we parse the details. For now, generally show guest logs as they are likely safe or relevant (student voting).
                $query->whereNull('user_id');
            })
            ->latest()
            ->paginate(20);

        return view('panitia.audit.index', [
            'title' => 'Audit Logs - Aktivitas Panitia & Mahasiswa',
            'logs' => $logs
        ]);
    }

    public function print()
    {
        // Get IDs of all admins
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');

        $logs = AuditLog::query()
            ->where(function($query) use ($adminIds) {
                $query->whereNotIn('user_id', $adminIds)
                      ->whereNotNull('user_id'); 
            })
            ->orWhere(function($query) {
                $query->whereNull('user_id');
            })
            ->latest()
            ->get();

        return view('panitia.audit.print', [
            'logs' => $logs,
            'title' => 'Laporan Audit Log (Panitia View)'
        ]);
    }

    public function destroy()
    {
        // Panitia can only delete logs that aren't from Admins
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');

        AuditLog::query()
            ->where(function($query) use ($adminIds) {
                $query->whereNotIn('user_id', $adminIds)
                      ->whereNotNull('user_id'); 
            })
            ->orWhere(function($query) {
                $query->whereNull('user_id');
            })
            ->delete();

        return back()->with('success', 'Log aktivitas berhasil dibersihkan.');
    }
}
