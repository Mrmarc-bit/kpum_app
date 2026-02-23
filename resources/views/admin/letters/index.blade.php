<x-layouts.admin :title="$title ?? 'Unduh Surat Pemberitahuan'">
    <div class="max-w-2xl mx-auto">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Unduh Surat Pemberitahuan</h1>
            <p class="text-slate-500 mt-2 font-medium">Generate & Download Surat Pemberitahuan Pemilihan (ZIP per Prodi).</p>
        </div>

        <form method="POST" action="{{ route('admin.dpt.download-batch-letters') }}" class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-100">
            @csrf
            
            <div class="mb-8 bg-amber-50 border border-amber-100 rounded-2xl p-5 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="text-sm text-amber-900">
                    <span class="font-bold block mb-1 text-base">Batasan Sistem</span>
                    <p class="leading-relaxed opacity-90">Untuk menjaga kinerja server, download dibatasi maksimal 500 surat per batch. Silakan pilih Program Studi untuk mengunduh surat secara bertahap.</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Program Studi</label>
                    <div class="relative">
                        <select name="prodi" required
                            class="w-full px-4 py-4 rounded-xl border border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all appearance-none font-bold text-slate-700 text-lg">
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodiList as $prodi => $fakultas)
                                {{-- Value harus sama persis dengan yang tersimpan di database --}}
                                <option value="{{ $prodi }}">{{ $prodi }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-xl shadow-emerald-500/30 hover:-translate-y-1 transition-all text-lg flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"></path>
                        </svg>
                        Download ZIP
                    </button>
                    <p class="text-center text-slate-400 text-sm mt-4 font-medium">Proses download mungkin memakan waktu beberapa saat.</p>
                </div>
            </div>
        </form>
    </div>

    <!-- History Table -->
    <div class="max-w-4xl mx-auto mt-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-800">Riwayat Download</h2>
            <button onclick="window.location.reload()" class="text-sm text-blue-600 font-bold hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh Status
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-xs font-bold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Waktu Request</th>
                            <th class="px-6 py-4" width="45%">Detail & Progres</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($history as $file)
                            <tr class="hover:bg-slate-50/50 transition-colors" data-status="{{ $file->status }}">
                                <td class="px-6 py-4">
                                    <span class="block font-bold text-slate-700">{{ $file->created_at->format('d M Y') }}</span>
                                    <span class="text-xs text-slate-400 font-mono">{{ $file->created_at->format('H:i') }} WIB</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="mb-2">
                                        <span class="font-bold text-slate-800 block">{{ $file->details ?? 'Semua Prodi' }}</span>
                                    </div>

                                    @if($file->status === 'completed')
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                Selesai (100%)
                                            </span>
                                        </div>
                                    @elseif($file->status === 'processing')
                                        <div class="w-full max-w-sm">
                                            <div class="flex justify-between text-xs mb-1">
                                                <span class="font-bold text-blue-600 animate-pulse">Sedang Memproses...</span>
                                                <span class="font-bold text-slate-600">{{ $file->progress }}%</span>
                                            </div>
                                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                                <div class="bg-blue-500 h-2.5 rounded-full transition-all duration-500" style="width: <?php echo $file->progress; ?>%;"></div>
                                            </div>
                                        </div>
                                    @elseif($file->status === 'failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800" title="{{ $file->error_message }}">
                                            Gagal
                                        </span>
                                        <p class="text-xs text-red-400 mt-1 max-w-xs truncate">{{ $file->error_message }}</p>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                            Menunggu Antrian...
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        @if($file->status === 'completed')
                                            <a href="{{ route('admin.reports.download', $file->id) }}" data-turbo="false" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition-colors shadow-sm shadow-emerald-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"></path></svg>
                                                Download
                                            </a>
                                        @endif

                                        <form action="{{ route('admin.reports.destroy', $file->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="delete-btn p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Riwayat">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    Belum ada riwayat download.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
    
    {{-- Real-Time Progress Tracker (Pure JavaScript) --}}
    <script>
        // Use 'var' or attach to window to avoid "Identifier 'refreshInterval' has already been declared" 
        // when Turbo Drive replaces the body.
        var refreshInterval = window.refreshInterval || null;
        
        // Auto-refresh progress every 1 second if there's processing job
        function startAutoRefresh() {
            const hasProcessing = document.querySelector('[data-status="processing"], [data-status="pending"]');
            
            if (hasProcessing) {
                if (!refreshInterval) {
                    console.log('🔄 Starting auto-refresh for real-time progress...');
                    refreshInterval = setInterval(refreshPage, 3000); // Refresh every 3 seconds
                }
            } else {
                stopAutoRefresh();
            }
        }
        
        function stopAutoRefresh() {
            if (refreshInterval) {
                console.log('⏸️ Stopping auto-refresh (no active jobs)');
                clearInterval(refreshInterval);
                refreshInterval = null;
            }
        }
        
        function refreshPage() {
            // Use AJAX to refresh only the history table (faster than full page reload)
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse the response and extract the table
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.querySelector('table');
                const currentTable = document.querySelector('table');
                
                if (newTable && currentTable) {
                    // Get scroll position before update
                    const scrollPos = window.scrollY;
                    
                    // Replace table content
                    currentTable.innerHTML = newTable.innerHTML;
                    
                    // Restore scroll position
                    window.scrollTo(0, scrollPos);
                    
                    // Check if still has processing jobs
                    const stillProcessing = document.querySelector('[data-status="processing"], [data-status="pending"]');
                    if (!stillProcessing) {
                        stopAutoRefresh();
                        
                        // Show completion notification
                        showNotification('✅ Download sudah siap!', 'success');
                    }
                }
            })
            .catch(error => {
                console.error('Failed to refresh:', error);
                stopAutoRefresh();
            });
        }
        
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-4 rounded-2xl shadow-2xl font-bold text-white z-50 animate-bounce ${
                type === 'success' ? 'bg-emerald-500' : 'bg-blue-500'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('opacity-0', 'transition-opacity');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Start monitoring on page load
        document.addEventListener('DOMContentLoaded', () => {
            startAutoRefresh();
        });
        
        // Enhanced form submission with instant AJAX feedback
        document.querySelector('form[action*="download-batch-letters"]').addEventListener('submit', function(e) {
            e.preventDefault(); // Stop Turbo and native browser form submission
            
            const form = this;
            const button = form.querySelector('button[type="submit"]');
            const originalContent = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mengirim...</span>
            `;
            
            let url = form.action;
            let options = {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            };
            
            if (form.method.toUpperCase() === 'GET') {
                const params = new URLSearchParams(new FormData(form)).toString();
                url = url + (url.includes('?') ? '&' : '?') + params;
                options.method = 'GET';
            } else {
                options.method = form.method.toUpperCase();
                options.body = new FormData(form);
            }

            fetch(url, options)
                .then(response => {
                    // Start auto-refresh and show success notification
                    showNotification('⚡ Proses antrian dimulai!', 'info');
                    
                    // Reset state
                    form.reset();
                    button.disabled = false;
                    button.innerHTML = originalContent;
                    
                    // Force an immediate fetch of the new row
                    refreshPage();
                    if(!refreshInterval) {
                        refreshInterval = setInterval(refreshPage, 3000);
                    }
                })
                .catch(error => {
                    showNotification('❌ Gagal mengirim request.', 'error');
                    button.disabled = false;
                    button.innerHTML = originalContent;
                });
        });
        
        // Modern Delete Confirmation with SweetAlert2
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');
                
                Swal.fire({
                    title: '🗑️ Hapus Riwayat?',
                    html: '<p class="text-slate-600">File download akan dihapus permanen dari sistem.</p>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '✓ Ya, Hapus!',
                    cancelButtonText: '✕ Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl',
                        title: 'text-2xl font-black text-slate-800',
                        htmlContainer: 'text-slate-600',
                        confirmButton: 'px-6 py-3 rounded-xl font-bold shadow-lg',
                        cancelButton: 'px-6 py-3 rounded-xl font-bold'
                    },
                    buttonsStyling: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading toast
                        Swal.fire({
                            title: 'Menghapus...',
                            html: 'Mohon tunggu sebentar',
                            icon: 'info',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit form
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-layouts.admin>
