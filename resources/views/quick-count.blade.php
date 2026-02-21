<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Count Real-Time - KPUM UNUGHA</title>
    
    <!-- Favicon -->
    @php $appLogo = \App\Models\Setting::where('key', 'app_logo')->value('value'); @endphp
    @if($appLogo)
        <link rel="icon" href="{{ asset($appLogo) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif

    <!-- Fonts & Scripts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-indigo-500 selection:text-white"
      x-data="quickCountData()" x-init="initData()">

    <!-- Header -->
    <header class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($appLogo)
                    <img src="{{ asset($appLogo) }}" alt="Logo" class="h-8 w-auto">
                @else
                    <div class="h-8 w-8 bg-indigo-600 rounded flex items-center justify-center text-white font-bold">K</div>
                @endif
                <div class="hidden sm:block">
                    <h1 class="font-bold text-slate-900 text-sm leading-tight">Quick Count</h1>
                    <p class="text-[10px] uppercase font-bold text-indigo-600 tracking-wider">Real-Time Result</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 rounded-full text-xs font-bold animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Live Update
                </div>
                <a href="/" class="text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-24 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">

        <!-- Hero: Participation Rate -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Participation Card -->
            <div class="md:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-indigo-50 rounded-full blur-3xl group-hover:bg-indigo-100 transition-colors"></div>
                
                <h2 class="text-lg font-bold text-slate-700 mb-6">Partisipasi Pemilih</h2>
                
                <div class="flex flex-col sm:flex-row items-center gap-8">
                    <!-- Circular Progress -->
                    <div class="relative w-40 h-40 shrink-0">
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="transparent" class="text-slate-100" />
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="transparent" 
                                    class="text-indigo-600 transition-all duration-1000 ease-out"
                                    :stroke-dasharray="circumference"
                                    :stroke-dashoffset="circumference - (meta.participation_rate / 100) * circumference"
                                    stroke-linecap="round" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-black text-slate-900" x-text="meta.participation_rate + '%'">0%</span>
                            <span class="text-xs font-semibold text-slate-500">Masuk</span>
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="flex-1 grid grid-cols-2 gap-4 w-full">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total DPT</p>
                            <p class="text-2xl font-black text-slate-900" x-text="formatNumber(meta.total_dpt)">0</p>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Suara Masuk</p>
                            <p class="text-2xl font-black text-indigo-600" x-text="formatNumber(meta.total_voted)">0</p>
                        </div>
                        <div class="col-span-2 text-xs text-slate-400 font-medium pt-2">
                            *Data diperbarui setiap 10 detik. Terakhir update: <span x-text="meta.updated_at" class="text-slate-600 font-bold">--:--:--</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaderboard Highlight (Winning Presma) -->
            <div class="bg-linear-to-br from-indigo-600 to-violet-700 rounded-3xl shadow-lg border border-white/10 p-6 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('/assets/images/noise.svg')] opacity-20 mix-blend-overlay"></div>
                <h2 class="text-sm font-bold text-indigo-200 uppercase tracking-widest mb-4 relative z-10">Unggul Sementara</h2>
                
                <template x-if="presma.length > 0">
                    <div class="relative z-10 text-center mt-4">
                        <div class="w-20 h-20 mx-auto rounded-full border-4 border-white/30 p-1 mb-4 shadow-xl">
                            <img :src="presma[0].foto || 'https://ui-avatars.com/api/?name='+presma[0].name" class="w-full h-full rounded-full object-cover bg-white">
                        </div>
                        <h3 class="text-xl font-bold leading-tight mb-1" x-text="presma[0].name">Candidate Name</h3>
                        <div class="text-4xl font-black mt-2" x-text="presma[0].percentage + '%'">0%</div>
                        <p class="text-indigo-200 text-xs font-medium mt-1">Perolehan Suara</p>
                    </div>
                </template>
                <template x-if="presma.length === 0">
                    <div class="h-full flex items-center justify-center text-indigo-200 text-sm font-medium">
                        Menunggu Data...
                    </div>
                </template>
            </div>
        </section>

        <!-- Presma Results -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-slate-900">Hasil Pemilihan Presiden Mahasiswa</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <template x-for="candidate in presma" :key="candidate.id">
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-slate-100 shrink-0 overflow-hidden ring-2 ring-white shadow-sm">
                                <img :src="candidate.foto || 'https://ui-avatars.com/api/?name='+candidate.name" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-slate-900 truncate" x-text="candidate.name"></h3>
                                    <span class="text-xs font-bold bg-slate-100 text-slate-600 px-2 py-1 rounded-full">No. <span x-text="candidate.no_urut"></span></span>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-black text-indigo-600" x-text="candidate.percentage + '%'"></span>
                                    <span class="text-xs text-slate-500 font-medium">(<span x-text="formatNumber(candidate.votes)"></span> suara)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out relative"
                                 :style="`width: ${candidate.percentage}%; background-color: ${candidate.color || '#4F46E5'};`">
                                 <div class="absolute inset-0 bg-white/20 animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </section>

        <!-- DPM Results -->
        <section class="bg-white rounded-[2rem] p-6 md:p-8 border border-slate-100 shadow-sm">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Perolehan Suara DPM</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="py-3 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest w-12">#</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Kandidat</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest hidden sm:table-cell">Fakultas</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest w-1/3">Suara</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="(candidate, index) in dpm" :key="candidate.id">
                            <tr class="group hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-4 font-bold text-slate-500" x-text="index + 1"></td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden shrink-0">
                                            <img :src="candidate.foto || 'https://ui-avatars.com/api/?name='+candidate.name" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm" x-text="candidate.name"></p>
                                            <p class="text-xs text-slate-500 sm:hidden" x-text="candidate.fakultas"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 hidden sm:table-cell">
                                    <span class="text-xs font-semibold px-2 py-1 rounded bg-slate-100 text-slate-600" x-text="candidate.fakultas"></span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden min-w-[100px]">
                                            <div class="h-full bg-indigo-500 rounded-full" :style="`width: ${candidate.percentage}%`"></div>
                                        </div>
                                        <div class="text-right min-w-[80px]">
                                            <span class="block text-sm font-bold text-slate-900" x-text="formatNumber(candidate.votes)"></span>
                                            <span class="block text-[10px] text-slate-500" x-text="candidate.percentage + '%'"></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="dpm.length === 0">
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-500 text-sm">Belum ada data DPM.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <footer class="bg-white border-t border-slate-200 py-8 text-center">
        <p class="text-slate-500 text-sm font-medium">
            &copy; {{ date('Y') }} Komisi Pemilihan Umum Mahasiswa UNUGHA. <br>
            <span class="text-xs text-slate-400">Powered by E-Voting System</span>
        </p>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quickCountData', () => ({
                meta: { updated_at: '--:--', participation_rate: 0, total_dpt: 0, total_voted: 0 },
                presma: [],
                dpm: [],
                circumference: 2 * Math.PI * 70, // for progress circle
                
                async initData() {
                    await this.fetchData();
                    setInterval(() => this.fetchData(), 10000); // Refresh every 10s
                },

                async fetchData() {
                    try {
                        const response = await fetch('/api/quick-count/data');
                        const data = await response.json();
                        
                        this.meta = data.meta;
                        this.presma = data.presma;
                        this.dpm = data.dpm;
                    } catch (error) {
                        console.error('Error fetching quick count:', error);
                    }
                },

                formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                }
            }))
        })
    </script>
</body>
</html>
