<x-layouts.admin title="Pengaturan Sistem">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Pengaturan Sistem</h1>
                <p class="text-slate-500 mt-1">Kelola konfigurasi global website KPUM.</p>
            </div>

            <button type="submit" form="settings-form"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Perubahan
            </button>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- GRID CONTAINER FOR ALL SETTINGS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- FORM 1: GENERAL SETTINGS -->
            <form id="settings-form" method="POST" action="{{ route('admin.settings.update') }}"
                enctype="multipart/form-data" data-turbo="false" class="md:col-span-3 contents">
            @csrf

            <!-- 1. Identitas Website (Span 2 Columns) -->
            <div
                class="md:col-span-2 bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group">

                <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </span>
                    Identitas & Branding
                </h2>

                <div class="grid md:grid-cols-2 gap-6 relative z-10">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Platform</label>
                            <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'KPUM System' }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-bold text-slate-800"
                                placeholder="Nama Website">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Website</label>
                            <textarea name="app_description" rows="3"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium text-slate-800 resize-none"
                                placeholder="Deskripsi singkat tentang platform pemilihan ini...">{{ $settings['app_description'] ?? '' }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Contact Person (WhatsApp)</label>
                                <input type="text" name="contact_person" value="{{ $settings['contact_person'] ?? '' }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium text-slate-800"
                                    placeholder="6281234567890">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Email KPUM</label>
                                <input type="email" name="email_kpum" value="{{ $settings['email_kpum'] ?? '' }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium text-slate-800"
                                    placeholder="kpum@unugha.ac.id">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Sekretariat</label>
                            <textarea name="address" rows="3"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium text-slate-800 resize-none"
                                placeholder="Alamat lengkap sekretariat KPUM...">{{ $settings['address'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div x-data="{ 
                        photoName: null, 
                        photoPreview: {{ isset($settings['app_logo']) && $settings['app_logo'] ? "'" . asset((string) $settings['app_logo']) . "'" : 'null' }} 
                    }" class="col-span-1">
                        <!-- File Input (Hidden) -->
                        <input type="file" name="app_logo" class="hidden" x-ref="photo" accept="image/*" x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                            ">

                        <label class="block text-sm font-bold text-slate-700 mb-2">Logo Website</label>

                        <!-- 1. EMPTY STATE (Dashed Upload Zone) -->
                        <div class="w-full h-40 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-slate-100 hover:border-indigo-500 transition-all cursor-pointer flex flex-col items-center justify-center gap-2 group"
                            x-show="!photoPreview" @click="$refs.photo.click()">
                            <div
                                class="w-12 h-12 rounded-full bg-white text-slate-400 group-hover:text-indigo-600 shadow-sm flex items-center justify-center transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p
                                    class="text-sm font-bold text-slate-600 group-hover:text-indigo-600 transition-colors">
                                    Klik untuk Upload</p>
                                <p class="text-xs text-slate-400">PNG / JPG (Max 2MB)</p>
                                @error('app_logo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- 2. PREVIEW STATE (Image Card) -->
                        <div class="relative w-40 h-40 rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden group"
                            x-show="photoPreview" style="display: none;">

                            <!-- Pattern Background -->
                            <div
                                class="absolute inset-0 opacity-[0.03] bg-[radial-gradient(#4f46e5_1px,transparent_1px)] [background-size:16px_16px]">
                            </div>

                            <!-- Image -->
                            <img :src="photoPreview" class="absolute inset-0 w-full h-full object-contain p-4 z-10">

                            <!-- Action Overlay -->
                            <div
                                class="absolute inset-x-0 bottom-0 p-4 z-20 opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0">
                                <button type="button"
                                    class="w-full bg-slate-900/80 backdrop-blur-md text-white text-xs font-bold py-2.5 rounded-xl hover:bg-slate-900 transition-colors flex items-center justify-center gap-2 shadow-lg"
                                    @click="$refs.photo.click()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Ganti Logo
                                </button>
                            </div>

                            <!-- Reset Button (Optional, positioned top-right) -->
                            <button type="button" @click="photoPreview = null; $refs.photo.value = null"
                                class="absolute top-2 right-2 z-30 w-8 h-8 bg-white/50 hover:bg-white text-slate-500 hover:text-red-500 rounded-full flex items-center justify-center backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100"
                                title="Hapus Logo">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>





            <!-- 3. Waktu Pemilihan (Small Card) -->
            <div
                class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-xl shadow-blue-500/30 relative overflow-hidden">
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>

                <h3 class="text-lg font-bold mb-1 opacity-90">Jadwal Pemilihan</h3>
                <p class="text-blue-100 text-xs mb-6">Waktu mulai countdown otomatis.</p>

                <div class="mb-4 pt-4 border-t border-white/20">
                    <h4 class="text-sm font-bold text-yellow-300 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        Mode Simulasi (Uji Coba)
                    </h4>
                    
                    <label class="block mb-4">
                        <span class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1 block">Mulai Simulasi</span>
                        <input type="datetime-local" name="simulation_start_time"
                            value="{{ $settings['simulation_start_time'] ?? '' }}"
                            class="w-full px-4 py-3 bg-yellow-400/10 border border-yellow-400/30 rounded-xl text-white placeholder-white/50 focus:ring-2 focus:ring-yellow-400/50 focus:bg-white/20 transition-all font-mono text-sm scheme-dark">
                    </label>

                    <label class="block mb-4">
                        <span class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1 block">Selesai Simulasi</span>
                        <input type="datetime-local" name="simulation_end_time"
                            value="{{ $settings['simulation_end_time'] ?? '' }}"
                            class="w-full px-4 py-3 bg-yellow-400/10 border border-yellow-400/30 rounded-xl text-white placeholder-white/50 focus:ring-2 focus:ring-yellow-400/50 focus:bg-white/20 transition-all font-mono text-sm scheme-dark">
                    </label>
                </div>

                <div class="pt-4 border-t border-white/20">
                    <h4 class="text-sm font-bold text-white mb-3">Waktu Pemilihan Resmi</h4>
                    <label class="block mb-4">
                        <span class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1 block">Waktu Mulai</span>
                        <input type="datetime-local" name="voting_start_time"
                            value="{{ $settings['voting_start_time'] ?? '' }}"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-2 focus:ring-white/50 focus:bg-white/20 transition-all font-mono text-sm scheme-dark">
                    </label>

                    <label class="block">
                        <span class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1 block">Waktu Selesai (Tutup Bilik)</span>
                        <input type="datetime-local" name="voting_end_time"
                            value="{{ $settings['voting_end_time'] ?? '' }}"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:ring-2 focus:ring-white/50 focus:bg-white/20 transition-all font-mono text-sm scheme-dark">
                    </label>
                </div>
            </div>

            <!-- 3. Feature Flags (Toggles Replaced by Status Cards) -->
            <!-- ROW 2: FEATURE FLAGS (Quick Count & Candidates) -->
            <!-- Wrapped in col-span-3 to take full width, then split into 2 cols -->
            <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Quick Count -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-lg relative group">
                    <div class="flex justify-between items-start mb-4">
                        <div
                            class="w-12 h-12 rounded-2xl {{ ($settings['enable_quick_count'] ?? 'false') === 'true' ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2">
                            <span
                                class="w-2 h-2 rounded-full {{ ($settings['enable_quick_count'] ?? 'false') === 'true' ? 'bg-green-500 animate-pulse' : 'bg-slate-300' }}"></span>
                            <span
                                class="text-xs font-bold uppercase {{ ($settings['enable_quick_count'] ?? 'false') === 'true' ? 'text-green-600' : 'text-slate-400' }}">
                                {{ ($settings['enable_quick_count'] ?? 'false') === 'true' ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Quick Count</h3>
                    <p class="text-slate-500 text-sm mb-6 h-10">Tampilkan hasil real-time ke publik.</p>

                    <!-- Hidden Checkbox for Form Submission Logic -->
                    <input type="checkbox" name="enable_quick_count" id="qc_checkbox" value="true" class="hidden" {{ ($settings['enable_quick_count'] ?? 'false') === 'true' ? 'checked' : '' }}>

                    <!-- Custom Button UI -->
                    <button type="button"
                        onclick="document.getElementById('qc_checkbox').click(); this.closest('form').submit()" class="w-full py-3 rounded-xl font-bold text-sm transition-all transform active:scale-95 flex items-center justify-center gap-2
                        {{ ($settings['enable_quick_count'] ?? 'false') === 'true'
        ? 'bg-red-50 text-red-600 hover:bg-red-100 border border-red-200'
        : 'bg-slate-900 text-white hover:bg-slate-800 shadow-lg shadow-slate-900/20' }}">
                        {{ ($settings['enable_quick_count'] ?? 'false') === 'true' ? 'Matikan Fitur' : 'Nyalakan Fitur' }}
                    </button>
                </div>

                <!-- Show Candidates -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-lg relative group">
                    <div class="flex justify-between items-start mb-4">
                        <div
                            class="w-12 h-12 rounded-2xl {{ ($settings['show_candidates'] ?? 'false') === 'true' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2">
                            <span
                                class="w-2 h-2 rounded-full {{ ($settings['show_candidates'] ?? 'false') === 'true' ? 'bg-blue-500 animate-pulse' : 'bg-slate-300' }}"></span>
                            <span
                                class="text-xs font-bold uppercase {{ ($settings['show_candidates'] ?? 'false') === 'true' ? 'text-blue-600' : 'text-slate-400' }}">
                                {{ ($settings['show_candidates'] ?? 'false') === 'true' ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Daftar Kandidat</h3>
                    <p class="text-slate-500 text-sm mb-6 h-10">Tampilkan profil kandidat di beranda.</p>

                    <!-- Hidden Checkbox -->
                    <input type="checkbox" name="show_candidates" id="cand_checkbox" value="true" class="hidden" {{ ($settings['show_candidates'] ?? 'false') === 'true' ? 'checked' : '' }}>

                    <!-- Custom Button UI -->
                    <button type="button"
                        onclick="document.getElementById('cand_checkbox').click(); this.closest('form').submit()" class="w-full py-3 rounded-xl font-bold text-sm transition-all transform active:scale-95 flex items-center justify-center gap-2
                        {{ ($settings['show_candidates'] ?? 'false') === 'true'
        ? 'bg-red-50 text-red-600 hover:bg-red-100 border border-red-200'
        : 'bg-slate-900 text-white hover:bg-slate-800 shadow-lg shadow-slate-900/20' }}">
                        {{ ($settings['show_candidates'] ?? 'false') === 'true' ? 'Sembunyikan' : 'Tampilkan' }}
                    </button>
                </div>

            </div>
        </form>



            <!-- ENCRYPTION MANAGEMENT LINK CARD -->
        <a href="{{ route('admin.security.encryption') }}" 
            class="md:col-span-3 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-indigo-500/30 hover:shadow-3xl transition-all transform hover:-translate-y-1 block group">
            
            <!-- Decorative Patterns -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0"
                    style="background-image: radial-gradient(circle at 25px 25px, white 2%, transparent 0%), radial-gradient(circle at 75px 75px, white 2%, transparent 0%); background-size: 100px 100px;">
                </div>
            </div>

            <!-- Lock Icon Backdrop -->
            <div class="absolute top-0 right-0 p-8 opacity-5">
                <svg class="w-64 h-64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            </div>

            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black mb-2 flex items-center gap-3">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                        Manajemen Enkripsi Vote
                    </h2>
                    <p class="text-indigo-100 max-w-2xl mb-4">
                        Kontrol tingkat keamanan dan enkripsi data voting mahasiswa. Pilih antara Standard, High, atau Blockchain mode.
                    </p>
                    
                    @php
                        $currentLevel = \App\Services\VoteEncryptionService::getCurrentLevel();
                        $levelLabels = [
                            'standard' => '🔒 Standard (AES-256)',
                            'high' => '🔐 High (End-to-End)',
                            'blockchain' => '⛓️ Blockchain Mode'
                        ];
                        $levelColors = [
                            'standard' => 'bg-green-500',
                            'high' => 'bg-purple-500',
                            'blockchain' => 'bg-orange-500'
                        ];
                    @endphp
                    
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 rounded-lg">
                        <div class="w-3 h-3 rounded-full {{ $levelColors[$currentLevel] ?? 'bg-slate-500' }} animate-pulse"></div>
                        <span class="text-sm font-bold">Mode Aktif: {{ $levelLabels[$currentLevel] ?? 'Unknown' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold">Kelola →</span>
                    <svg class="w-8 h-8 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- MAINTENANCE MODE LINK CARD -->
        <a href="{{ route('admin.settings.maintenance') }}" 
            class="md:col-span-3 bg-gradient-to-br from-orange-500 to-red-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-orange-500/30 hover:shadow-3xl transition-all transform hover:-translate-y-1 block group">
            
            <!-- Decorative Patterns -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0"
                    style="background-image: radial-gradient(circle at 25px 25px, white 2%, transparent 0%), radial-gradient(circle at 75px 75px, white 2%, transparent 0%); background-size: 100px 100px;">
                </div>
            </div>

            <!-- Warning Icon Backdrop -->
            <div class="absolute top-0 right-0 p-8 opacity-5">
                <svg class="w-64 h-64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>

            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black mb-2 flex items-center gap-3">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Mode Maintenance
                    </h2>
                    <p class="text-orange-100 max-w-2xl mb-4">
                        Kontrol akses website untuk pemeliharaan sistem. Aktifkan/nonaktifkan maintenance mode.
                    </p>
                    
                    @php
                        $isMaintenanceActive = \App\Models\Setting::isMaintenanceMode();
                    @endphp
                    
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 rounded-lg">
                        <div class="w-3 h-3 rounded-full {{ $isMaintenanceActive ? 'bg-red-500 animate-pulse' : 'bg-green-500' }}"></div>
                        <span class="text-sm font-bold">Status: {{ $isMaintenanceActive ? 'AKTIF' : 'NONAKTIF' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold">Kelola →</span>
                    <svg class="w-8 h-8 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>
        </a>
        
        </div> <!-- Close grid container -->
    </div>
</x-layouts.admin>