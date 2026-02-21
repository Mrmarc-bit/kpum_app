<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Vote;
use App\Models\DpmVote;
use App\Models\Kandidat;
use App\Models\CalonDpm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $service;

    public function __construct(\App\Services\DashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // 1. Get Cached Stats
        $stats = $this->service->getStats();
        
        // 2. Get Recent Activities
        $recentActivities = $this->service->getRecentActivities();

        return view('panitia.dashboard', [
            'totalDpt' => $stats['totalDpt'],
            'voters' => $stats['voters'],
            
            // Presma Data
            'sudahMemilihPresma' => $stats['presma']['sudah'],
            'belumMemilihPresma' => $stats['presma']['belum'],
            'turnoutPresma' => $stats['presma']['turnout'],
            'totalKandidatPresma' => $stats['presma']['totalCandidates'],
            'kandidats' => $stats['presma']['results'],
            
            // DPM Data
            'sudahMemilihDpm' => $stats['dpm']['sudah'],
            'belumMemilihDpm' => $stats['dpm']['belum'],
            'turnoutDpm' => $stats['dpm']['turnout'],
            'totalCalonDpm' => $stats['dpm']['totalCandidates'],
            'calonDpms' => $stats['dpm']['results'],

            'recentActivities' => $recentActivities,
            'title' => 'Dashboard Panitia Realtime'
        ]);
    }
}
