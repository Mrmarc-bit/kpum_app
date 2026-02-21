<x-layouts.panitia :title="$title">
    <!-- Fullscreen Scanner Container -->
    <div class="fixed inset-0 z-[60] bg-black flex flex-col items-center justify-center overflow-hidden">

    <!-- Custom Style for Fullscreen Video & Animation -->
    <style>
        #reader { width: 100% !important; height: 100% !important; border: none !important; background: black; position: absolute; top: 0; left: 0; }
        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            position: absolute !important;
            top: 0; left: 0;
            z-index: 1;
        }
        /* Hide Default Library Overlays */
        #reader canvas, #reader img, #reader__scan_region { display: none !important; }

        /* Laser Animation */
        @keyframes scan-laser-full {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .animate-scan-laser-full {
             animation: scan-laser-full 3s linear infinite;
        }
    </style>

    <!-- Camera Viewport (Full Screen) -->
    <div id="reader" class="absolute inset-0 w-full h-full bg-black z-0"></div>

        <!-- Floating Controls (Replaces Navbar) -->
        <div class="absolute top-0 left-0 right-0 z-30 p-6 flex justify-between items-start">
            <!-- Back Button -->
            <button onclick="handleBack()" class="p-3 rounded-full bg-black/20 text-white backdrop-blur-md hover:bg-black/40 transition-all border border-white/10 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </button>

            <!-- Flash Toggle -->
            <button id="btn-flash" onclick="toggleFlash()" class="p-3 rounded-full bg-black/20 text-white backdrop-blur-md hover:bg-black/40 transition-all hidden border border-white/10 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </button>
        </div>

        <!-- Scanner Feedback -->
        <div class="absolute inset-0 z-10 pointer-events-none overflow-hidden opacity-30">
             <div class="absolute w-full h-[5px] bg-emerald-400 shadow-[0_0_20px_rgba(52,211,153,0.8)] animate-scan-laser-full"></div>
        </div>

        <!-- Start Button -->
        <div id="scanner-idle" class="absolute inset-0 z-20 flex items-center justify-center bg-slate-900">
            <button onclick="startScanner()" class="flex flex-col items-center gap-6 group">
                <div class="w-24 h-24 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-2xl shadow-emerald-500/50 group-hover:scale-110 transition-transform duration-300 animate-pulse">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                </div>
                <span class="text-white font-bold text-xl tracking-wide group-hover:text-emerald-400 transition-colors">Ketuk untuk Scan</span>
            </button>
        </div>

        <!-- Bottom Sheet -->
        <div id="bottom-sheet" class="absolute bottom-0 w-full bg-white rounded-t-[2rem] px-6 pt-2 pb-8 z-40 shadow-[0_-10px_40px_rgba(0,0,0,0.3)] transform transition-transform duration-500 translate-y-[calc(100%-40px)]">
            <div class="w-full py-4 cursor-pointer flex flex-col items-center gap-1" onclick="toggleBottomSheet()">
                <div class="w-12 h-1.5 bg-slate-300 rounded-full"></div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Opsi Lainnya</span>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-2">
                <button onclick="openManualInput()" class="group flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-slate-100 hover:border-slate-200 transition-all active:scale-95">
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-700">Input Kode Akses</span>
                </button>

                <button onclick="openCheckDpt()" class="group flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-slate-100 hover:border-slate-200 transition-all active:scale-95">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-700">Cek DPT</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loading-indicator" class="fixed inset-0 z-[90] hidden flex-col items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="w-16 h-16 relative">
            <div class="absolute inset-0 rounded-full border-4 border-slate-600"></div>
            <div class="absolute inset-0 rounded-full border-4 border-emerald-500 border-t-transparent animate-spin"></div>
        </div>
        <p class="mt-4 text-white font-bold text-lg tracking-wide animate-pulse">Memproses...</p>
    </div>

    <!-- Verification Result Modal -->
    <div id="result-modal" class="fixed inset-0 z-[100] hidden items-end justify-center sm:items-center p-4 sm:p-0" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeModalAndResume()"></div>
        <div id="modal-panel" class="relative bg-white rounded-[2rem] w-full max-w-sm overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0 m-auto mt-auto mb-4 sm:mb-auto">
             <div id="modal-header-bg" class="h-32 bg-emerald-500 flex items-center justify-center relative overflow-hidden transition-colors duration-300">
                <div class="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IndoaXRlIiBmaWxsLW9wYWNpdHk9IjEuMCIvPjwvc3ZnPg==')]"></div>
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg relative z-10">
                    <svg id="icon-success" class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <svg id="icon-error" class="h-10 w-10 text-red-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
             </div>
             <div class="p-6 text-center">
                <h3 id="modal-title" class="text-2xl font-black text-slate-800 tracking-tight">Verifikasi Sukses</h3>
                <p id="modal-subtitle" class="text-slate-500 text-sm font-medium mt-1 mb-6">Mahasiswa terdaftar dalam DPT.</p>
                <div id="details-block" class="bg-slate-50 rounded-2xl p-5 border border-slate-100 text-left space-y-4 mb-6">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Lengkap</span>
                        <p id="res-name" class="font-bold text-slate-800 text-lg leading-tight mt-1">Nama Mahasiswa</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NIM</span>
                            <p id="res-nim" class="font-mono text-slate-700 font-bold text-base mt-1">Wait...</p>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu</span>
                            <p id="res-time" class="text-blue-600 font-bold text-base mt-1">00:00</p>
                        </div>
                    </div>
                </div>
                <div id="error-block" class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-bold mb-6 hidden border border-red-100">
                    <p id="res-error-msg">Error details here</p>
                </div>
                <button onclick="closeModalAndResume()" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold shadow-lg shadow-slate-300 hover:bg-slate-800 active:scale-95 transition-all">
                    Lanjut Scan Berikutnya
                </button>
             </div>
        </div>
    </div>

    <!-- Confirmation Modal (Manual Input) -->
    <div id="confirm-modal" class="fixed inset-0 z-[110] hidden items-end justify-center sm:items-center p-4 sm:p-0" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeConfirmModal()"></div>
        <div class="relative bg-white rounded-[2rem] w-full max-w-sm overflow-hidden shadow-2xl m-auto">
             <div class="bg-amber-500 h-24 flex items-center justify-center">
                 <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg -mb-10 relative z-10">
                     <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                 </div>
             </div>
             <div class="p-6 pt-8 text-center">
                <h3 class="text-xl font-black text-slate-800 mt-2">Konfirmasi Validasi Manual</h3>
                <p class="text-slate-500 text-sm mt-1 mb-6">Pastikan data sesuai. Pemilih akan ditandai sebagai hadir offline.</p>
                <div class="bg-slate-50 rounded-xl p-4 text-left space-y-3 mb-6 border border-slate-200">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">Nama Pemilih</span>
                        <p id="conf-name" class="font-bold text-slate-800">Review Name</p>
                    </div>
                    <div class="flex justify-between">
                         <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">NIM</span>
                            <p id="conf-nim" class="font-mono font-bold text-slate-700">Review NIM</p>
                         </div>
                         <div class="text-right">
                             <span class="text-[10px] text-slate-400 font-bold uppercase">Prodi</span>
                             <p id="conf-prodi" class="font-bold text-slate-700">Review Prodi</p>
                         </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button onclick="closeConfirmModal()" class="flex-1 bg-slate-100 text-slate-600 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors">Batal</button>
                    <button onclick="submitManualValidation()" class="flex-1 bg-amber-500 text-white py-3 rounded-xl font-bold shadow-lg shadow-amber-200 hover:bg-amber-600 transition-colors">Validasi Masuk</button>
                </div>
             </div>
        </div>
    </div>

    <!-- Check DPT Modal (Internal) -->
    <div id="check-dpt-modal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-all" role="dialog" aria-modal="true">
        <div class="bg-white rounded-[2rem] w-full max-w-sm p-6 shadow-2xl transform transition-all scale-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-slate-800">Cek Status DPT</h3>
                <button onclick="closeCheckDpt()" class="p-2 hover:bg-slate-100 rounded-full transition-colors"><svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <div id="dpt-search-form">
                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Masukkan NIM</label>
                    <input type="text" id="check_dpt_nim" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono text-lg font-bold text-slate-800 placeholder-slate-300 text-center" placeholder="Nomor Induk Mahasiswa">
                </div>
                <button onclick="performCheckDpt()" class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">Cari Data</button>
            </div>

            <!-- DPT Result View -->
            <div id="dpt-search-result" class="hidden mt-4">
                 <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 mb-6">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white text-indigo-600 shadow-sm mb-2">
                           <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="font-bold text-indigo-900">Data Ditemukan</h4>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between border-b border-indigo-200 pb-2">
                            <span class="text-indigo-400 font-bold uppercase text-[10px]">Nama</span>
                            <span id="dpt-res-name" class="font-bold text-indigo-900 text-right">Name</span>
                        </div>
                        <div class="flex justify-between border-b border-indigo-200 pb-2">
                            <span class="text-indigo-400 font-bold uppercase text-[10px]">NIM</span>
                            <span id="dpt-res-nim" class="font-bold font-mono text-indigo-900">NIM</span>
                        </div>
                        <div class="flex justify-between border-b border-indigo-200 pb-2">
                            <span class="text-indigo-400 font-bold uppercase text-[10px]">Prodi</span>
                            <span id="dpt-res-prodi" class="font-bold text-indigo-900 text-right">Prodi</span>
                        </div>
                        <div class="flex justify-between pt-1">
                            <span class="text-indigo-400 font-bold uppercase text-[10px]">Status</span>
                            <span id="dpt-res-status" class="font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded text-[10px]">BELUM MEMILIH</span>
                        </div>
                    </div>
                 </div>
                 <button onclick="resetCheckDpt()" class="w-full bg-slate-100 text-slate-600 py-3 rounded-xl font-bold hover:bg-slate-200">Cari Lainnya</button>
            </div>

            <div id="dpt-not-found" class="hidden mt-4 text-center">
                <div class="bg-red-50 p-6 rounded-xl border border-red-100 mb-4">
                    <svg class="w-10 h-10 text-red-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="font-bold text-red-800">Data Tidak Ditemukan</p>
                    <p class="text-xs text-red-600 mt-1">NIM tersebut tidak terdaftar dalam DPT.</p>
                </div>
                <button onclick="resetCheckDpt()" class="w-full bg-slate-100 text-slate-600 py-3 rounded-xl font-bold hover:bg-slate-200">Coba Lagi</button>
            </div>
        </div>
    </div>

    <!-- Manual Input Modal -->
    <div id="manual-modal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-all" role="dialog" aria-modal="true">
        <div class="bg-white rounded-[2rem] w-full max-w-sm p-6 shadow-2xl transform transition-all scale-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-slate-800">Input Kode Akses</h3>
                <button onclick="closeManualInput()" class="p-2 hover:bg-slate-100 rounded-full transition-colors"><svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kode Akses Unik (FORMAT: K-MU-xxxx)</label>
                <div class="relative">
                    <input type="text" id="manual_access_code"
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono text-xl font-bold text-slate-800 placeholder-slate-300 text-center uppercase tracking-widest"
                           placeholder="K-MU-5503"
                           autocomplete="off"
                           autocapitalize="characters"
                           style="text-transform:uppercase">
                    <p class="text-[10px] text-slate-400 mt-2 text-center">Otomatis format: K-MU-XXXX</p>
                </div>
            </div>

            <button onclick="checkManualCode()" class="w-full bg-emerald-500 text-white py-4 rounded-xl font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-600 active:scale-95 transition-all">Cek Data</button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <audio id="sound-success" src="{{ asset('assets/audio/sukses.mp3') }}?v={{ time() }}"></audio>
    <audio id="sound-error" src="{{ asset('assets/audio/error.mp3') }}?v={{ time() }}"></audio>

    <script>
        // --- Input Masking for Access Code ---
        const accessInput = document.getElementById('manual_access_code');
        accessInput.addEventListener('input', function (e) {
            let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, ''); // Remove non-alphanumeric

            // Auto Format K-MU-XXXX
            // Logic:
            // 1st char (K)
            // -
            // 2nd-3rd (MU usually)
            // -
            // rest

            if (value.length > 0) {
                // Insert dash after 1st char
                if (value.length > 1) {
                    value = value.substring(0, 1) + '-' + value.substring(1);
                }
                // Insert dash after 4th char (1 char + 1 dash + 2 chars = index 4)
                if (value.length > 4) {
                    value = value.substring(0, 4) + '-' + value.substring(4);
                }
            }

            // Limit length if needed (e.g. K-MU-5503 is 9 chars)
            if (value.length > 9) value = value.substring(0, 9);

            e.target.value = value;
        });


        // --- DPT Check Logic ---
        function openCheckDpt() {
            const modal = document.getElementById('check-dpt-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => document.getElementById('check_dpt_nim').focus(), 100);
        }

        function closeCheckDpt() {
            const modal = document.getElementById('check-dpt-modal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            resetCheckDpt();
        }

        function resetCheckDpt() {
            document.getElementById('dpt-search-form').classList.remove('hidden');
            document.getElementById('dpt-search-result').classList.add('hidden');
            document.getElementById('dpt-not-found').classList.add('hidden');
            document.getElementById('check_dpt_nim').value = '';
            document.getElementById('check_dpt_nim').focus();
        }

        async function performCheckDpt() {
            const nim = document.getElementById('check_dpt_nim').value;
            if(!nim) return;

            document.getElementById('loading-indicator').classList.remove('hidden');

            try {
                const response = await fetch("{{ route('panitia.scanner.search-dpt') }}", {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                     },
                     body: JSON.stringify({ nim: nim })
                 });
                 const data = await response.json();
                 document.getElementById('loading-indicator').classList.add('hidden');

                 document.getElementById('dpt-search-form').classList.add('hidden');

                 if (response.ok && data.success) {
                     const m = data.data;
                     document.getElementById('dpt-res-name').innerText = m.name;
                     document.getElementById('dpt-res-nim').innerText = m.nim;
                     document.getElementById('dpt-res-prodi').innerText = m.prodi || '-';

                     const statusEl = document.getElementById('dpt-res-status');
                     if(m.status_voted) {
                         statusEl.innerText = "SUDAH MEMILIH";
                         statusEl.className = "font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded text-[10px]";
                     } else {
                         statusEl.innerText = "BELUM MEMILIH";
                         statusEl.className = "font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded text-[10px]";
                     }

                     document.getElementById('dpt-search-result').classList.remove('hidden');
                 } else {
                     document.getElementById('dpt-not-found').classList.remove('hidden');
                 }

            } catch(e) {
                document.getElementById('loading-indicator').classList.add('hidden');
            document.getElementById('loading-indicator').classList.remove('flex');
                alert("Gagal koneksi server.");
                resetCheckDpt();
            }
        }

        // --- Core Variables ---
        let html5QrCode;
        let isScanning = false;
        let isProcessing = false;
        let lastScannedCode = null;
        let warmupDone = false;

        // --- Existing Refs ---
        const elIdle = document.getElementById("scanner-idle");
        const elFlashBtn = document.getElementById('btn-flash');
        const elLoading = document.getElementById('loading-indicator');
        const elModal = document.getElementById('result-modal');
        const elPanel = document.getElementById('modal-panel');
        const elHeaderBg = document.getElementById('modal-header-bg');
        const elIconSuccess = document.getElementById('icon-success');
        const elIconError = document.getElementById('icon-error');
        const elTitle = document.getElementById('modal-title');
        const elSubtitle = document.getElementById('modal-subtitle');
        const elDetailsBlock = document.getElementById('details-block');
        const elErrorBlock = document.getElementById('error-block');

        const valName = document.getElementById('res-name');
        const valNim = document.getElementById('res-nim');
        const valTime = document.getElementById('res-time');
        const valError = document.getElementById('res-error-msg');

        const elConfirmModal = document.getElementById('confirm-modal');
        const confName = document.getElementById('conf-name');
        const confNim = document.getElementById('conf-nim');
        const confProdi = document.getElementById('conf-prodi');
        let currentManualCode = null;

        // Bottom Sheet Logic
        let isSheetOpen = false;
        function toggleBottomSheet() {
            const sheet = document.getElementById('bottom-sheet');
            if (isSheetOpen) {
                sheet.classList.add('translate-y-[calc(100%-40px)]');
                sheet.classList.remove('translate-y-0');
            } else {
                sheet.classList.remove('translate-y-[calc(100%-40px)]');
                sheet.classList.add('translate-y-0');
            }
            isSheetOpen = !isSheetOpen;
        }

        async function handleBack() {
            if (isScanning && html5QrCode) {
                await stopScanner();
            } else {
                window.location.href = "{{ route('panitia.dashboard') }}";
            }
        }

        async function startScanner() {
            if(isScanning) return;
            if(!html5QrCode) html5QrCode = new Html5Qrcode("reader");

            const config = {
                fps: 15,
                qrbox: function(viewfinderWidth, viewfinderHeight) {
                    let minEdgePercentage = 0.65;
                    let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                    let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                    return { width: qrboxSize, height: qrboxSize };
                },
                videoConstraints: { facingMode: "environment", width: { ideal: 1280 }, height: { ideal: 720 } }
            };
            const cameraConfig = { facingMode: "environment" };

            try {
                if (!warmupDone) {
                    await html5QrCode.start(cameraConfig, { fps: 10, qrbox: 200 }, () => {});
                    await new Promise(r => setTimeout(r, 200));
                    await html5QrCode.stop();
                    warmupDone = true;
                }
                await html5QrCode.start(cameraConfig, config, (decodedText) => {
                    if (!isProcessing) processVerification(decodedText);
                });
                isScanning = true;
                elIdle.classList.add('hidden');
                checkCapabilities();
                applyVideoEnhancements();
            } catch(err) {
                 alert("Gagal koneksi kamera: " + err);
                 isScanning = false;
            }
        }

        function applyVideoEnhancements() {
             const videoEl = document.querySelector('#reader video');
             if(videoEl) {
                 videoEl.style.transform = "scale(1)";
                 videoEl.style.filter = "contrast(1.1) brightness(1.05)";
             }
        }

        async function stopScanner() {
            if (html5QrCode) { try { await html5QrCode.stop(); } catch(e) {} }
            isScanning = false;
            isProcessing = false;
            lastScannedCode = null;
            elIdle.classList.remove('hidden');
            elLoading.classList.add('hidden');
            elLoading.classList.remove('flex');
            if(elFlashBtn) elFlashBtn.classList.add('hidden');
        }

        function checkCapabilities() {
            try {
                if(html5QrCode) {
                   const track = html5QrCode.getRunningTrackCameraCapabilities();
                   if (track && track.torchFeature && track.torchFeature.isSupported()) {
                       const btn = document.getElementById('btn-flash');
                       if(btn) btn.classList.remove('hidden');
                   }
                }
            } catch (e) {}
        }

        let isFlashOn = false;
        function toggleFlash() {
            if(html5QrCode) {
                isFlashOn = !isFlashOn;
                html5QrCode.applyVideoConstraints({ advanced: [{ torch: isFlashOn }] });
                const btn = document.getElementById('btn-flash');
                if(isFlashOn) {
                    btn.classList.add('bg-yellow-400', 'text-slate-900');
                    btn.classList.remove('bg-black/20', 'text-white');
                } else {
                    btn.classList.remove('bg-yellow-400', 'text-slate-900');
                    btn.classList.add('bg-black/20', 'text-white');
                }
            }
        }

        // --- MANUAL INPUT ---
        function openManualInput() {
            const modal = document.getElementById('manual-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => document.getElementById('manual_access_code').focus(), 100);
        }
        function closeManualInput() {
            const modal = document.getElementById('manual-modal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.getElementById('manual_access_code').value = '';
        }

        async function checkManualCode() {
            let code = document.getElementById('manual_access_code').value;
            // Remove dashes for backend verify if backend expects raw chars (Currently backend expects match to DB col)
            // Assuming DB stores formatted 'K-MU-5503' OR raw 'KMU5503'.
            // Usually strict match. I'll send as is, assuming user follows format.
            // If backend stores 'KMU5503' (raw), I should strip dashes.
            // Let's assume Access Code is stored AS IS. But usually user wants to type easy without dashes.
            // Since I force format with dashes on frontend, better send WITH dashes?
            // Actually, best practice: send RAW to backend? Or BE handles it.
            // Let's stick to sending exact string for now.
            // But wait, if user didn't type dashes but my formatter added them...

            if(!code) return alert('Masukkan Kode Akses!');
            closeManualInput();
            document.getElementById('loading-indicator').classList.remove('hidden');
            document.getElementById('loading-indicator').classList.add('flex');

            try {
                const response = await fetch("{{ route('panitia.scanner.check-access') }}", {
                     method: 'POST',
                     headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                     body: JSON.stringify({ access_code: code })
                 });
                 const data = await response.json();
                 document.getElementById('loading-indicator').classList.add('hidden');
            document.getElementById('loading-indicator').classList.remove('flex');

                 if (response.ok && data.success) {
                     if (data.mahasiswa.status_voted) {
                         // Show Modal Info: Already Voted
                         showError("Mahasiswa ini SUDAH MENGGUNAKAN Hak Pilihnya.", data.mahasiswa);
                     } else {
                         // Show Confirmation Modal for Validation
                         currentManualCode = code;
                         confName.innerText = data.mahasiswa.name;
                         confNim.innerText = data.mahasiswa.nim;
                         confProdi.innerText = data.mahasiswa.prodi || '-';
                         
                         elConfirmModal.classList.remove('hidden');
                         elConfirmModal.classList.add('flex');
                     }
                 } else {
                     showError(data.message || "Kode Akses tidak ditemukan.", null);
                 }
            } catch(e) {
                 document.getElementById('loading-indicator').classList.add('hidden');
            document.getElementById('loading-indicator').classList.remove('flex');
                 showError("Gagal: " + e.message);
            }
        }

        function closeConfirmModal() {
            elConfirmModal.classList.remove('flex');
            elConfirmModal.classList.add('hidden');
            currentManualCode = null;
        }

        async function submitManualValidation() {
            const codeToSend = currentManualCode;
            if(!codeToSend) return;
            
            closeConfirmModal();
            elLoading.classList.remove('hidden');
            elLoading.classList.add('flex');
            try {
                const response = await fetch("{{ route('panitia.scanner.verify-manual') }}", {
                     method: 'POST',
                     headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                     body: JSON.stringify({ access_code: codeToSend })
                 });
                 const data = await response.json();
                 elLoading.classList.add('hidden');
            elLoading.classList.remove('flex');
                 if (response.ok && data.success) showSuccess(data.mahasiswa);
                 else showError(data.message || "Gagal verify", data.mahasiswa);
            } catch(e) {
                 elLoading.classList.add('hidden');
            elLoading.classList.remove('flex');
                 showError("Error: " + e.message);
            }
        }

        async function processVerification(code) {
             if (isProcessing || code === lastScannedCode) return;
             isProcessing = true;
             lastScannedCode = code;
             elLoading.classList.remove('hidden');
            elLoading.classList.add('flex');
             try { document.getElementById('sound-success').play().catch(e=>{}); } catch(e){}
             if(html5QrCode) try { html5QrCode.pause(true); } catch(e) {}

             let hasResponded = false;
             const safetyTimeout = setTimeout(() => {
                 if(!hasResponded) { showError("Koneksi timeout."); hasResponded = true; }
             }, 8000);

             try {
                 const response = await fetch("{{ route('panitia.scanner.verify') }}", {
                     method: 'POST',
                     headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                     body: JSON.stringify({ qr_code: code })
                 });
                 clearTimeout(safetyTimeout);
                 elLoading.classList.add('hidden');
            elLoading.classList.remove('flex');
                 if(hasResponded) return;
                 hasResponded = true;
                 const data = await response.json();
                 if (response.ok && data.success) showSuccess(data.mahasiswa);
                 else showError(data.message, data.mahasiswa);
             } catch(e) {
                 clearTimeout(safetyTimeout);
                 elLoading.classList.add('hidden');
            elLoading.classList.remove('flex');
                 if(!hasResponded) { showError("Error: " + e.message); hasResponded = true; }
             }
        }

        function showSuccess(data) {
             elHeaderBg.classList.replace('bg-red-500', 'bg-emerald-500');
             elIconSuccess.classList.remove('hidden');
             elIconError.classList.add('hidden');
             elTitle.innerText = "Verifikasi Sukses";
             elSubtitle.innerText = "Mahasiswa Valid (Scanner/Manual).";
             valName.innerText = data ? data.name : '-';
             valNim.innerText = data ? data.nim : '-';
             valTime.innerText = data ? data.time : '-';
             elDetailsBlock.classList.remove('hidden');
             elErrorBlock.classList.add('hidden');
             toggleResultModal(true);
        }

        function showError(msg, data) {
             elHeaderBg.classList.replace('bg-emerald-500', 'bg-red-500');
             elIconSuccess.classList.add('hidden');
             elIconError.classList.remove('hidden');
             elTitle.innerText = "Akses Ditolak";
             elSubtitle.innerText = "Gagal Verifikasi.";
             valError.innerText = msg;
             if(data) {
                 valName.innerText = data.name;
                 valNim.innerText = data.nim;
                 valTime.innerText = data.time || '-';
                 elDetailsBlock.classList.remove('hidden');
             } else {
                 elDetailsBlock.classList.add('hidden');
             }
             elErrorBlock.classList.remove('hidden');
             document.getElementById('sound-error').play().catch(e=>{});
             toggleResultModal(true);
        }

        function toggleResultModal(show) {
            if(show) {
                elModal.classList.remove('hidden');
                elModal.classList.add('flex');
                setTimeout(() => {
                    elPanel.classList.remove('scale-95', 'opacity-0');
                    elPanel.classList.add('scale-100', 'opacity-100');
                }, 50);
            } else {
                elPanel.classList.replace('scale-100', 'scale-95');
                elPanel.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => {
                    elModal.classList.add('hidden');
                    elModal.classList.remove('flex');
                }, 300);
            }
        }

        function closeModalAndResume() {
            toggleResultModal(false);
            setTimeout(() => {
                isProcessing = false;
                lastScannedCode = null;
                applyVideoEnhancements();
                if(html5QrCode && isScanning) html5QrCode.resume();
            }, 300);
        }
    </script>
</x-layouts.panitia>
