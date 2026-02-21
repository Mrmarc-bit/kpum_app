<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalDpt = \App\Models\Mahasiswa::count();
        $totalVotes = \App\Models\Mahasiswa::whereNotNull('voted_at')->count();
        $participationRate = $totalDpt > 0 ? round(($totalVotes / $totalDpt) * 100, 1) : 0;

        // Hourly Traffic
        $votesByHour = \App\Models\Mahasiswa::whereNotNull('voted_at')
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->voted_at)->format('H:00');
            })
            ->map->count()
            ->sortKeys();

        // Find Peak Hour
        $peakHour = '-';
        if ($votesByHour->isNotEmpty()) {
            $maxVotes = $votesByHour->max();
            $peakHour = $votesByHour->search($maxVotes);
        }

        return view('panitia.analytics.index', [
            'title' => 'Realtime Analytics',
            'totalVotes' => $totalVotes,
            'participationRate' => $participationRate,
            'peakHour' => $peakHour,
            'votesByHour' => $votesByHour,
            'totalDpt' => $totalDpt
        ]);
    }
}
