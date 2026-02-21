<section id="quick-count" class="py-24 bg-white relative overflow-hidden">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" x-data="quickCountData()" x-init="initData()">
        
        <!-- Header Global -->
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-2 tracking-tight">
                Quick Count Real-Time
            </h2>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-bold border border-green-100">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                Live Update <span x-text="meta.updated_at" class="ml-1 opacity-75"></span>
            </div>
        </div>

        <!-- 1. Quick Count PRESMA -->
        <div class="mb-20">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-slate-900">Pemilihan Presiden & Wakil Presiden Mahasiswa</h3>
                <p class="text-slate-500">Hasil Perolehan Suara Sementara</p>
            </div>

            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 p-6 md:p-10">
                <div class="flex flex-col lg:flex-row gap-10">
                    
                    <!-- Chart Area -->
                    <div class="flex-1">
                        <template x-if="presma.length > 0">
                            <div class="h-80 flex items-end gap-4 md:gap-8 min-w-0">
                                <template x-for="(candidate, index) in presma" :key="candidate.id">
                                    <div class="flex-1 flex flex-col items-center group h-full justify-end">
                                        
                                        <!-- Percentage & Votes Floating -->
                                        <div class="mb-3 text-center transition-all duration-500 transform group-hover:-translate-y-1">
                                            <div class="text-xl md:text-2xl font-black text-slate-900" x-text="candidate.percentage + '%'"></div>
                                            <div class="text-xs font-semibold text-slate-400" x-text="formatNumber(candidate.votes) + ' Suara'"></div>
                                        </div>

                                        <!-- The Bar -->
                                        <div class="w-full max-w-[120px] rounded-2xl relative overflow-hidden transition-all duration-1000 ease-out hover:brightness-110 relative"
                                             :style="`height: ${candidate.percentage}%; background-color: ${getColor(index)}; min-height: 12px;`">
                                            <!-- Shine Effect -->
                                            <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent opacity-50"></div>
                                        </div>

                                        <!-- Label Bottom -->
                                        <div class="mt-4 text-center">
                                            <div class="w-10 h-10 mx-auto rounded-full overflow-hidden border-2 border-slate-100 mb-2 shadow-sm">
                                                <img :src="candidate.foto || 'https://ui-avatars.com/api/?name='+candidate.name" class="w-full h-full object-cover">
                                            </div>
                                            <div class="text-sm font-bold text-slate-800 leading-tight px-2" x-text="candidate.no_urut + '. ' + candidate.name"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="presma.length === 0">
                            <div class="h-64 flex items-center justify-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 text-slate-400 font-medium">
                                Menunggu Data Masuk...
                            </div>
                        </template>
                    </div>



                </div>
            </div>
        </div>

        <!-- Spacer to force separation -->
        <div class="h-32 w-full block" aria-hidden="true"></div>

        <!-- 2. Quick Count DPM -->
        <div class="mt-12 pt-12 border-t border-slate-200">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-slate-900">Pemilihan Dewan Perwakilan Mahasiswa</h3>
                <p class="text-slate-500">Hasil Perolehan Suara Sementara</p>
            </div>

            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 p-6 md:p-10">
                <div class="flex flex-col lg:flex-row gap-10">
                    
                    <!-- Chart Area (Scrollable for DPM) -->
                    <div class="flex-1 overflow-x-auto pb-4 custom-scrollbar">
                        <template x-if="dpm.length > 0">
                            <!-- Minimum width to ensure bars don't get squished if many candidates -->
                            <div class="h-80 flex items-end gap-6 md:gap-8 min-w-[max-content] px-4">
                                <template x-for="(candidate, index) in dpm" :key="candidate.id">
                                    <div class="w-24 md:w-32 flex flex-col items-center group h-full justify-end shrink-0">
                                        
                                        <!-- Percentage -->
                                        <div class="mb-3 text-center transition-all duration-500 transform group-hover:-translate-y-1">
                                            <div class="text-xl font-black text-slate-900" x-text="candidate.percentage + '%'"></div>
                                            <div class="text-[10px] font-semibold text-slate-400" x-text="formatNumber(candidate.votes)"></div>
                                        </div>

                                        <!-- The Bar -->
                                        <div class="w-full md:w-20 rounded-2xl relative overflow-hidden transition-all duration-1000 ease-out hover:brightness-110 relative"
                                             :style="`height: ${candidate.percentage}%; background-color: ${getColor(index + 3)}; min-height: 10px;`">
                                             <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent opacity-50"></div>
                                        </div>

                                        <!-- Label -->
                                        <div class="mt-4 text-center w-full">
                                            <div class="w-8 h-8 mx-auto rounded-full overflow-hidden border border-slate-100 mb-2 shadow-sm">
                                                <img :src="candidate.foto || 'https://ui-avatars.com/api/?name='+candidate.name" class="w-full h-full object-cover">
                                            </div>
                                            <div class="text-xs font-bold text-slate-800 leading-tight truncate px-1" x-text="candidate.name" :title="candidate.name"></div>
                                            <div class="text-[10px] text-slate-500 truncate" x-text="candidate.fakultas"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                         <template x-if="dpm.length === 0">
                            <div class="h-64 flex items-center justify-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 text-slate-400 font-medium">
                                Belum ada data DPM masuk.
                            </div>
                        </template>
                    </div>

                    <!-- Right Side: Info Card (Optional: or reuse Data Masuk if same poll) -->
                    <!-- Since DPT for presma & dpm usually same, we can just show a summary or info box here -->


                </div>
            </div>
        </div>

    </div>

    <!-- Alpine Data Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
             if (!Alpine.data('quickCountData')) {
                Alpine.data('quickCountData', () => ({
                    meta: { updated_at: '--:--', participation_rate: 0, total_dpt: 0, total_voted: 0 },
                    presma: [],
                    dpm: [],
                    colors: ['#6366f1', '#10b981', '#f59e0b', '#3b82f6', '#ec4899', '#8b5cf6', '#14b8a6'],
                    
                    async initData() {
                        await this.fetchData();
                        setInterval(() => this.fetchData(), 10000); 
                    },

                    async fetchData() {
                        try {
                            const response = await fetch('/api/quick-count/data', {
                                headers: { 'Accept': 'application/json' }
                            });
                            
                            const contentType = response.headers.get("content-type");
                            if (contentType && contentType.indexOf("application/json") !== -1) {
                                const data = await response.json();
                                this.meta = data.meta;
                                this.presma = data.presma;
                                this.dpm = data.dpm;
                            } else {
                                console.warn("QC: Received non-JSON response");
                            }
                        } catch (error) {
                            console.error('QC Fetch Error:', error);
                        }
                    },

                    getColor(index) {
                        return this.colors[index % this.colors.length];
                    },

                    formatNumber(num) {
                        return new Intl.NumberFormat('id-ID').format(num);
                    },

                    dpmVotesTotal() {
                        return this.dpm.reduce((acc, curr) => acc + curr.votes, 0);
                    }
                }))
             }
        })
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</section>
