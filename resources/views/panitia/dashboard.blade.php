<x-layouts.panitia title="Dashboard Panitia Realtime">
    <!-- Auto Refresh Script -->


    <div class="space-y-6">
        
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-blue-500/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            <div class="relative z-10">
                <h2 class="text-2xl font-bold mb-1">Halo, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-blue-100 text-sm">Berikut adalah pantauan realtime aktivitas pemungutan suara.</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Turnout Presma -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                     <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-full mb-2 inline-block">PRESMA</span>
                     <div class="flex items-center gap-2">
                        <div class="text-2xl font-black text-slate-800">{{ $turnoutPresma }}%</div>
                        <span class="text-xs font-semibold text-slate-400">Turnout</span>
                     </div>
                </div>
                <div class="mt-3">
                     <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width: <?php echo $turnoutPresma; ?>%"></div>
                    </div>
                    <div class="mt-1 text-[10px] text-slate-400 font-medium">
                        {{ number_format($sudahMemilihPresma) }} dari {{ number_format($totalDpt) }} pemilih
                    </div>
                </div>
            </div>

            <!-- Turnout DPM -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                     <span class="bg-purple-50 text-purple-600 text-[10px] font-bold px-2 py-0.5 rounded-full mb-2 inline-block">DPM</span>
                     <div class="flex items-center gap-2">
                        <div class="text-2xl font-black text-slate-800">{{ $turnoutDpm }}%</div>
                        <span class="text-xs font-semibold text-slate-400">Turnout</span>
                     </div>
                </div>
                <div class="mt-3">
                     <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-purple-500 h-full rounded-full" style="width: <?php echo $turnoutDpm; ?>%"></div>
                    </div>
                    <div class="mt-1 text-[10px] text-slate-400 font-medium">
                        {{ number_format($sudahMemilihDpm) }} dari {{ number_format($totalDpt) }} pemilih
                    </div>
                </div>
            </div>

            <!-- Status Pemilih -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                     <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-0.5 rounded-full mb-2 inline-block">METODE</span>
                     <div class="grid grid-cols-2 gap-2">
                        <div>
                            <div class="text-xl font-black text-blue-600">{{ number_format($voters['online']) }}</div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Online</span>
                        </div>
                        <div>
                            <div class="text-xl font-black text-purple-600">{{ number_format($voters['offline']) }}</div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Offline</span>
                        </div>
                     </div>
                </div>
                <div class="mt-3 text-[10px] text-slate-400 font-medium border-t border-slate-50 pt-2 flex items-center gap-1">
                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Total {{ number_format($voters['online'] + $voters['offline']) }} suara masuk
                </div>
            </div>

            <!-- Belum Memilih -->
             <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                 <div>
                    <h3 class="text-xl font-black text-slate-800">{{ number_format($belumMemilihPresma) }}</h3>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Belum Vote (Presma)</p>
                     <p class="text-[10px] text-red-500 font-bold mt-1">Potensi Golput?</p>
                </div>
                 <div class="w-10 h-10 bg-red-50 text-red-500 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

        </div>

        <!-- Charts & Activity -->
        <div class="grid lg:grid-cols-3 gap-6">
            
            <!-- Left: Results -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Presma -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span>
                         Perolehan Suara BEM
                    </h3>
                    <div class="space-y-4">
                        @foreach($kandidats as $kandidat)
                             <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100">
                                <div class="flex justify-between items-center mb-2">
                                     <div class="flex items-center gap-3">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-500">
                                            {{ $kandidat['no_urut'] }}
                                        </div>
                                        <span class="font-bold text-slate-700 text-sm">{{ $kandidat['name'] }}</span>
                                     </div>
                                     <span class="font-mono font-bold text-slate-900">{{ $kandidat['votes'] }}</span>
                                </div>
                                <div class="w-full bg-white h-2.5 rounded-full overflow-hidden border border-slate-100">
                                    <div class="bg-blue-500 h-full rounded-full transition-all duration-1000" style="width: <?php echo $kandidat['percentage']; ?>%"></div>
                                </div>
                                <div class="text-right mt-1 text-[10px] font-bold text-blue-600">{{ $kandidat['percentage'] }}%</div>
                             </div>
                        @endforeach
                    </div>
                </div>

                <!-- DPM -->
                 <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                     <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-purple-500 rounded-full"></span>
                         Perolehan Suara DPM
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                         @foreach($calonDpms as $calon)
                             <div class="flex items-center group">
                                <span class="w-6 h-6 mr-3 flex items-center justify-center bg-slate-100 text-[10px] font-bold text-slate-500 rounded">{{ $calon['no_urut'] }}</span>
                                <div class="flex-1">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-semibold text-slate-700 truncate w-24">{{ $calon['name'] }}</span>
                                        <span class="font-mono text-slate-900">{{ $calon['votes'] }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-purple-500 h-full rounded-full" style="width: <?php echo $calon['percentage']; ?>%"></div>
                                    </div>
                                </div>
                             </div>
                         @endforeach
                    </div>
                 </div>
            </div>

            <!-- Right: Live Feed -->
            <div class="bg-white p-0 rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[600px]">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Live Activity Log</h3>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                     @forelse($recentActivities as $activity)
                        <div class="flex gap-3 items-start relative pb-4 last:pb-0 border-l border-slate-100 pl-4 last:border-0">
                            <div class="absolute -left-[5px] top-1 w-2.5 h-2.5 rounded-full border-2 border-white 
                                {{ ($activity['type'] ?? '') == 'Presma' ? 'bg-blue-500' : 'bg-purple-500' }}"></div>
                            
                            <div>
                                <p class="text-xs text-slate-500">
                                    <span class="font-bold text-slate-800">{{ $activity['name'] }}</span>
                                    telah memilih
                                </p>
                                <div class="flex gap-2 mt-1">
                                    <span class="text-[9px] px-1.5 py-0.5 rounded border 
                                        {{ ($activity['type'] ?? '') == 'Presma' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-purple-50 text-purple-600 border-purple-100' }}">
                                        {{ $activity['type'] ?? 'Vote' }}
                                    </span>
                                    <span class="text-[9px] px-1.5 py-0.5 rounded border font-bold
                                        {{ ($activity['method'] ?? 'Online') == 'Offline' ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }}">
                                        {{ $activity['method'] ?? 'Online' }}
                                    </span>
                                    <span class="text-[9px] text-slate-400 self-center">
                                         @if(isset($activity['timestamp']))
                                            {{ \Carbon\Carbon::parse($activity['timestamp'])->format('H:i:s') }}
                                         @else
                                            Just Now
                                         @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-xs">Menunggu suara masuk...</div>
                    @endforelse
                </div>
            </div>
            
        </div>
    </div>
</x-layouts.panitia>