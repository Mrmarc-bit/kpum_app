<x-layouts.panitia title="Tambah Kandidat Baru">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tambah Kandidat Baru</h1>
                <p class="text-slate-500 mt-1">Daftarkan pasangan calon Ketua dan Wakil baru.</p>
            </div>
            <a href="{{ route('panitia.kandidat.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        {{-- data-turbo="false" wajib untuk form upload file --
             Turbo Drive menggunakan fetch() yang tidak support redirect
             setelah multipart/form-data (file upload). --}}
        <form action="{{ route('panitia.kandidat.store') }}" method="POST" enctype="multipart/form-data"
            data-turbo="false"
            class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/50">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Data No Urut / Urutan Tampil -->
                <div class="md:col-span-2">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-blue-900">Nomor Urut Otomatis</h3>
                            <p class="text-blue-600 text-sm">Nomor urut akan diberikan secara berurutan oleh sistem
                                saat disimpan.</p>
                        </div>
                    </div>
                </div>

                <!-- Ketua -->
                <div class="space-y-6 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200" x-data="dropdownSearch({
                        selected: '{{ old('prodi_ketua') }}',
                        options: [
                            { label: 'Fakultas Keguruan dan Ilmu Pendidikan (FKIP)', isGroup: true },
                            { value: 'Bimbingan Konseling (BK)', label: 'Bimbingan Konseling (BK)' },
                            { value: 'Pendidikan Guru SD (PGSD)', label: 'Pendidikan Guru SD (PGSD)' },
                            { value: 'Pendidikan Islam Anak Usia Dini (PIAUD)', label: 'Pendidikan Islam Anak Usia Dini (PIAUD)' },
                            { value: 'Manajamen Pendidikan Islam (MPI)', label: 'Manajamen Pendidikan Islam (MPI)' },
                            
                            { label: 'Fakultas Matematika dan Komputer (FMIKOM)', isGroup: true },
                            { value: 'Matematika (MAT)', label: 'Matematika (MAT)' },
                            { value: 'Informatika (INF)', label: 'Informatika (INF)' },
                            { value: 'Sistem Informasi (SI)', label: 'Sistem Informasi (SI)' },

                            { label: 'Fakultas Teknologi Industri (FTI)', isGroup: true },
                            { value: 'Teknik Industri (TIND)', label: 'Teknik Industri (TIND)' },
                            { value: 'Teknik Kimia (TKIM)', label: 'Teknik Kimia (TKIM)' },
                            { value: 'Teknik Mesin (TM)', label: 'Teknik Mesin (TM)' },

                            { label: 'Fakultas Ekonomi (FE)', isGroup: true },
                            { value: 'Manajemen (MAN)', label: 'Manajemen (MAN)' },
                            { value: 'Ekonomi Pembangunan (EP)', label: 'Ekonomi Pembangunan (EP)' },

                            { label: 'Fakultas Keagamaan Islam (FKI)', isGroup: true },
                            { value: 'Pendidikan Agama Islam (PAI)', label: 'Pendidikan Agama Islam (PAI)' },
                            { value: 'Pendidikan Guru Madrasah Ibtidaiyah (PGMI)', label: 'Pendidikan Guru Madrasah Ibtidaiyah (PGMI)' },
                            { value: 'Komunikasi Penyiaran Islam (KPI)', label: 'Komunikasi Penyiaran Islam (KPI)' },
                            { value: 'Hukum Keluarga Islam (HKI)', label: 'Hukum Keluarga Islam (HKI)' },
                        ]
                     })">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <span
                            class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">01</span>
                        Data Calon Ketua
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="nama_ketua" value="{{ old('nama_ketua') }}"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Nama Ketua" required>
                        @error('nama_ketua') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Modern Searchable Dropdown for Ketua -->
                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Program
                            Studi</label>
                        <input type="hidden" name="prodi_ketua" x-model="selected">

                        <div class="relative">
                            <button type="button"
                                @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
                                @click.away="open = false"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-left flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <span x-text="selected ? selected : 'Pilih Program Studi'"
                                    :class="selected ? 'text-slate-800 font-medium' : 'text-slate-400'"></span>
                                <svg class="w-5 h-5 text-slate-400 transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 max-h-48 overflow-hidden flex flex-col">

                                <div class="p-2 border-b border-slate-100 sticky top-0 bg-white z-10">
                                    <input x-ref="searchInput" x-model="search" type="text" placeholder="Cari Prodi..."
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <div class="overflow-y-auto flex-1 p-2">
                                    <template x-for="option in filteredOptions" :key="option.value || option.label">
                                        <div>
                                            <template x-if="option.isGroup">
                                                <div class="sticky top-0 z-10 bg-slate-100 border-b border-slate-200 px-3 py-2 text-xs font-extrabold text-slate-600 uppercase tracking-wider shadow-sm"
                                                    x-text="option.label"></div>
                                            </template>
                                            <template x-if="!option.isGroup">
                                                <button type="button"
                                                    @click="selected = option.value; open = false; search = ''"
                                                    class="w-full text-left px-3 py-2 rounded-lg text-sm hover:bg-blue-50 hover:text-blue-700 transition-colors flex items-center justify-between"
                                                    :class="selected === option.value ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600'">
                                                    <span x-text="option.label"></span>
                                                    <svg x-show="selected === option.value"
                                                        class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                    <div x-show="filteredOptions.length === 0"
                                        class="px-4 py-3 text-sm text-slate-500 text-center">
                                        Tidak ditemukan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Foto Ketua -->
                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto
                            Ketua</label>
                        <div class="flex flex-col items-start gap-4">
                            <div class="relative w-32 h-32 rounded-xl bg-slate-100 border-2 border-dashed border-slate-300 hover:bg-blue-50 hover:border-blue-400 group cursor-pointer transition-all flex items-center justify-center overflow-hidden"
                                onclick="document.getElementById('file-upload-ketua').click()">
                                <svg class="w-8 h-8 text-slate-300 group-hover:text-blue-400 transition-colors z-10"
                                    id="placeholder-icon-ketua" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <img id="image-preview-ketua"
                                    src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="Preview"
                                    class="w-full h-full object-cover hidden absolute inset-0">
                            </div>
                            <input type="file" id="file-upload-ketua" name="foto"
                                onchange="previewImage(this, 'image-preview-ketua', 'placeholder-icon-ketua')"
                                class="hidden" accept="image/jpeg,image/png">
                            @error('foto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Wakil -->
                <div class="space-y-6 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200" x-data="dropdownSearch({
                        selected: '{{ old('prodi_wakil') }}',
                        options: [
                            { label: 'Fakultas Keguruan dan Ilmu Pendidikan (FKIP)', isGroup: true },
                            { value: 'Bimbingan Konseling (BK)', label: 'Bimbingan Konseling (BK)' },
                            { value: 'Pendidikan Guru SD (PGSD)', label: 'Pendidikan Guru SD (PGSD)' },
                            { value: 'Pendidikan Islam Anak Usia Dini (PIAUD)', label: 'Pendidikan Islam Anak Usia Dini (PIAUD)' },
                            { value: 'Manajamen Pendidikan Islam (MPI)', label: 'Manajamen Pendidikan Islam (MPI)' },
                            
                            { label: 'Fakultas Matematika dan Komputer (FMIKOM)', isGroup: true },
                            { value: 'Matematika (MAT)', label: 'Matematika (MAT)' },
                            { value: 'Informatika (INF)', label: 'Informatika (INF)' },
                            { value: 'Sistem Informasi (SI)', label: 'Sistem Informasi (SI)' },

                            { label: 'Fakultas Teknologi Industri (FTI)', isGroup: true },
                            { value: 'Teknik Industri (TIND)', label: 'Teknik Industri (TIND)' },
                            { value: 'Teknik Kimia (TKIM)', label: 'Teknik Kimia (TKIM)' },
                            { value: 'Teknik Mesin (TM)', label: 'Teknik Mesin (TM)' },

                            { label: 'Fakultas Ekonomi (FE)', isGroup: true },
                            { value: 'Manajemen (MAN)', label: 'Manajemen (MAN)' },
                            { value: 'Ekonomi Pembangunan (EP)', label: 'Ekonomi Pembangunan (EP)' },

                            { label: 'Fakultas Keagamaan Islam (FKI)', isGroup: true },
                            { value: 'Pendidikan Agama Islam (PAI)', label: 'Pendidikan Agama Islam (PAI)' },
                            { value: 'Pendidikan Guru Madrasah Ibtidaiyah (PGMI)', label: 'Pendidikan Guru Madrasah Ibtidaiyah (PGMI)' },
                            { value: 'Komunikasi Penyiaran Islam (KPI)', label: 'Komunikasi Penyiaran Islam (KPI)' },
                            { value: 'Hukum Keluarga Islam (HKI)', label: 'Hukum Keluarga Islam (HKI)' },
                        ]
                     })">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <span
                            class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm">02</span>
                        Data Calon Wakil
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="nama_wakil" value="{{ old('nama_wakil') }}"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="Nama Wakil" required>
                        @error('nama_wakil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Modern Searchable Dropdown for Wakil -->
                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Program
                            Studi</label>
                        <input type="hidden" name="prodi_wakil" x-model="selected">
                        <div class="relative">
                            <button type="button"
                                @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
                                @click.away="open = false"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-left flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                <span x-text="selected ? selected : 'Pilih Program Studi'"
                                    :class="selected ? 'text-slate-800 font-medium' : 'text-slate-400'"></span>
                                <svg class="w-5 h-5 text-slate-400 transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 max-h-60 overflow-hidden flex flex-col">

                                <div class="p-2 border-b border-slate-100 sticky top-0 bg-white z-10">
                                    <input x-ref="searchInput" x-model="search" type="text" placeholder="Cari Prodi..."
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>

                                <div class="overflow-y-auto flex-1 p-2">
                                    <template x-for="option in filteredOptions" :key="option.value || option.label">
                                        <div>
                                            <template x-if="option.isGroup">
                                                <div class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider mt-2 first:mt-0"
                                                    x-text="option.label"></div>
                                            </template>
                                            <template x-if="!option.isGroup">
                                                <button type="button"
                                                    @click="selected = option.value; open = false; search = ''"
                                                    class="w-full text-left px-3 py-2 rounded-lg text-sm hover:bg-indigo-50 hover:text-indigo-700 transition-colors flex items-center justify-between"
                                                    :class="selected === option.value ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600'">
                                                    <span x-text="option.label"></span>
                                                    <svg x-show="selected === option.value"
                                                        class="w-4 h-4 text-indigo-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                    <div x-show="filteredOptions.length === 0"
                                        class="px-4 py-3 text-sm text-slate-500 text-center">
                                        Tidak ditemukan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Foto Wakil -->
                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto
                            Wakil</label>
                        <div class="flex flex-col items-start gap-4">
                            <div class="relative w-32 h-32 rounded-xl bg-slate-100 border-2 border-dashed border-slate-300 hover:bg-blue-50 hover:border-blue-400 group cursor-pointer transition-all flex items-center justify-center overflow-hidden"
                                onclick="document.getElementById('file-upload-wakil').click()">
                                <svg class="w-8 h-8 text-slate-300 group-hover:text-indigo-400 transition-colors z-10"
                                    id="placeholder-icon-wakil" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <img id="image-preview-wakil"
                                    src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="Preview"
                                    class="w-full h-full object-cover hidden absolute inset-0">
                            </div>
                            <input type="file" id="file-upload-wakil" name="foto_wakil"
                                onchange="previewImage(this, 'image-preview-wakil', 'placeholder-icon-wakil')"
                                class="hidden" accept="image/jpeg,image/png">
                            @error('foto_wakil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Visi Misi -->
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Visi</label>
                        <textarea name="visi" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            placeholder="Tuliskan visi kandidat...">{{ old('visi') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Misi</label>
                        <textarea name="misi" rows="5"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            placeholder="Tuliskan misi kandidat...">{{ old('misi') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Singkat / Slogan</label>
                        <textarea name="deskripsi_singkat" rows="2"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all"
                            placeholder="Deskripsi singkat atau slogan...">{{ old('deskripsi_singkat') }}</textarea>
                    </div>
                </div>

                <!-- Status & Visibility -->
                <div class="md:col-span-2 flex flex-col sm:flex-row gap-6">
                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <input type="checkbox" name="status_aktif" id="status_aktif" value="1" checked
                            class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 border-gray-300 transition-all">
                        <label for="status_aktif" class="font-bold text-slate-700 select-none cursor-pointer">Status
                            Aktif</label>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <input type="checkbox" name="tampilkan_di_landing" id="tampilkan_di_landing" value="1" checked
                            class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 border-gray-300 transition-all">
                        <label for="tampilkan_di_landing"
                            class="font-bold text-slate-700 select-none cursor-pointer">Tampilkan di Landing
                            Page</label>
                    </div>
                </div>

            </div>

            {{-- ===== KOALISI PARTAI PENGUSUNG ===== --}}
            @if($parties->isNotEmpty())
            <div class="mt-8 pt-8 border-t border-slate-100">
                <div class="mb-5 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Koalisi Partai Pengusung</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pilih partai yang mendukung paslon ini. Logo diambil dari data Manajemen Partai.</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($parties as $party)
                        @php
                            $isSelected = in_array($party->id, old('party_ids', []));
                            $logoUrl = $party->logo_path ? asset('storage/' . $party->logo_path) : null;
                            $abbr = strtoupper(substr($party->short_name ?? $party->name, 0, 2));
                        @endphp

                        <label for="party_c_{{ $party->id }}"
                            x-data="{ checked: {{ $isSelected ? 'true' : 'false' }} }"
                            :class="checked ? 'border-blue-500 bg-blue-50 shadow-md shadow-blue-100' : 'border-slate-200 bg-slate-50 hover:border-blue-300 hover:bg-blue-50/50'"
                            class="relative flex flex-col items-center gap-2.5 p-3 rounded-2xl border-2 cursor-pointer transition-all duration-200">

                            <input type="checkbox"
                                id="party_c_{{ $party->id }}"
                                name="party_ids[]"
                                value="{{ $party->id }}"
                                {{ $isSelected ? 'checked' : '' }}
                                @change="checked = $event.target.checked"
                                class="sr-only">

                            {{-- Checkmark pill (top-right) --}}
                            <div class="absolute top-2 right-2 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all duration-150"
                                :class="checked ? 'bg-blue-500 border-blue-500' : 'border-slate-300 bg-white'">
                                <svg x-show="checked" x-cloak class="w-3 h-3 text-white"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>

                            {{-- Logo --}}
                            <div class="w-14 h-14 rounded-xl overflow-hidden flex items-center justify-center bg-white border border-slate-100 shadow-sm mt-1">
                                @if($logoUrl)
                                    <img src="{{ $logoUrl }}"
                                        alt="{{ e($party->name) }}"
                                        class="w-full h-full object-contain p-1"
                                        onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex'">
                                    <span style="display:none" class="text-xl font-black text-slate-300 w-full h-full items-center justify-center">{{ $abbr }}</span>
                                @else
                                    <span class="text-xl font-black text-slate-300">{{ $abbr }}</span>
                                @endif
                            </div>

                            {{-- Nama Partai --}}
                            <p class="text-xs font-bold text-slate-800 leading-tight text-center line-clamp-2">
                                {{ $party->short_name ?? $party->name }}
                            </p>
                        </label>
                    @endforeach
                </div>
                @error('party_ids')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
            @else
            <div class="mt-8 pt-8 border-t border-slate-100">
                <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-100 rounded-2xl">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-amber-800">Belum ada data partai</p>
                        <p class="text-xs text-amber-700 mt-0.5">
                            Tambahkan partai terlebih dahulu di
                            <a href="{{ route('admin.parties.index') }}" class="underline font-semibold hover:text-amber-900">Manajemen Partai</a>
                            untuk mengatur koalisi.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-8 pt-8 border-t border-slate-100 flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                    Simpan Kandidat
                </button>
            </div>
        </form>
    </div>


    <script>
        // previewImage: tetap di sini karena spesifik per halaman
        function previewImage(input, previewId, placeholderId) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        // dropdownSearch Alpine component sudah didaftarkan di resources/js/app.js
    </script>
</x-layouts.admin>
