<x-layouts.admin :title="$title">
    <div class="max-w-4xl mx-auto">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Scanner USB - Manual Input</h1>
            <p class="text-slate-500 mt-2 font-medium">Input QR code menggunakan scanner USB barcode atau manual</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Input Column -->
            <div>
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-3xl p-8 shadow-xl">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/30">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Scan QR Code</h3>
                        <p class="text-sm text-slate-500 mt-2">Klik input di bawah, lalu scan dengan USB scanner</p>
                    </div>

                    <form onsubmit="verifyManualQR(event)" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">QR Code String:</label>
                            <textarea 
                                id="qr-input" 
                                rows="4" 
                                class="w-full px-4 py-3 border-2 border-blue-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm resize-none"
                                placeholder="Klik di sini dan scan dengan USB scanner..."
                                autofocus
                            ></textarea>
                            <p class="text-xs text-slate-400 mt-2">💡 Setelah scan, klik tombol "Verify QR Code"</p>
                        </div>

                        <button 
                            type="submit"
                            class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-blue-500/30 hover:shadow-2xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Verify QR Code
                        </button>

                        <button 
                            type="button"
                            onclick="clearInput()"
                            class="w-full py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all"
                        >
                            Clear
                        </button>
                    </form>
                </div>

                <!-- Instructions -->
                <div class="mt-6 bg-amber-50 border-2 border-amber-200 rounded-2xl p-5">
                    <p class="font-bold text-amber-800 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Cara Menggunakan Scanner USB:
                    </p>
                    <ol class="text-sm text-amber-900 space-y-2 ml-7 list-decimal">
                        <li>Hubungkan scanner USB ke komputer</li>
                        <li>Klik di kolom input QR Code di atas</li>
                        <li>Scan QR code dari surat pemberitahuan</li>
                        <li>String QR akan otomatis muncul di kolom</li>
                        <li>Klik tombol "Verify QR Code"</li>
                    </ol>
                </div>
            </div>

            <!-- Result Column -->
            <div>
                <!-- Result Card -->
                <div id="result-container" class="hidden bg-white rounded-3xl p-8 shadow-2xl border border-blue-100">
                    <div id="loading-overlay" class="hidden absolute inset-0 bg-white/90 backdrop-blur-sm z-20 flex-col items-center justify-center gap-4 rounded-3xl">
                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-slate-100 border-t-blue-600"></div>
                        <p class="font-bold text-slate-500 animate-pulse">Memverifikasi Data...</p>
                    </div>

                    <div class="text-center mb-8">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-blue-500/20">
                            <span id="mhs-initials" class="text-3xl font-black text-white">MH</span>
                        </div>
                        <h3 id="mhs-name" class="text-2xl font-black text-slate-800 leading-tight mb-1">Nama Mahasiswa</h3>
                        <p id="mhs-nim" class="text-lg text-slate-500 font-mono font-bold bg-slate-100 inline-block px-3 py-1 rounded-lg border border-slate-200">NIM: -</p>
                        <p id="mhs-prodi" class="text-sm text-slate-400 mt-3 font-medium">Prodi -</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Status Voting -->
                        <div class="bg-slate-50 rounded-2xl p-4 flex items-center justify-between border border-slate-100" id="card-voting">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-slate-600">Status Voting</span>
                            </div>
                            <span id="status-voting" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-200 text-slate-500">-</span>
                        </div>

                        <!-- Status Kehadiran -->
                        <div class="bg-slate-50 rounded-2xl p-4 flex items-center justify-between border border-slate-100" id="card-attendance">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-slate-600">Kehadiran</span>
                            </div>
                            <span id="status-attendance" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-200 text-slate-500">-</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 space-y-3">
                            <button id="btn-attendance" onclick="markAttendance()"
                                class="w-full py-4 bg-emerald-500 text-white rounded-2xl font-bold text-lg shadow-xl shadow-emerald-500/30 hover:bg-emerald-600 hover:-translate-y-0.5 transition-all disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Konfirmasi Kehadiran
                            </button>
                            
                            <button onclick="resetResult()"
                                class="w-full py-3 bg-white border-2 border-slate-100 text-slate-500 rounded-2xl font-bold hover:bg-slate-50 transition-all">
                                Scan Berikutnya
                            </button>
                        </div>
                    </div>
                    
                    <input type="hidden" id="current-mhs-id">
                </div>
                
                <!-- Placeholder -->
                <div id="placeholder-container" class="bg-slate-50 rounded-3xl p-10 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center min-h-[500px]">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl text-slate-400">Menunggu Input...</h3>
                    <p class="text-slate-400 mt-2 max-w-xs mx-auto leading-relaxed">Scan QR code dengan USB scanner atau input manual, lalu klik "Verify"</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const qrInput = document.getElementById('qr-input');
        const resultContainer = document.getElementById('result-container');
        const placeholderContainer = document.getElementById('placeholder-container');
        const loadingOverlay = document.getElementById('loading-overlay');
        
        // Auto-focus pada input saat halaman load
        qrInput.focus();

        function clearInput() {
            qrInput.value = '';
            qrInput.focus();
        }

        async function verifyManualQR(event) {
            event.preventDefault();
            
            const qrCode = qrInput.value.trim();
            
            if (!qrCode) {
                Swal.fire('Error', 'Mohon input QR code terlebih dahulu', 'warning');
                return;
            }

            console.log('Verifying QR Code:', qrCode.substring(0, 50) + '...');
            
            // Show result container with loading
            resultContainer.classList.remove('hidden');
            placeholderContainer.classList.add('hidden');
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');

            try {
                const response = await fetch("{{ route('admin.scanner.verify') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ qr_code: qrCode })
                });

                console.log('Response status:', response.status);
                
                const data = await response.json();
                console.log('Response data:', data);
                
                loadingOverlay.classList.add('hidden');
                loadingOverlay.classList.remove('flex');
                
                if (response.ok && data.valid) {
                    displayData(data);
                    clearInput(); // Clear input for next scan
                    Swal.fire({
                        icon: 'success',
                        title: 'Data Terverifikasi!',
                        timer: 1500,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                } else {
                    console.error('Verification failed:', data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Scan Gagal',
                        text: data.message || 'QR Code tidak valid',
                    });
                    resetResult();
                }
                
            } catch (err) {
                console.error('Error:', err);
                loadingOverlay.classList.add('hidden');
                loadingOverlay.classList.remove('flex');
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    html: `<p><strong>Error:</strong> ${err.message}</p><p><small>Pastikan Anda sudah login sebagai admin</small></p>`
                });
                resetResult();
            }
        }

        function displayData(data) {
            const mhs = data.mahasiswa;
            const status = data.status;
            
            document.getElementById('mhs-name').innerText = mhs.name;
            document.getElementById('mhs-nim').innerText = 'NIM: ' + mhs.nim;
            document.getElementById('mhs-prodi').innerText = (mhs.prodi || '-') + ' - ' + (mhs.fakultas || '-');
            
            const initials = mhs.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
            document.getElementById('mhs-initials').innerText = initials;
            
            document.getElementById('current-mhs-id').value = mhs.id;
            
            const statusVoting = document.getElementById('status-voting');
            const cardVoting = document.getElementById('card-voting');
            const statusAttendance = document.getElementById('status-attendance');
            const cardAttendance = document.getElementById('card-attendance');
            const btnAttendance = document.getElementById('btn-attendance');
            
            // Voting Status
            if (status.has_voted) {
                statusVoting.innerText = 'SUDAH MEMILIH';
                statusVoting.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-green-100 text-green-700';
                cardVoting.classList.add('bg-green-50/50', 'border-green-100');
            } else {
                statusVoting.innerText = 'BELUM MEMILIH';
                statusVoting.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-100 text-amber-700';
                cardVoting.classList.remove('bg-green-50/50', 'border-green-100');
            }
            
            // Attendance Status
            if (status.has_attended) {
                statusAttendance.innerText = 'SUDAH HADIR (' + (status.attended_at || '-') + ')';
                statusAttendance.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-100 text-blue-700';
                cardAttendance.classList.add('bg-blue-50/50', 'border-blue-100');
                btnAttendance.disabled = true;
                btnAttendance.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Sudah Tercatat';
            } else {
                statusAttendance.innerText = 'BELUM HADIR';
                statusAttendance.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-200 text-slate-500';
                cardAttendance.classList.remove('bg-blue-50/50', 'border-blue-100');
                btnAttendance.disabled = false;
                btnAttendance.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Konfirmasi Kehadiran';
            }
        }

        async function markAttendance() {
            const id = document.getElementById('current-mhs-id').value;
            if (!id) return;
            
            const btnAttendance = document.getElementById('btn-attendance');
            const originalHTML = btnAttendance.innerHTML;
            btnAttendance.disabled = true;
            btnAttendance.innerText = 'Menyimpan...';
            
            try {
                const response = await fetch("{{ route('admin.scanner.check-in') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ mahasiswa_id: id })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    Swal.fire('Berhasil!', data.message, 'success');
                    
                    const statusAttendance = document.getElementById('status-attendance');
                    const cardAttendance = document.getElementById('card-attendance');
                    statusAttendance.innerText = 'SUDAH HADIR (' + data.time + ')';
                    statusAttendance.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-100 text-blue-700';
                    cardAttendance.classList.add('bg-blue-50/50', 'border-blue-100');
                    btnAttendance.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Sudah Tercatat';
                } else {
                    Swal.fire('Gagal', data.message || 'Gagal menyimpan', 'error');
                    btnAttendance.disabled = false;
                    btnAttendance.innerHTML = originalHTML;
                }
                
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan jaringan', 'error');
                btnAttendance.disabled = false;
                btnAttendance.innerHTML = originalHTML;
            }
        }

        function resetResult() {
            resultContainer.classList.add('hidden');
            placeholderContainer.classList.remove('hidden');
            document.getElementById('current-mhs-id').value = '';
            clearInput();
        }
    </script>
</x-layouts.admin>
