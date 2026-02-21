<?php

namespace App\Http\Controllers\Kpps;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Simple Realtime Stats
        $totalDpt = Mahasiswa::count();
        $totalAttended = Mahasiswa::whereNotNull('attended_at')->count();
        $attendancePercentage = $totalDpt > 0 ? round(($totalAttended / $totalDpt) * 100, 1) : 0;
        
        // Recent Scans (Last 10)
        $recentScans = Mahasiswa::whereNotNull('attended_at')
            ->orderBy('attended_at', 'desc')
            ->take(10)
            ->get();

        return view('kpps.dashboard', [
            'totalDpt' => $totalDpt,
            'totalAttended' => $totalAttended,
            'attendancePercentage' => $attendancePercentage,
            'recentScans' => $recentScans,
            'title' => 'Dashboard KPPS'
        ]);
    }
}
