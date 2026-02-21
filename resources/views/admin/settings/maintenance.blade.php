<x-layouts.admin title="Maintenance Mode Settings">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Mode Maintenance</h1>
                <p class="text-slate-500 mt-1">Kontrol akses website untuk pemeliharaan sistem.</p>
            </div>

            <a href="{{ route('admin.settings.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-600 text-white text-sm font-semibold rounded-xl hover:bg-slate-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Pengaturan
            </a>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @php
            $isMaintenanceActive = \App\Models\Setting::isMaintenanceMode();
        @endphp

        <!-- MAINTENANCE MODE CARD -->
        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-orange-500/30">
            
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

            <form method="POST" action="{{ route('admin.settings.maintenance.update') }}" class="relative z-10"
                x-data="{ 
                    maintenanceMode: {{ $isMaintenanceActive ? 'true' : 'false' }},
                    loading: false 
                }">
                @csrf

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
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
                        <p class="text-orange-100 max-w-2xl">
                            Aktifkan mode pemeliharaan untuk membuat website tidak dapat diakses oleh pengguna umum.
                            Admin dan panitia tetap dapat mengakses sistem.
                        </p>
                    </div>

                    <!-- Main Toggle Switch -->
                    <div class="flex-shrink-0">
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <!-- Hidden input for unchecked state -->
                            <input type="hidden" name="maintenance_mode" value="0">
                            <!-- Checkbox for checked state -->
                            <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer"
                                {{ $isMaintenanceActive ? 'checked' : '' }}
                                @change="maintenanceMode = $el.checked">
                            <div
                                class="w-20 h-10 bg-white/20 peer-focus:ring-4 peer-focus:ring-white/30 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:rounded-full after:h-8 after:w-8 after:transition-all peer-checked:bg-white/30 border-2 border-white/40 shadow-inner">
                            </div>
                            <span class="ms-4 text-lg font-bold" x-text="maintenanceMode ? 'ON' : 'OFF'"></span>
                        </label>
                    </div>
                </div>

                <!-- Configuration Grid -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Maintenance Message -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-orange-100 uppercase tracking-wider mb-3">
                            Pesan Maintenance
                        </label>
                        <textarea name="maintenance_message" rows="3"
                            class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/50 focus:ring-2 focus:ring-white/40 focus:bg-white/15 transition-all resize-none"
                            placeholder="Website sedang dalam pemeliharaan sistem pemilu. Silakan kembali beberapa saat lagi.">{{ setting('maintenance_message', 'Website sedang dalam pemeliharaan sistem pemilu. Silakan kembali beberapa saat lagi.') }}</textarea>
                        <p class="mt-2 text-xs text-orange-100/70">Pesan yang akan ditampilkan kepada pengunjung
                            saat mode maintenance aktif.</p>
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-xs font-bold text-orange-100 uppercase tracking-wider mb-3">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Estimasi Selesai (Opsional)
                        </label>
                        <input type="datetime-local" name="maintenance_end_time"
                            value="{{ setting('maintenance_end_time') }}"
                            class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-white/40 focus:bg-white/15 transition-all scheme-dark">
                        <p class="mt-2 text-xs text-orange-100/70">Menampilkan countdown timer di halaman
                            maintenance.</p>
                    </div>

                    <!-- Status Info -->
                    <div
                        class="flex items-center gap-4 p-6 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                                <span class="relative flex h-4 w-4" x-show="maintenanceMode">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                                </span>
                                <span class="relative flex h-4 w-4" x-show="!maintenanceMode"
                                    style="display: none;">
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
                                </span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-orange-100/70 uppercase tracking-wider font-bold mb-1">Status
                                Saat Ini</p>
                            <p class="text-xl font-black"
                                x-text="maintenanceMode ? 'Website Offline' : 'Website Online'"></p>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-8 flex gap-4 items-center flex-wrap">
                    <button type="submit" :disabled="loading"
                        class="px-8 py-4 bg-white text-orange-600 font-black text-sm rounded-xl hover:bg-orange-50 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-3">
                        <svg class="w-5 h-5" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Simpan Pengaturan Maintenance</span>
                    </button>

                    <!-- Warning Badge -->
                    <div class="flex items-center gap-2 px-4 py-2 bg-yellow-400/20 backdrop-blur-sm border border-yellow-300/30 rounded-xl"
                        x-show="maintenanceMode">
                        <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm font-bold text-yellow-200">Pengguna tidak dapat mengakses website</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Cards -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Who Can Access -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Tetap Bisa Akses
                </h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        Administrator
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        Panitia
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        Route /login
                    </li>
                </ul>
            </div>

            <!-- Who Cannot Access -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Tidak Bisa Akses
                </h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                        Mahasiswa
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                        Pengunjung Umum
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                        Semua Route Publik
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-layouts.admin>
