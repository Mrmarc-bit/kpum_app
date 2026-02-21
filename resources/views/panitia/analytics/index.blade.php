<x-layouts.panitia :title="$title">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Top Stats Cards -->
        <div class="bg-white/70 backdrop-blur-xl p-6 rounded-3xl border border-white/50 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Votes</p>
                    <h3 class="text-2xl font-black text-slate-900">{{ number_format($totalVotes) }}</h3>
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $participationRate }}%"></div>
            </div>
            <p class="text-xs text-slate-400 mt-2">{{ $participationRate }}% participation rate</p>
        </div>

        <div class="bg-white/70 backdrop-blur-xl p-6 rounded-3xl border border-white/50 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Peak Traffic</p>
                    <h3 class="text-2xl font-black text-slate-900">{{ $peakHour }}</h3>
                </div>
            </div>
            <div class="h-2 flex gap-1">
                <div class="bg-indigo-300 w-1/4 rounded-full opacity-30"></div>
                <div class="bg-indigo-400 w-1/4 rounded-full opacity-60"></div>
                <div class="bg-indigo-500 w-1/4 rounded-full"></div>
                <div class="bg-indigo-600 w-1/4 rounded-full"></div>
            </div>
            <p class="text-xs text-slate-400 mt-2">Highest activity detected</p>
        </div>

        <div class="bg-white/70 backdrop-blur-xl p-6 rounded-3xl border border-white/50 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-2xl animate-[pulse_3s_infinite]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">System Status</p>
                    <h3 class="text-2xl font-black text-green-600">Healthy</h3>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <p class="text-xs text-slate-400">All systems operational</p>
            </div>
        </div>
    </div>

    <!-- Main Chart Section -->
    <div
        class="bg-white/70 backdrop-blur-xl p-6 rounded-3xl border border-white/50 shadow-sm min-h-[400px] flex flex-col justify-between relative overflow-hidden group">
        <div class="flex justify-between items-center mb-6 relative z-10">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Aktivitas Suara per Jam</h3>
                <p class="text-sm text-slate-500">Distribusi waktu voting mahasiswa.</p>
            </div>
        </div>

        <div class="flex items-end justify-between gap-2 h-64 w-full relative z-10 px-4">
            @php
                $maxVal = $votesByHour->max() ?: 1;
            @endphp
            @forelse($votesByHour as $hour => $count)
                <div class="flex flex-col items-center gap-2 group flex-1">
                    <div
                        class="w-full max-w-[40px] bg-blue-500/20 rounded-t-lg relative h-full flex items-end overflow-hidden group-hover:bg-blue-500/30 transition-colors">
                        <div class="w-full bg-blue-600 rounded-t-lg hover:shadow-lg hover:shadow-blue-500/50 transition-all duration-500 relative group-hover:scale-y-105 origin-bottom"
                            style="height: {{ ($count / $maxVal) * 100 }}%">
                            <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                        </div>
                        <!-- Tooltip -->
                        <div
                            class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] font-bold py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-20 pointer-events-none">
                            {{ $count }} Suara
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400">{{ $hour }}</span>
                </div>
            @empty
                <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">
                    Belum ada data visualisasi.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.panitia>