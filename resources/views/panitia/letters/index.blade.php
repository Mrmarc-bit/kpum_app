@php
    $prodiList = \App\Models\Mahasiswa::PRODI_LIST;
@endphp

<x-layouts.panitia :title="$title ?? 'Unduh Surat Pemberitahuan'">
    <div class="relative min-h-[600px]">
        {{-- Background Accents --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl -z-10"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-4xl mx-auto py-8">
            {{-- Header Section --}}
            <div class="mb-12 text-center animate-fade-in">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-purple-50 text-purple-600 text-sm font-bold mb-4 border border-purple-100 shadow-sm">
                    <span class="flex w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    Panitia Dashboard
                </div>
                <h1 class="text-4xl font-black text-slate-800 tracking-tight leading-none mb-3">Unduh Surat Pemberitahuan</h1>
                <p class="text-slate-500 font-medium max-w-lg mx-auto">Generate & Download Surat Pemberitahuan Pemilihan (ZIP per Prodi) untuk kelancaran logistik.</p>
            </div>

            {{-- Main Form Card --}}
            <div class="relative mb-16 px-4">
                <div class="absolute inset-0 bg-white/40 backdrop-blur-xl rounded-[2.5rem] -z-10 shadow-2xl shadow-slate-200/50 border border-white/60"></div>
                <div class="p-8 sm:p-12">
                    <form method="GET" action="{{ route('panitia.dpt.download-batch-letters') }}" id="batch-download-form" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                            <div class="md:col-span-8 group">
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Program Studi</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-purple-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <select name="prodi" required
                                        class="w-full pl-12 pr-10 py-4 rounded-2xl border border-slate-200 bg-white/80 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition-all appearance-none font-bold text-slate-700 text-lg shadow-sm">
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach($prodiList as $prodi => $fakultas)
                                            @php
                                                $prodiClean = preg_replace('/\s*\([^)]*\)$/', '', $prodi);
                                            @endphp
                                            <option value="{{ $prodiClean }}">{{ $prodi }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-4 self-end">
                                <button type="submit" id="submit-btn"
                                    class="w-full py-4.5 bg-purple-600 hover:bg-purple-700 text-white font-black rounded-2xl shadow-xl shadow-purple-500/30 hover:-translate-y-1 active:scale-95 transition-all text-lg flex items-center justify-center gap-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"></path></svg>
                                    Generate ZIP
                                </button>
                            </div>
                        </div>

                        {{-- Alert Box --}}
                        <div class="p-6 bg-blue-500/5 rounded-3xl border border-blue-500/10 flex gap-5 items-start">
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div class="space-y-1">
                                <h4 class="font-black text-blue-900 leading-none">Batas Pemrosesan</h4>
                                <p class="text-sm text-blue-800/70 font-medium leading-relaxed">Maksimal 500 surat per pengunduhan. Proses dilakukan di background. Hasil ZIP akan muncul otomatis pada tabel di bawah.</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- History Section --}}
            <div class="px-4">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Riwayat Pengunduhan</h2>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left" id="history-table">
                            <thead>
                                <tr class="bg-slate-50/50 text-slate-400 font-black text-[10px] uppercase tracking-widest border-b border-slate-100">
                                    <th class="px-8 py-5">Details</th>
                                    <th class="px-8 py-5 text-center">Status & Progress</th>
                                    <th class="px-8 py-5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($history as $file)
                                    <tr class="group hover:bg-slate-50/50 transition-colors" data-job-id="{{ $file->id }}" data-status="{{ $file->status }}">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-purple-500 group-hover:border-purple-100 transition-all">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                                <div>
                                                    <span class="block font-black text-slate-700 group-hover:text-slate-900 transition-colors">{{ $file->details ?? 'Semua Prodi' }}</span>
                                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ $file->created_at->translatedFormat('d F Y • H:i') }} WIB</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="status-content">
                                                @if($file->status === 'completed')
                                                    <div class="flex flex-col items-center gap-1">
                                                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider shadow-sm shadow-emerald-200/50">Ready</span>
                                                        <span class="text-[10px] font-bold text-slate-400">100% Downloadable</span>
                                                    </div>
                                                @elseif($file->status === 'processing' || $file->status === 'pending')
                                                    <div class="w-full max-w-[150px] mx-auto space-y-2">
                                                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-wider">
                                                            <span class="text-purple-600 animate-pulse">{{ $file->status === 'pending' ? 'Waiting' : 'Processing' }}</span>
                                                            <span class="text-slate-400 progress-pct">{{ $file->progress }}%</span>
                                                        </div>
                                                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                                            <div class="h-full bg-purple-500 rounded-full transition-all duration-500 progress-bar shadow-[0_0_8px_rgba(147,51,234,0.5)]" style="width: {{ $file->progress }}%"></div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="flex flex-col items-center gap-1">
                                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-wider shadow-sm shadow-red-200/50">Failed</span>
                                                        <span class="text-[10px] font-bold text-red-400 max-w-[100px] truncate" title="{{ $file->error_message }}">System Error</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex justify-end items-center gap-2 action-content">
                                                @if($file->status === 'completed')
                                                    <a href="{{ route('panitia.reports.download', $file->id) }}" data-turbo="false" 
                                                        class="p-2.5 rounded-xl bg-slate-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm hover:shadow-lg hover:shadow-emerald-500/30" title="Download ZIP">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"></path></svg>
                                                    </a>
                                                @endif
                                                <button type="button" onclick="deleteHistory('{{ route('panitia.reports.destroy', $file->id) }}', this)" 
                                                    class="p-2.5 rounded-xl bg-slate-50 text-slate-400 hover:bg-red-500 hover:text-white transition-all shadow-sm hover:shadow-lg hover:shadow-red-500/30" title="Hapus Riwayat">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row">
                                        <td colspan="3" class="px-8 py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-20 h-20 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-200 mb-4 border-2 border-dashed border-slate-100">
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                </div>
                                                <h3 class="font-black text-slate-400 uppercase tracking-widest text-xs">Belum ada riwayat</h3>
                                                <p class="text-slate-300 text-sm font-medium mt-1">Silakan pilih Program Studi di atas.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($history->hasPages())
                        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                            {{ $history->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ensure no multiple declarations
        window.lettersRefreshInterval = window.lettersRefreshInterval || null;

        function startLettersPolling() {
            const active = document.querySelector('[data-status="processing"], [data-status="pending"]');
            if (active && !window.lettersRefreshInterval) {
                console.log('🔄 Bulk Letters (Panitia): Polling started');
                window.lettersRefreshInterval = setInterval(refreshHistoryTable, 3000);
            }
        }

        function stopLettersPolling() {
            if (window.lettersRefreshInterval) {
                console.log('⏸️ Bulk Letters (Panitia): Polling stopped');
                clearInterval(window.lettersRefreshInterval);
                window.lettersRefreshInterval = null;
            }
        }

        function refreshHistoryTable() {
            fetch(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTbody = doc.querySelector('tbody');
                const currentTbody = document.querySelector('tbody');
                
                if (newTbody && currentTbody) {
                    currentTbody.innerHTML = newTbody.innerHTML;
                    
                    // Check if we can stop
                    const stillActive = currentTbody.querySelector('[data-status="processing"], [data-status="pending"]');
                    if (!stillActive) {
                        stopLettersPolling();
                        if (typeof Toast !== 'undefined') {
                            Toast.fire({ icon: 'success', title: 'Generasi ZIP Selesai!' });
                        }
                    }
                }
            })
            .catch(err => {
                console.error('Refresh failing:', err);
                stopLettersPolling();
            });
        }

        // Global Delete function 
        window.deleteHistory = function(url, btn) {
            const row = btn.closest('tr');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Hapus riwayat download ini secara permanen?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                backdrop: 'rgba(15, 23, 42, 0.4)',
                customClass: {
                    popup: 'rounded-[2rem] border-none shadow-2xl',
                    confirmButton: 'rounded-xl font-black px-6 py-3',
                    cancelButton: 'rounded-xl font-black px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Visual feedback
                    if (row) row.style.opacity = '0.3';
                    
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        if (data.success) {
                            if (row) {
                                row.classList.add('animate-fadeOut');
                                setTimeout(() => {
                                    row.remove();
                                    // If empty, reload to show empty state
                                    if (!document.querySelector('tbody tr:not(.empty-row)')) {
                                        window.location.reload();
                                    }
                                }, 400);
                            }
                            if (typeof Toast !== 'undefined') {
                                Toast.fire({ icon: 'success', title: data.message });
                            }
                        } else {
                            if (row) row.style.opacity = '1';
                            Swal.fire('Error', data.message || 'Gagal menghapus.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        if (row) row.style.opacity = '1';
                        if (typeof Toast !== 'undefined') {
                            Toast.fire({ icon: 'error', title: 'Gagal menghubungi server' });
                        }
                    });
                }
            });
        };

        // Form Handler
        document.getElementById('batch-download-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const btn = document.getElementById('submit-btn');
            const originalHtml = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Proses...';
            
            const url = new URL(form.action);
            const params = new URLSearchParams(new FormData(form));
            
            fetch(`${url}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => {
                if (typeof Toast !== 'undefined') {
                    Toast.fire({ icon: 'info', title: 'Antrian dimulai!' });
                }
                form.reset();
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                refreshHistoryTable();
                startLettersPolling();
            })
            .catch(err => {
                console.error(err);
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
        });

        // Initialize polling on DOM Load & Turbo load
        document.addEventListener('DOMContentLoaded', startLettersPolling);
        document.addEventListener('turbo:load', startLettersPolling);
        document.addEventListener('turbo:before-cache', stopLettersPolling);

    </script>

    <style>
        .animate-fadeOut {
            animation: fadeOut 0.4s ease-out forwards;
        }
        @keyframes fadeOut {
            from { opacity: 0.3; transform: scale(1); }
            to { opacity: 0; transform: scale(0.95); }
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
        }
    </style>
</x-layouts.panitia>
