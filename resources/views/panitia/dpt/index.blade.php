<x-layouts.panitia :title="$title">
    <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/50 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">DPT Mahasiswa</h3>
                <p class="text-slate-500 text-sm">Kelola data pemilih tetap untuk pemilihan.</p>
            </div>
            <div class="grid grid-cols-2 sm:flex gap-3">
                <form id="delete-all-form" action="{{ route('panitia.dpt.destroy-all') }}" method="POST" class="col-span-1">
                    @csrf @method('DELETE')
                    <button type="button" onclick="confirmDeleteAll()"
                        class="w-full px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 text-sm font-bold rounded-xl border border-red-100 shadow-sm transition-all flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">Hapus Semua</span>
                        <span class="sm:hidden">Hapus</span>
                    </button>
                </form>
                <button onclick="document.getElementById('import-dpt-modal').showModal()"
                    class="col-span-1 px-4 py-2 bg-white text-slate-700 hover:bg-slate-50 text-sm font-bold rounded-xl border border-slate-200 shadow-sm transition-all flex items-center justify-center gap-2 group">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Import Data
                </button>
                <button onclick="document.getElementById('add-dpt-modal').showModal()"
                    class="col-span-2 sm:col-span-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center justify-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Manual
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <form action="{{ route('panitia.dpt.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <!-- Search -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                    class="block w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm"
                    placeholder="Cari Nama atau NIM..." autofocus>
            </div>

            <!-- Status Filter Dropdown -->
            <div class="w-full md:w-auto">
                <select name="status" onchange="this.form.submit()"
                    class="block w-full md:w-auto pl-3 pr-8 py-2.5 text-sm border border-slate-200 rounded-xl bg-white text-slate-600 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm cursor-pointer hover:bg-slate-50">
                    <option value="">Semua Status</option>
                    <option value="sudah-milih" {{ request('status') == 'sudah-milih' ? 'selected' : '' }}>Sudah Memilih
                    </option>
                    <option value="belum-milih" {{ request('status') == 'belum-milih' ? 'selected' : '' }}>Belum Memilih
                    </option>
                </select>
            </div>
        </form>
    </div>

    <div id="dpt-table-container">
        @include('admin.dpt.table')
    </div>
    </div>

    <!-- Add DPT Modal -->
    <dialog id="add-dpt-modal"
        class="backdrop:bg-slate-900/40 backdrop:backdrop-blur-md bg-transparent p-0 w-full max-w-2xl rounded-2xl shadow-2xl">
        <form method="POST" action="{{ route('panitia.dpt.store') }}"
            class="bg-white rounded-2xl p-0 shadow-2xl border border-slate-100 overflow-hidden">
            @csrf

            <!-- Header -->
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white">
                <div>
                    <h3 class="font-bold text-xl text-slate-800">Tambah Mahasiswa</h3>
                    <p class="text-sm text-slate-500 mt-1">Input data DPT secara manual ke dalam sistem.</p>
                </div>
                <button type="button" onclick="document.getElementById('add-dpt-modal').close()"
                    class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-8 space-y-6">
                <!-- NIM & Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">NIM</label>
                        <input type="text" name="nim" required
                            class="w-full px-4 py-3 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all font-mono text-sm placeholder:text-slate-400"
                            placeholder="Contoh: 210101001">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Tanggal Lahir</label>
                        <input type="date" name="dob" required
                            class="w-full px-4 py-3 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-sm">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-sm placeholder:text-slate-400"
                        placeholder="Nama sesuai KTM">
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <div class="text-sm text-blue-900">
                        <span class="font-bold block mb-1">Password Otomatis</span>
                        Password default adalah tanggal lahir dengan format <span
                            class="font-mono bg-blue-100 px-1.5 py-0.5 rounded text-blue-800 font-bold text-xs">DDMMYYYY</span>.
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ 
                    prodiMap: {{ json_encode($prodiList) }},
                    selectedProdi: '',
                    fakultas: ''
                }" x-init="$watch('selectedProdi', value => fakultas = prodiMap[value] || '')">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Prodi</label>
                        <div class="relative">
                            <select name="prodi" x-model="selectedProdi" required
                                class="w-full px-4 py-3 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all text-sm appearance-none">
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodiList as $prodi => $fakultas)
                                    <option value="{{ $prodi }}">{{ $prodi }}</option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Fakultas</label>
                        <input type="text" name="fakultas" x-model="fakultas" readonly
                            class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-600 focus:border-slate-200 focus:ring-0 transition-all text-sm font-medium"
                            placeholder="Otomatis terisi">
                    </div>
                </div>
            </div>

            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('add-dpt-modal').close()"
                    class="px-6 py-2.5 rounded-xl text-slate-600 font-bold hover:bg-slate-200 transition-all text-sm">Batal</button>
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all text-sm flex items-center gap-2 transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </dialog>

    <!-- Import Modal (Realtime) -->
    <dialog id="import-dpt-modal"
        class="backdrop:bg-slate-900/40 backdrop:backdrop-blur-md bg-transparent p-0 w-full max-w-lg rounded-3xl shadow-2xl"
        x-data="{
            isUploading: false,
            progress: 0,
            statusText: 'Mengupload...',
            fileName: '',
            errorMessage: '',
            successMessage: '',
            pollInterval: null,
            
            async submitImport(e) {
                this.isUploading = true;
                this.progress = 0;
                this.errorMessage = '';
                this.successMessage = '';
                this.statusText = 'Mengupload data...';
                
                const formData = new FormData(e.target);
                
                try {
                    // 1. Submit File to start Batch
                    const startRes = await fetch('{{ route('panitia.dpt.import') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        }
                    });

                    if (!startRes.ok) {
                        const errorData = await startRes.json();
                        throw new Error(errorData.message || errorData.error || 'Gagal memulai import');
                    }
                    const startData = await startRes.json();
                    
                    if (startData.error) throw new Error(startData.error);
                    const batchId = startData.batchId;

                    // 2. Poll Batch Status
                    this.statusText = 'Memproses data...';
                    this.pollInterval = setInterval(async () => {
                        try {
                            const statusRes = await fetch(`/panitia/dpt/batch/${batchId}`);
                            const status = await statusRes.json();
                            
                            this.progress = status.progress;
                            
                            if (status.finished) {
                                clearInterval(this.pollInterval);
                                this.progress = 100;
                                this.statusText = 'Selesai!';

                                // Delay hiding progress bar to show 100%
                                setTimeout(() => {
                                    this.isUploading = false;
                                    this.successMessage = 'Import berhasil! Halaman akan dimuat ulang.';
                                    Toast.fire({ icon: 'success', title: 'Import Data Selesai' });
                                    setTimeout(() => window.location.reload(), 1500);
                                }, 800);
                            }
                        } catch (err) {
                            console.error(err);
                        }
                    }, 500);

                } catch (err) {
                    this.isUploading = false;
                    this.errorMessage = err.message || 'Terjadi kesalahan saat import.';
                    Toast.fire({ icon: 'error', title: this.errorMessage });
                }
            },

            resetImport() {
                if (this.pollInterval) clearInterval(this.pollInterval);
                this.isUploading = false;
                this.progress = 0;
                this.fileName = '';
                document.getElementById('import-dpt-modal').close();
            }
        }">
        <form @submit.prevent="submitImport"
            class="bg-white rounded-3xl p-0 shadow-2xl border border-white/50 ring-1 ring-black/5 mx-4 overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Import CSV Realtime</h3>
                        <p class="text-xs text-slate-500">Upload & pantau proses data secara langsung.</p>
                    </div>
                </div>
                <button type="button" @click="resetImport()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-8">
                <!-- Helper & Sample -->
                <div class="mb-6 bg-blue-50/50 rounded-xl p-4 border border-blue-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-bold mb-1">Format File CSV Baru</p>
                        <p class="opacity-80">Pastikan urutan kolom: <span
                                class="font-mono bg-blue-100 px-1 rounded">NIM, Nama, Email, Tanggal Lahir,
                                Prodi</span>.</p>
                        <a href="{{ route('panitia.dpt.download-sample') }}"
                            class="inline-flex items-center gap-1 text-blue-600 font-bold hover:underline mt-2">
                            Download Contoh CSV
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Dropzone Area -->
                <div x-show="!isUploading && !successMessage"
                    class="mt-6 border-2 border-dashed border-slate-300 rounded-2xl h-48 flex flex-col items-center justify-center text-center hover:border-blue-500 hover:bg-blue-50/30 transition-all cursor-pointer group relative overflow-hidden bg-slate-50/50">

                    <input type="file" name="file" accept=".csv, .txt" required
                        @change="fileName = $event.target.files[0].name"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                    <div
                        class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:border-blue-200 transition-all duration-300">
                        <svg class="w-8 h-8 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                    </div>

                    <div class="relative z-0 px-6">
                        <p class="font-bold text-slate-700 text-lg group-hover:text-blue-600 transition-colors"
                            x-text="fileName || 'Klik untuk memilih file CSV'"></p>
                        <p class="text-sm text-slate-400 mt-1" x-show="!fileName">atau tarik file langsung ke sini</p>
                    </div>

                    <!-- File Selected Indicator -->
                    <div x-show="fileName"
                        class="mt-2 inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold animate-fade-in">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        File Terpilih
                    </div>
                </div>

                <!-- Progress Bar UI -->
                <div x-show="isUploading" class="mt-8 space-y-4">
                    <div class="flex justify-between text-sm font-bold text-blue-600 animate-pulse">
                        <span x-text="statusText"></span>
                        <span x-text="progress + '%'"></span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-4 overflow-hidden border border-slate-300 relative">
                        <!-- Animated Progress Fill -->
                        <div class="h-full transition-all duration-300 ease-out relative"
                            :style="`width: ${progress}%; background-color: #2563eb;`">
                            <div class="absolute inset-0 bg-white/20 animate-[shimmer_2s_infinite]"
                                style="background-image: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.5) 50%, transparent 100%); background-size: 200% 100%;">
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-xs text-slate-400 animate-pulse">Mohon jangan tutup modal ini selama
                        proses berlangsung...</p>
                </div>

                <!-- Success Message -->
                <div x-show="successMessage" class="py-12 text-center">
                    <div
                        class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <h4 class="font-bold text-xl text-slate-800" x-text="successMessage"></h4>
                    <p class="text-slate-500 mt-2">Halaman akan dimuat ulang otomatis.</p>
                </div>
            </div>

            <div class="p-5 bg-slate-50 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" @click="resetImport()"
                    class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-200 hover:text-slate-700 transition-all text-sm">Batal</button>
                <button type="submit" :disabled="isUploading"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all text-sm flex items-center gap-2 disabled:bg-slate-400 disabled:shadow-none disabled:cursor-not-allowed">
                    <span x-show="isUploading"
                        class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                    <span x-text="isUploading ? 'Memproses...' : 'Import Sekarang'"></span>
                </button>
            </div>
        </form>
    </dialog>
    <script>
        // Real-time Search
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const tableContainer = document.getElementById('dpt-table-container');
            let debounceTimer;

            if (searchInput && tableContainer) {
                searchInput.addEventListener('input', function (e) {
                    clearTimeout(debounceTimer);

                    // Show loading state (optional)
                    tableContainer.classList.add('opacity-50', 'transition-opacity');

                    debounceTimer = setTimeout(() => {
                        const searchValue = e.target.value;
                        const url = new URL("{{ route('panitia.dpt.index') }}");
                        url.searchParams.set('search', searchValue);

                        // Preserve other filters (like status)
                        const currentUrl = new URL(window.location.href);
                        if (currentUrl.searchParams.has('status')) {
                            url.searchParams.set('status', currentUrl.searchParams.get('status'));
                        }

                        fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(response => response.text())
                            .then(html => {
                                tableContainer.innerHTML = html;
                            })
                            .catch(err => console.error(err))
                            .finally(() => {
                                tableContainer.classList.remove('opacity-50');
                                // Update URL without reload
                                window.history.pushState({}, '', url);
                            });
                    }, 300); // 300ms debounce
                });
            }
        });

        function confirmDeleteAll() {
            Swal.fire({
                title: 'Hapus Seluruh Data?',
                text: "Tindakan ini akan menghapus SEMUA data DPT dan tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-bold',
                    cancelButton: 'bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-bold ml-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-all-form').submit();
                }
            })
        }

        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target;

            Swal.fire({
                title: 'Hapus Mahasiswa?',
                text: "Data mahasiswa ini akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-bold',
                    cancelButton: 'bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-bold ml-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>


    <style>
        /* Fixed centering for dialog */
        dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin: 0;
            max-height: 90vh;
            overflow-y: auto;
        }

        dialog[open] {
            animation: scale-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            display: block;
            /* Ensure display block for animation to work if needed */
        }

        dialog::backdrop {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            opacity: 0;
            animation: fade-in 0.3s forwards;
        }

        @keyframes scale-in {
            from {
                opacity: 0;
                transform: translate(-50%, -48%) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes fade-in {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</x-layouts.panitia>