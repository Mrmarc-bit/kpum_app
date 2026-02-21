<x-layouts.kpps title="Dashboard KPPS">
    <div class="space-y-6">
        
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-blue-500/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold mb-1">Halo, Petugas KPPS! 👋</h2>
                    <p class="text-blue-100 text-sm">Selamat bertugas. Pantau kehadiran pemilih di sini.</p>
                </div>
                <div>
                    <a href="{{ route('kpps.scanner.index') }}" class="px-6 py-3 bg-white text-blue-600 font-bold rounded-xl shadow-lg hover:shadow-xl hover:bg-blue-50 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Buka Scanner
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Total DPT -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                     <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded-full mb-2 inline-block">TOTAL PEMILIH</span>
                     <div class="text-4xl font-black text-slate-800">{{ number_format($totalDpt) }}</div>
                     <span class="text-xs font-semibold text-slate-400">Mahasiswa Terdaftar (DPT)</span>
                </div>
            </div>

            <!-- Total Hadir -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                     <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-0.5 rounded-full mb-2 inline-block">HADIR (SCAN)</span>
                     <div class="text-4xl font-black text-emerald-600">{{ number_format($totalAttended) }}</div>
                     <span class="text-xs font-semibold text-slate-400">Mahasiswa Check-in</span>
                </div>
            </div>

            <!-- Presentase -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                     <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-full mb-2 inline-block">KEHADIRAN</span>
                     <div class="flex items-center gap-2">
                        <div class="text-4xl font-black text-blue-600">{{ $attendancePercentage }}%</div>
                     </div>
                     <span class="text-xs font-semibold text-slate-400">Total Kehadiran</span>
                </div>
                <div class="mt-4">
                     <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full transition-all duration-1000" style="width: <?php echo $attendancePercentage; ?>%"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Aktivitas Scan Terbaru</h3>
                <span class="text-xs text-slate-500">10 data terakhir</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Waktu</th>
                            <th scope="col" class="px-6 py-3">NIM</th>
                            <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentScans as $scan)
                            <tr class="bg-white border-b hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-slate-600">
                                    {{ $scan->attended_at->format('H:i:s') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $scan->nim }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $scan->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Hadir</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada data scan masuk hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.kpps>
