<x-layouts.admin title="Pusat Laporan">
    <div class="space-y-12">
        
        <!-- Header -->
        <div class="flex items-end justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pusat Laporan</h1>
                <p class="text-slate-500 mt-2 font-medium">Unduh dokumen pelaksanaan pemilihan umum.</p>
            </div>
            <div class="px-4 py-2 bg-slate-100 rounded-xl text-sm font-bold text-slate-500 font-mono tracking-tight">
                {{ now()->translatedFormat('l, d F Y • H:i') }}
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Card 1: Hasil Pemilihan -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-indigo-200/40 hover:shadow-indigo-300/40 transition-all duration-300 relative overflow-hidden group hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-50 rounded-full -mr-12 -mt-12 group-hover:scale-125 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/30 group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2 tracking-tight">Laporan Hasil</h3>
                    <p class="text-slate-500 mb-8 text-sm leading-relaxed font-medium">
                        Dokumen lengkap rekapitulasi suara untuk Presma dan DPM.
                    </p>
                    <a href="{{ route('admin.reports.results') }}" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-800 transition-colors group/link">
                        <span>Unduh PDF Resmi</span>
                        <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Berita Acara -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-amber-200/40 hover:shadow-amber-300/40 transition-all duration-300 relative overflow-hidden group hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-40 h-40 bg-amber-50 rounded-full -mr-12 -mt-12 group-hover:scale-125 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-amber-500/30 group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2 tracking-tight">Berita Acara</h3>
                    <p class="text-slate-500 mb-8 text-sm leading-relaxed font-medium">
                        Dokumen legal formal pelaksanaan kegiatan pemilihan.
                    </p>
                    <a href="{{ route('admin.reports.ba') }}" target="_blank" class="inline-flex items-center gap-2 text-amber-600 font-bold hover:text-amber-800 transition-colors group/link">
                        <span>Download Dokumen</span>
                        <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Card 3: Audit Log -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-emerald-200/40 hover:shadow-emerald-300/40 transition-all duration-300 relative overflow-hidden group hover:-translate-y-1">
                <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-50 rounded-full -mr-12 -mt-12 group-hover:scale-125 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/30 group-hover:rotate-6 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2 tracking-tight">Log Audit Sistem</h3>
                    <p class="text-slate-500 mb-8 text-sm leading-relaxed font-medium">
                        Rekam jejak aktivitas sistem dan keamanan data.
                    </p>
                    <a href="{{ route('admin.reports.audit') }}" target="_blank" class="inline-flex items-center gap-2 text-emerald-600 font-bold hover:text-emerald-800 transition-colors group/link">
                        <span>Ekspor Log PDF</span>
                        <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

        </div>

        <!-- Download History Section -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Riwayat Download</h2>
                    <p class="text-slate-500 mt-1 text-sm font-medium">File PDF yang telah di-generate untuk download.</p>
                </div>
            </div>

            {{-- Data sudah difilter oleh ReportController: hanya results, audit, berita_acara --}}
            {{-- Surat Pemberitahuan (type=letters) TIDAK akan muncul di sini --}}
            @php $reportFiles = $generatedReports ?? collect(); @endphp

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
                @if($reportFiles->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-100">
                                    <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">Tipe Laporan</th>
                                    <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">Status</th>
                                    <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">Di-generate oleh</th>
                                    <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">Waktu</th>
                                    <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px] text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($reportFiles as $file)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm {{ $file->type === 'results' ? 'bg-indigo-50 text-indigo-600' : ($file->type === 'berita_acara' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-800 text-sm">
                                                        {{ $file->type === 'results' ? 'Laporan Hasil' : ($file->type === 'berita_acara' ? 'Berita Acara' : 'Log Audit') }}
                                                    </p>
                                                    <p class="text-[10px] font-mono text-slate-400 mt-0.5 truncate max-w-[150px]">{{ basename($file->path ?? '-') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            @if($file->status === 'completed')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Selesai
                                                </span>
                                            @elseif($file->status === 'pending')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide bg-amber-50 text-amber-600 ring-1 ring-amber-100">
                                                    <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    Proses
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide bg-red-50 text-red-600 ring-1 ring-red-100">
                                                    Gagal
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs ring-2 ring-white shadow-sm">
                                                    {{ substr($file->user->name ?? 'S', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-700">{{ $file->user->name ?? 'System' }}</p>
                                                    <p class="text-[10px] text-slate-500 font-medium">{{ $file->user->role ?? 'System' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex flex-col text-xs">
                                                <span class="font-bold text-slate-600">{{ $file->created_at->format('d M Y') }}</span>
                                                <span class="text-slate-400 font-mono">{{ $file->created_at->format('H:i') }} WIB</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                @if($file->status === 'completed' && $file->path && \Storage::disk($file->disk)->exists($file->path))
                                                    <a href="{{ route('admin.reports.download', $file) }}" 
                                                       class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Download">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                        </svg>
                                                    </a>
                                                @endif

                                                <form action="{{ route('admin.reports.destroy', $file) }}" method="POST" onsubmit="return confirm('Hapus riwayat file ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Hapus File">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($reportFiles, 'hasPages') && $reportFiles->hasPages())
                        <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
                            {{ $reportFiles->links() }}
                        </div>
                    @endif
                @else
                    <div class="py-20 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada riwayat</h3>
                        <p class="text-slate-500 text-sm max-w-sm mx-auto">Generate laporan baru untuk melihat riwayat download di sini.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-layouts.admin>
