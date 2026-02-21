<x-layouts.admin :title="$title">
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
                <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo $participationRate ?? 0; ?>%;"></div>
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
    <div class="bg-white/70 backdrop-blur-xl p-6 rounded-3xl border border-white/50 shadow-sm min-h-[450px] flex flex-col relative overflow-hidden group">
        <div class="flex justify-between items-center mb-2 px-2 z-10">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Aktivitas Suara per Jam</h3>
                <p class="text-sm text-slate-500">Distribusi waktu voting mahasiswa secara realtime.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-xs font-mono text-green-600 uppercase tracking-widest">LIVE</span>
            </div>
        </div>

        <!-- Chart Container -->
        <div id="realtimeChart" class="w-full h-full min-h-[350px]"></div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            var options = {
                series: [{
                    name: 'Suara Masuk',
                    data: <?php echo json_encode($votesByHour->values()); ?> 
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    fontFamily: 'Inter, sans-serif',
                    zoom: { enabled: false },
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'linear',
                        dynamicAnimation: { speed: 1000 }
                    }
                },
                colors: ['#4F46E5'], // Indigo-600
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: <?php echo json_encode($votesByHour->keys()); ?>,
                    tooltip: { enabled: false },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                           colors: '#64748b',
                           fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '12px'
                        },
                        formatter: function(val) {
                            return val.toFixed(0);
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: function (val) {
                            return val + " Suara"
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#realtimeChart"), options);
            chart.render();

            // Realtime Polling every 5 seconds
            setInterval(() => {
                fetch('{{ route("admin.analytics.data") }}')
                    .then(response => response.json())
                    .then(data => {
                        chart.updateOptions({
                            xaxis: { categories: data.categories }
                        });
                        chart.updateSeries([{
                            data: data.data
                        }]);
                        
                        // Update other stats if needed can be done here by targeting DOM elements
                    })
                    .catch(error => console.error('Error fetching analytics:', error));
            }, 5000); // 5 Seconds
        });
    </script>
</x-layouts.admin>