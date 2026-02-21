<?php

namespace App\Services;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Get Analytics Stats safely cached.
     * Caching Strategy: 60 seconds (Analytics doesn't need to be strictly realtime per second).
     */
    public function getAnalytics()
    {
        return Cache::remember('admin_analytics_stats', 60, function () {
            $totalDpt = Mahasiswa::count();
            $totalVotes = Mahasiswa::whereNotNull('voted_at')->count();
            $participationRate = $totalDpt > 0 ? round(($totalVotes / $totalDpt) * 100, 1) : 0;

            // Hourly Traffic (Simplified for performance)
            // Group by hour directly in DB if possible, but Laravel collection method is fine for moderate dataset
            // For production with Millions of rows, this should be raw SQL grouping.
            $votesByHour = Mahasiswa::whereNotNull('voted_at')
                ->select('voted_at')
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->voted_at)->format('H:00');
                })
                ->map(fn($group) => $group->count())
                ->sortKeys();

            $peakHour = '-';
            if ($votesByHour->isNotEmpty()) {
                $maxVotes = $votesByHour->max();
                $peakHour = $votesByHour->search($maxVotes);
            }

            return [
                'totalDpt' => $totalDpt,
                'totalVotes' => $totalVotes,
                'participationRate' => $participationRate,
                'peakHour' => $peakHour,
                'votesByHour' => $votesByHour,
            ];
        });
    }

    public function getChartData()
    {
        // Shorter cache for charts if user is actively refreshing
        return Cache::remember('admin_analytics_chart', 30, function () {
            $stats = $this->getAnalytics(); // Reuse if possible or recalc
            $votesByHour = $stats['votesByHour'];

            $processedData = [];
            $categories = [];

            if ($votesByHour->isNotEmpty()) {
                $start = Carbon::parse($votesByHour->keys()->first())->subHour();
                $end = Carbon::parse($votesByHour->keys()->last())->addHour();
                
                // Add boundary checks
                $now = now();
                if ($end->greaterThan($now)) $end = $now->addHour();

                for ($time = $start->copy(); $time <= $end; $time->addHour()) {
                    $hourKey = $time->format('H:00');
                    $categories[] = $hourKey;
                    $processedData[] = $votesByHour->get($hourKey, 0);
                }
            } else {
                 $categories = [now()->format('H:00')];
                 $processedData = [0];
            }

            return [
                'categories' => $categories,
                'data' => $processedData,
                'total_votes' => $stats['totalVotes'],
                'participation_rate' => $stats['participationRate'],
                'peak_hour' => $stats['peakHour']
            ];
        });
    }
}
