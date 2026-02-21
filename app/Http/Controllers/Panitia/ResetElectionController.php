<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Mahasiswa;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ResetElectionController extends Controller
{
    public function index()
    {
        return view('panitia.reset.index', [
            'title' => 'Reset Election'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirm_text' => 'required|in:RESET ELECTION'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah. Tidak dapat melanjutkan reset.');
        }

        try {
            DB::transaction(function () use ($user, $request) {
                // 1. Delete Votes (Use delete() for transaction safety)
                Vote::query()->delete();
                \App\Models\DpmVote::query()->delete();

                // 2. Reset Voted Status
                Mahasiswa::query()->update([
                    'voted_at' => null,
                    'dpm_voted_at' => null,
                    'voting_method' => null,
                    'ip_address' => null,
                    'attended_at' => null
                ]);

                // 3. Log Action
                AuditLog::create([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'action' => 'RESET ELECTION',
                    'details' => 'Menghapus semua suara (Presma & DPM) dan mereset status pemilih.',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            });

            return redirect()->route('panitia.dashboard')->with('success', 'Pemilihan berhasil di-reset. Semua data suara telah dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mereset data: ' . $e->getMessage());
        }
    }
}
