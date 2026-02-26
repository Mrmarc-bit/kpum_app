@php
    $prodiList = \App\Models\Mahasiswa::PRODI_LIST;
@endphp

<x-layouts.panitia :title="$title ?? 'Unduh Surat Pemberitahuan'">
    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">
        {{-- Header Section --}}
        <div class="mb-8 border-b border-gray-100 pb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Unduh Surat Pemberitahuan</h1>
                <p class="text-gray-500 text-sm font-medium">Buat dan unduh surat pemberitahuan pemilih format ZIP secara massal.</p>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            {{-- Form Section --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 sm:p-6">
                        <h2 class="text-base font-bold text-gray-800 mb-5 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </span>
                            Buat Permintaan
                        </h2>

                        <form method="POST" action="{{ route('panitia.dpt.download-batch-letters') }}" id="batch-download-form" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Pilih Program Studi <span class="text-red-500">*</span></label>
                                <select name="prodi" required class="w-full rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm text-gray-700 text-sm font-medium transition-all py-2.5">
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach($prodiList as $prodi => $fakultas)
                                        <option value="{{ $prodi }}">{{ $prodi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="rounded-lg bg-yellow-50 p-3.5 border border-yellow-100 mt-2">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <svg class="h-4 w-4 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="text-xs text-yellow-800 leading-relaxed font-medium">
                                        Proses pembuatan ZIP dilakukan di latar belakang. Anda dapat meninggalkan halaman ini dan memantau status di tabel riwayat.
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="submit-btn" class="w-full mt-4 flex justify-center items-center py-3 px-4 rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100 transition-all active:scale-[0.98]">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"></path></svg>
                                Generate ZIP
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- History Section --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                        <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <span class="text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Riwayat Generasi
                        </h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left" id="history-table">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-5 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">Detail Permintaan</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 text-center w-40">Status</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 text-right w-28">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($history as $file)
                                    <tr class="hover:bg-gray-50/50 transition-colors group" data-job-id="{{ $file->id }}" data-status="{{ $file->status }}">
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-blue-50/50 flex items-center justify-center text-blue-500 border border-blue-100/50">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-gray-800">{{ $file->details ?? 'Semua Prodi' }}</span>
                                                    <span class="text-xs font-medium text-gray-400">{{ $file->created_at->translatedFormat('d M Y • H:i') }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex justify-center status-content">
                                                @if($file->status === 'completed')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-green-50 text-green-600 border border-green-100">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        Selesai
                                                    </span>
                                                @elseif($file->status === 'processing' || $file->status === 'pending')
                                                    <div class="w-full">
                                                        <div class="flex justify-between items-center mb-1.5 text-[10px] font-bold tracking-wide">
                                                            <span class="text-blue-600 animate-pulse">{{ $file->status === 'pending' ? 'ANTRIAN' : 'PROSES' }}</span>
                                                            <span class="text-gray-500 progress-pct">{{ $file->progress }}%</span>
                                                        </div>
                                                        <div class="w-full bg-blue-50 rounded-full h-1.5 overflow-hidden">
                                                            <div class="bg-blue-500 h-1.5 rounded-full progress-bar transition-all duration-300" style="width: {{ $file->progress }}%"></div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="flex flex-col items-center">
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-600 border border-red-100 mb-1.5">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            Gagal
                                                        </span>
                                                        <button type="button" onclick="showErrorDetail('{{ htmlspecialchars($file->error_message ?: 'Terjadi kesalahan sistem yang tidak diketahui.', ENT_QUOTES, 'UTF-8') }}')" class="text-[10px] font-bold text-gray-500 hover:text-red-600 flex items-center gap-1 transition-colors bg-gray-50 hover:bg-red-50 px-2 py-0.5 rounded border border-gray-100">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            Lihat Error
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2 action-content">
                                                @if($file->status === 'completed')
                                                    <a href="{{ route('panitia.reports.download', $file->id) }}" data-turbo="false" class="text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 p-2 rounded-lg flex items-center justify-center transition-all border border-blue-100 hover:border-blue-600" title="Unduh File">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"></path></svg>
                                                    </a>
                                                @endif
                                                <button type="button" onclick="deleteHistory('{{ route('panitia.reports.destroy', $file->id) }}', this)" class="text-gray-400 hover:text-white bg-gray-50 hover:bg-red-500 p-2 rounded-lg transition-all border border-gray-100 hover:border-red-500" title="Hapus Riwayat">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row">
                                        <td colspan="3" class="px-5 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                </div>
                                                <span class="text-sm font-bold text-gray-500">Belum Ada Riwayat</span>
                                                <span class="text-xs text-gray-400 mt-1">Data unduhan belum tersedia</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($history->hasPages())
                        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                            {{ $history->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        window.lettersRefreshInterval = window.lettersRefreshInterval || null;

        function startLettersPolling() {
            const active = document.querySelector('[data-status="processing"], [data-status="pending"]');
            if (active && !window.lettersRefreshInterval) {
                window.lettersRefreshInterval = setInterval(refreshHistoryTable, 3000);
            }
        }

        function stopLettersPolling() {
            if (window.lettersRefreshInterval) {
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

                if (newTbody && currentTbody && currentTbody.innerHTML !== newTbody.innerHTML) {
                    currentTbody.innerHTML = newTbody.innerHTML;

                    const stillActive = currentTbody.querySelector('[data-status="processing"], [data-status="pending"]');
                    if (!stillActive) {
                        stopLettersPolling();
                        if (typeof window.Toast !== 'undefined') {
                            window.Toast.fire({ icon: 'success', title: 'Generasi ZIP Selesai!' });
                        }
                    }
                }
            })
            .catch(err => {
                console.error('Refresh failing:', err);
                stopLettersPolling();
            });
        }

        window.showErrorDetail = function(message) {
            Swal.fire({
                title: 'Detail Kesalahan',
                html: `<div class="bg-red-50 text-red-700 p-4 rounded-lg text-left text-sm font-mono whitespace-pre-wrap border border-red-100 overflow-x-auto">${message}</div>`,
                icon: 'error',
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'rounded-lg font-bold px-6 py-2.5 shadow-none'
                }
            });
        };

        window.deleteHistory = function(url, btn) {
            const row = btn.closest('tr');

            Swal.fire({
                title: 'Hapus Riwayat?',
                text: 'File ZIP (jika ada) akan ikut terhapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'Hapus',
                cancelButtonText: '<span class="text-gray-700">Batal</span>',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'rounded-lg font-bold px-6 py-2.5',
                    cancelButton: 'rounded-lg font-bold px-6 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (row) row.style.opacity = '0.5';

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
                                row.remove();
                                if (!document.querySelector('tbody tr:not(.empty-row)')) {
                                    window.location.reload();
                                }
                            }
                            if (typeof window.Toast !== 'undefined') {
                                window.Toast.fire({ icon: 'success', title: 'Berhasil dihapus' });
                            }
                        } else {
                            if (row) row.style.opacity = '1';
                            Swal.fire('Error', data.message || 'Gagal menghapus.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        if (row) row.style.opacity = '1';
                        if (typeof window.Toast !== 'undefined') {
                            window.Toast.fire({ icon: 'error', title: 'Gagal menghubungi server' });
                        }
                    });
                }
            });
        };

        function initLetterPage() {
            const form = document.getElementById('batch-download-form');
            if (form) {
                form.removeEventListener('submit', handleDownloadSubmit);
                form.addEventListener('submit', handleDownloadSubmit);
            }
            startLettersPolling();
        }

        function handleDownloadSubmit(e) {
            e.preventDefault();
            const form = this;
            const btn = document.getElementById('submit-btn');
            if (!btn) return;
            const originalHtml = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';

            const url = new URL(form.action);
            fetch(url.toString(), {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Server returned ' + res.status);
                if (typeof window.Toast !== 'undefined') {
                    window.Toast.fire({ icon: 'info', title: 'Antrian dimulai!' });
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
                Swal.fire({
                    title: 'Gagal Membuat Antrian',
                    text: 'Terjadi kesalahan saat menghubungi server. Pastikan Anda masih terlogin.',
                    icon: 'error'
                });
            });
        }

        document.addEventListener('DOMContentLoaded', initLetterPage);
        document.addEventListener('turbo:load', initLetterPage);
        document.addEventListener('turbo:before-cache', stopLettersPolling);
    </script>
</x-layouts.panitia>
