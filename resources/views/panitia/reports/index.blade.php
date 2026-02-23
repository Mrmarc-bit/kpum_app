<x-layouts.panitia title="Pusat Laporan">
    <div class="space-y-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-800">📄 Pusat Laporan</h1>
                <p class="text-slate-500 mt-2">Unduh dokumen pelaksanaan pemilihan umum.</p>
            </div>
            <div class="text-sm text-slate-400 font-mono">
                {{ now()->translatedFormat('l, d F Y H:i') }}
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card 1: Hasil Pemilihan -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-indigo-100/50 hover:shadow-indigo-200/50 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Laporan Hasil Pemilihan</h3>
                    <p class="text-slate-500 mb-6 text-sm leading-relaxed">
                        Dokumen lengkap rekapitulasi suara untuk Presma dan DPM.
                    </p>
                    <a href="{{ route('panitia.reports.results') }}" target="_blank" class="flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-800 transition-colors">
                        <span>Unduh PDF Resmi</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Card 2: Berita Acara -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-amber-100/50 hover:shadow-amber-200/50 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-amber-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Berita Acara</h3>
                    <p class="text-slate-500 mb-6 text-sm leading-relaxed">
                        Dokumen legal formal pelaksanaan kegiatan pemilihan.
                    </p>
                    <a href="{{ route('panitia.reports.ba') }}" target="_blank" class="flex items-center gap-2 text-amber-600 font-bold hover:text-amber-800 transition-colors">
                        <span>Download Dokumen</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Card 3: Audit Log -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-emerald-100/50 hover:shadow-emerald-200/50 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-900 mb-2">Log Audit Sistem</h3>
                    <p class="text-slate-600 mb-6 text-sm leading-relaxed">
                        Rekam jejak aktivitas sistem.
                    </p>
                    <a href="{{ route('panitia.reports.audit') }}" target="_blank" class="flex items-center gap-2 text-emerald-600 font-bold hover:text-emerald-800 transition-colors">
                        <span>Ekspor Log PDF</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

        </div>

        <!-- Download History Section -->
        <div class="mt-12">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-800">📥 Riwayat Download Berkas</h2>
                    <p class="text-slate-500 mt-1 text-sm">File PDF yang telah di-generate untuk download.</p>
                </div>
            </div>

            {{-- Data sudah difilter oleh ReportController: hanya results, audit, berita_acara --}}
            {{-- Surat Pemberitahuan (type=letters) TIDAK akan muncul di sini --}}
            @php $reportFiles = $generatedReports ?? collect(); @endphp

            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                @if($reportFiles->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Tipe Laporan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Di-generate oleh</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($reportFiles as $file)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $file->type === 'results' ? 'bg-indigo-100 text-indigo-600' : ($file->type === 'berita_acara' ? 'bg-amber-100 text-amber-600' : 'bg-cyan-100 text-cyan-600') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-900">
                                                        {{ $file->type === 'results' ? 'Laporan Hasil Pemilihan' : ($file->type === 'berita_acara' ? 'Berita Acara' : 'Log Audit Sistem') }}
                                                    </p>
                                                    <p class="text-xs text-slate-500">{{ basename($file->path ?? '-') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($file->status === 'completed')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-700">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Selesai
                                                </span>
                                            @elseif($file->status === 'pending')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-yellow-100 text-yellow-700">
                                                    <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Proses
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-700">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Gagal
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w size-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-xs">
                                                    {{ substr($file->user->name ?? 'S', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-slate-700">{{ $file->user->name ?? 'System' }}</p>
                                                    <p class="text-xs text-slate-500">{{ $file->user->role ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-slate-700">{{ $file->created_at->format('d M Y') }}</p>
                                            <p class="text-xs text-slate-500">{{ $file->created_at->format('H:i') }} WIB</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                @if($file->status === 'completed' && $file->path && \Storage::disk($file->disk)->exists($file->path))
                                                    <a href="{{ route('panitia.reports.download', $file) }}" 
                                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold rounded-lg text-xs transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                        </svg>
                                                        Download
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400 italic">-</span>
                                                @endif

                                                <form action="{{ route('panitia.reports.destroy', $file) }}" method="POST" data-confirm="Hapus riwayat file ini?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus File">
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
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                            {{ $reportFiles->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">Belum ada riwayat download.</p>
                        <p class="text-slate-400 text-sm mt-1">Buat laporan PDF untuk melihat riwayat di sini.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-layouts.panitia>
