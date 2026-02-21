<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $service;

    public function __construct(\App\Services\AnalyticsService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $stats = $this->service->getAnalytics();

        return view('admin.analytics.index', [
            'title' => 'Realtime Analytics',
            'totalVotes' => $stats['totalVotes'],
            'participationRate' => $stats['participationRate'],
            'peakHour' => $stats['peakHour'],
            'votesByHour' => $stats['votesByHour'],
            'totalDpt' => $stats['totalDpt']
        ]);
    }

    public function getChartData()
    {
        $data = $this->service->getChartData();
        return response()->json($data);
    }
}
