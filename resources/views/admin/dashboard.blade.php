<x-layouts.admin title="Dashboard Realtime">

    
    <div class="space-y-8">
        <!-- Top Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Partisipasi Presma -->
            <div class="relative group h-full">
                <div class="absolute inset-0 bg-blue-500/20 blur-xl rounded-2xl group-hover:blur-2xl transition-all opacity-0 group-hover:opacity-100"></div>
                <div class="relative h-full bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-lg">PRESMA</span>
                        </div>
                        <div class="text-3xl font-black text-slate-900 mb-1">{{ $turnoutPresma }}<span class="text-lg text-slate-500 font-bold ml-1">%</span></div>
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Partisipasi BEM</p>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-xs font-bold text-slate-400 mb-1">
                            <span>{{ number_format($sudahMemilihPresma) }} Suara</span>
                            <span>Target: {{ number_format($totalDpt) }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width: <?php echo $turnoutPresma; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partisipasi DPM -->
            <div class="relative group h-full">
                <div class="absolute inset-0 bg-purple-500/20 blur-xl rounded-2xl group-hover:blur-2xl transition-all opacity-0 group-hover:opacity-100"></div>
                <div class="relative h-full bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-1 rounded-lg">DPM (LEGISLATIF)</span>
                        </div>
                        <div class="text-3xl font-black text-slate-900 mb-1">{{ $turnoutDpm }}<span class="text-lg text-slate-500 font-bold ml-1">%</span></div>
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Partisipasi DPM</p>
                    </div>
                     <div class="mt-4">
                        <div class="flex justify-between text-xs font-bold text-slate-400 mb-1">
                            <span>{{ number_format($sudahMemilihDpm) }} Suara</span>
                            <span>Target: {{ number_format($totalDpt) }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500 rounded-full" style="width: <?php echo $turnoutDpm; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Total Data Stats -->
            <div class="relative group h-full">
                <div class="absolute inset-0 bg-green-500/20 blur-xl rounded-2xl group-hover:blur-2xl transition-all opacity-0 group-hover:opacity-100"></div>
                <div class="relative h-full bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between">
                    <div>
                         <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-green-50 rounded-xl text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-slate-900 mb-1">{{ number_format($totalDpt) }}</div>
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Total DPT (Mahasiswa)</p>
                    </div>
                    <div class="mt-4 text-xs font-medium text-slate-400">
                        {{ $totalKandidatPresma }} Paslon BEM &middot; {{ $totalCalonDpm }} Calon DPM
                    </div>
                </div>
            </div>

            <!-- Status Pemilih -->
             <div class="relative group h-full">
                <div class="absolute inset-0 bg-slate-500/20 blur-xl rounded-2xl group-hover:blur-2xl transition-all opacity-0 group-hover:opacity-100"></div>
                <div class="relative h-full bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                             <div class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </div>
                            <div class="text-lg font-black text-slate-900">Status Pemilih</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div class="bg-blue-50 p-3 rounded-xl">
                                <div class="text-[10px] uppercase font-bold text-blue-500 mb-1">Online</div>
                                <div class="text-xl font-black text-blue-700">{{ number_format($voters['online']) }}</div>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-xl">
                                <div class="text-[10px] uppercase font-bold text-purple-500 mb-1">Offline (QR)</div>
                                <div class="text-xl font-black text-purple-700">{{ number_format($voters['offline']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column: Election Results -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- BEM Result Chart -->
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                                <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                                Hasil Sementara BEM
                            </h3>
                            <p class="text-sm text-slate-500 pl-4">Presiden & Wakil Presiden Mahasiswa</p>
                        </div>
                    </div>
                    
                     <div class="h-64 flex items-end justify-around gap-4 relative z-10 px-4">
                        @foreach($kandidats as $kandidat)
                             @php
                                $colors = [
                                    ['from-blue-600 to-blue-400', 'text-blue-600'],
                                    ['from-indigo-600 to-violet-500', 'text-indigo-600'],
                                    ['from-emerald-500 to-teal-400', 'text-emerald-600'],
                                    ['from-orange-500 to-red-400', 'text-orange-600']
                                ];
                                $color = $colors[$loop->index % count($colors)];
                            @endphp
                            <div class="flex-1 flex flex-col items-center justify-end group h-full">
                                <div class="w-full max-w-[80px] bg-slate-100 rounded-t-2xl relative overflow-hidden transition-all duration-700 hover:shadow-lg" style="height: <?php echo max(10, $kandidat['percentage']); ?>%">
                                    <div class="absolute bottom-0 w-full h-full bg-gradient-to-t {{ $color[0] }}"></div>
                                    <div class="absolute -top-10 left-0 w-full text-center font-black text-slate-700 group-hover:-top-6 transition-all">{{ $kandidat['percentage'] }}%</div>
                                    <!-- Tooltip -->
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-white font-bold text-xs drop-shadow-md">{{ $kandidat['votes'] }}</span>
                                    </div>
                                </div>
                                <div class="mt-3 text-center">
                                    <div class="font-bold text-slate-800 text-sm">No. {{ $kandidat['no_urut'] }}</div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-tight leading-tight mt-1 line-clamp-2">{{ $kandidat['name'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- DPM Result Chart -->
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                         <div>
                            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                                <span class="w-2 h-8 bg-purple-600 rounded-full"></span>
                                Hasil Sementara DPM
                            </h3>
                            <p class="text-sm text-slate-500 pl-4">Dewan Perwakilan Mahasiswa</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                         @foreach($calonDpms as $calon)
                            <div class="flex items-center p-3 bg-white/50 rounded-xl hover:bg-white transition-colors border border-transparent hover:border-purple-100 group">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-black text-slate-400 mr-4 group-hover:bg-purple-100 group-hover:text-purple-600 transition-colors">
                                    {{ $calon['no_urut'] }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-1">
                                        <h4 class="font-bold text-slate-800 text-sm truncate w-32">{{ $calon['name'] }}</h4>
                                        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">{{ $calon['percentage'] }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-purple-500 h-full rounded-full" style="width: <?php echo $calon['percentage']; ?>%"></div>
                                    </div>
                                    <div class="flex justify-between mt-1 text-[10px] text-slate-400">
                                        <span>{{ $calon['fakultas'] }}</span>
                                        <span>{{ number_format($calon['votes']) }} Suara</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right Column: Activity & Menu -->
            <div class="space-y-6">
                
                <!-- Live Activity Feed -->
                <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col h-[500px]">
                     <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                        </span>
                        Live Activity
                    </h3>

                    <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                        @forelse($recentActivities as $activity)
                            <div class="group flex gap-3 items-start animate-[fadeIn_0.5s_ease-out]">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 shadow-sm border
                                    {{ isset($activity['type']) && $activity['type'] == 'Presma' ? 'bg-blue-100 text-blue-600 border-blue-200' : 'bg-purple-100 text-purple-600 border-purple-200' }}">
                                    <span class="font-bold text-[10px]">{{ substr($activity['type'] ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                     <p class="text-xs font-medium text-slate-900 leading-snug">
                                        <span class="font-bold">{{ $activity['name'] }}</span>
                                        <span class="text-slate-500 font-normal">telah memilih di</span>
                                        <span class="font-bold {{ isset($activity['type']) && $activity['type'] == 'Presma' ? 'text-blue-600' : 'text-purple-600' }}">{{ $activity['type'] ?? '' }}</span>
                                    </p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold border {{ ($activity['method'] ?? 'Online') == 'Offline' ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }}">
                                            {{ $activity['method'] ?? 'Online' }}
                                        </span>
                                        <p class="text-[9px] uppercase tracking-wider font-bold text-slate-400">
                                             @if(isset($activity['timestamp']))
                                                {{ \Carbon\Carbon::parse($activity['timestamp'])->diffForHumans() }}
                                             @else
                                                Baru saja
                                             @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-400 text-xs">Belum ada aktivitas.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Menu -->
                 <div class="bg-slate-800 rounded-3xl p-6 text-white shadow-xl shadow-slate-800/20">
                    <h3 class="font-bold text-lg mb-4">Akses Cepat</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.kandidat.index') }}" class="block p-3 bg-white/5 hover:bg-white/10 rounded-xl transition-colors border border-white/5 text-sm">
                            Manajemen Kandidat BEM
                        </a>
                        <a href="{{ route('admin.calon_dpm.index') }}" class="block p-3 bg-white/5 hover:bg-white/10 rounded-xl transition-colors border border-white/5 text-sm">
                            Manajemen Calon DPM
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="block p-3 bg-white/5 hover:bg-white/10 rounded-xl transition-colors border border-white/5 text-sm">
                            Pengaturan Sistem
                        </a>
                    </div>
                 </div>

            </div>
        </div>
    </div>
</x-layouts.admin>