@if(($settings['show_candidates'] ?? 'false') === 'true')
    <section id="kandidat" class="py-24 bg-slate-50 relative overflow-hidden">
        <!-- Modern Grid Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:24px_24px]">
        </div>
        <div class="absolute left-0 right-0 top-0 -z-10 m-auto h-[310px] w-[310px] rounded-full bg-blue-400 opacity-10 blur-[100px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Section Header -->
            <div class="text-center mb-16">
                <span
                    class="inline-block py-1.5 px-4 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-bold tracking-widest uppercase mb-4 shadow-sm">
                    Kandidat 2026
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">
                    Pasangan Calon
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto font-medium">
                    Presiden & Wakil Presiden Mahasiswa
                </p>
            </div>

            <!-- Wrapper for modal handling -->
            <div x-data="{
                activeModal: null,
                selectedKandidat: null,
                openModal(type, data) {
                    this.activeModal = type;
                    this.selectedKandidat = data;
                    document.body.classList.add('overflow-hidden');
                },
                closeModal() {
                    this.activeModal = null;
                    this.selectedKandidat = null;
                    document.body.classList.remove('overflow-hidden');
                }
            }">

                <!-- PASLON GRID (Presma & Wapresma) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-items-center mb-24">
                    @forelse($kandidats->sortBy('no_urut') as $kandidat)
                        <!-- Modern Glass Flip Card Paslon Container -->
                        <div x-data="{ flip: false }" x-init="setInterval(() => flip = !flip, 5000)"
                            class="group perspective-[1000px] w-full max-w-[320px] aspect-[3/5] relative">

                            <!-- Card Inner (Flipping Wrapper) -->
                            <div class="relative w-full h-full transition-transform duration-700 ease-[cubic-bezier(0.23,1,0.32,1)] [transform-style:preserve-3d]"
                                :class="{ '[transform:rotateY(180deg)]': flip }">

                                <!-- FRONT SIDE: CALON PRESIDEN -->
                                <div
                                    class="absolute inset-0 w-full h-full [backface-visibility:hidden] bg-white/60 backdrop-blur-xl border border-white/50 shadow-xl shadow-slate-200/50 rounded-[2rem] p-4 flex flex-col overflow-hidden ring-1 ring-white/60">
                                    <!-- Badge Nomor Urut -->
                                    <div class="absolute top-6 right-6 z-20">
                                        <div
                                            class="w-10 h-10 flex items-center justify-center bg-white border border-slate-100 rounded-full shadow-sm text-slate-900 font-extrabold text-lg">
                                            {{ $kandidat->no_urut }}
                                        </div>
                                    </div>

                                    <!-- Foto Container (4:5 Ratio) -->
                                    <div
                                        class="relative w-full aspect-[4/5] rounded-[1.5rem] overflow-hidden shadow-inner bg-slate-100 mb-5 group-hover:shadow-md transition-shadow duration-300">
                                        @if($kandidat->foto)
                                            <img src="{{ asset('storage/' . $kandidat->foto) }}"
                                                srcset="{{ asset('storage/' . $kandidat->foto_thumb) }} 300w, {{ asset('storage/' . $kandidat->foto) }} 800w"
                                                sizes="(max-width: 768px) 300px, 800px"
                                                loading="lazy"
                                                alt="{{ $kandidat->nama_ketua }}"
                                                class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105">
                                        @else
                                            <div
                                                class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                                <svg class="w-16 h-16 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-xs font-semibold uppercase tracking-wider">No Photo</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Info Section -->
                                    <div class="flex-1 flex flex-col items-center text-center">
                                        <div
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-[10px] font-bold tracking-widest uppercase mb-3 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            Calon Presiden
                                        </div>
                                        <h3 class="text-xl font-bold text-slate-900 leading-tight mb-1 line-clamp-2">
                                            {{ $kandidat->nama_ketua }}</h3>
                                        <p class="text-sm font-medium text-slate-500 mb-2 line-clamp-1">
                                            {{ $kandidat->prodi_ketua ?: 'Mahasiswa' }}</p>
                                    </div>
                                </div>

                                <!-- BACK SIDE: CALON WAKIL PRESIDEN -->
                                <div
                                    class="absolute inset-0 w-full h-full [backface-visibility:hidden] [transform:rotateY(180deg)] bg-white/60 backdrop-blur-xl border border-white/50 shadow-xl shadow-slate-200/50 rounded-[2rem] p-4 flex flex-col overflow-hidden ring-1 ring-white/60">
                                    <!-- Badge Nomor Urut -->
                                    <div class="absolute top-6 right-6 z-20">
                                        <div
                                            class="w-10 h-10 flex items-center justify-center bg-white border border-slate-100 rounded-full shadow-sm text-slate-900 font-extrabold text-lg">
                                            {{ $kandidat->no_urut }}
                                        </div>
                                    </div>

                                    <!-- Foto Container (4:5 Ratio) -->
                                    <div
                                        class="relative w-full aspect-[4/5] rounded-[1.5rem] overflow-hidden shadow-inner bg-slate-100 mb-5 group-hover:shadow-md transition-shadow duration-300">
                                        @if($kandidat->foto_wakil)
                                            <img src="{{ asset('storage/' . $kandidat->foto_wakil) }}"
                                                srcset="{{ asset('storage/' . $kandidat->foto_wakil_thumb) }} 300w, {{ asset('storage/' . $kandidat->foto_wakil) }} 800w"
                                                sizes="(max-width: 768px) 300px, 800px"
                                                loading="lazy"
                                                alt="{{ $kandidat->nama_wakil }}"
                                                class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105">
                                        @elseif($kandidat->foto)
                                            <img src="{{ asset('storage/' . $kandidat->foto) }}"
                                                srcset="{{ asset('storage/' . $kandidat->foto_thumb) }} 300w, {{ asset('storage/' . $kandidat->foto) }} 800w"
                                                sizes="(max-width: 768px) 300px, 800px"
                                                loading="lazy"
                                                alt="{{ $kandidat->nama_wakil }}"
                                                class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105">
                                        @else
                                            <div
                                                class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                                <svg class="w-16 h-16 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-xs font-semibold uppercase tracking-wider">No Photo</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Info Section -->
                                    <div class="flex-1 flex flex-col items-center text-center">
                                        <div
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-bold tracking-widest uppercase mb-3 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                            Calon Wakil
                                        </div>
                                        <h3 class="text-xl font-bold text-slate-900 leading-tight mb-1 line-clamp-2">
                                            {{ $kandidat->nama_wakil }}</h3>
                                        <p class="text-sm font-medium text-slate-500 mb-2 line-clamp-1">
                                            {{ $kandidat->prodi_wakil ?: 'Mahasiswa' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button (Triggers Modal) -->
                            <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 z-30 w-full text-center">
                                <button @click="openModal('presma', {{ json_encode($kandidat) }})"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white text-sm font-bold rounded-full shadow-lg shadow-blue-900/10 hover:bg-blue-600 hover:shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-1 group cursor-pointer">
                                    <span>Lihat Visi Misi</span>
                                    <svg class="w-4 h-4 text-white/70 group-hover:text-white transition-colors" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900">Belum Ada Kandidat</h3>
                            <p class="text-slate-500 mt-2">Daftar calon akan segera ditampilkan.</p>
                        </div>
                    @endforelse
                </div>

                <!-- SECTION 2: DPM (Dewan Perwakilan Mahasiswa) -->
                <div class="border-t border-slate-200/60 my-16 w-full max-w-sm mx-auto"></div>

                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight mb-3">
                        Calon Ketua DPM UNUGHA
                    </h2>
                    <p class="text-slate-600 font-medium">Perwakilan aspirasi dari setiap fakultas</p>
                </div>

                <!-- DPM GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($calonDpms as $dpm)
                        <div class="p-4">
                            <div
                                class="bg-white rounded-[2rem] p-5 border border-slate-100 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden flex flex-col h-full">
                                <!-- Number Badge -->
                                <div
                                    class="absolute top-4 right-4 bg-slate-100 text-slate-600 font-bold text-xs w-8 h-8 flex items-center justify-center rounded-full z-10">
                                    {{ $dpm->urutan_tampil }}
                                </div>

                                <div class="flex items-center gap-4 p-2">
                                    <!-- Foto Kandidat -->
                                    <div
                                        class="w-20 h-20 rounded-2xl bg-slate-100 overflow-hidden relative shadow-sm shrink-0 ring-1 ring-slate-100">
                                        @if($dpm->foto)
                                            <img src="{{ asset('storage/' . $dpm->foto_thumb) }}" loading="lazy" alt="{{ $dpm->nama }}"
                                                class="w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="flex items-center justify-center h-full text-slate-300 bg-slate-50">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Wrapper Info -->
                                    <div class="flex-1 min-w-0 py-1">
                                        <h4
                                            class="font-bold text-slate-900 text-base leading-tight group-hover:text-blue-600 transition-colors truncate">
                                            {{ $dpm->nama }}</h4>
                                        <span
                                            class="text-xs font-semibold text-slate-500 mt-1 truncate block">{{ $dpm->fakultas }}</span>
                                        <div class="flex items-center gap-1 mt-1">
                                            <div class="w-1 h-1 rounded-full bg-blue-400"></div>
                                            <span
                                                class="text-[10px] text-slate-400 font-medium truncate">{{ $dpm->prodi }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- DPM Action Button -->
                                <div class="mt-4 pt-4 border-t border-slate-100 flex justify-center">
                                    <button @click="openModal('dpm', {{ json_encode($dpm) }})"
                                        class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
                                        Lihat Profil & Visi Misi
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-slate-500 text-sm">
                            Belum ada calon Ketua DPM.
                        </div>
                    @endforelse
                </div>

                <!-- UNIVERSAL CANDIDATE MODAL (Clean SaaS Dashboard Style) -->
                <template x-teleport="body">
                    <div x-show="activeModal" style="display: none; z-index: 9999;"
                        class="fixed inset-0 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
                        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                        <!-- Modal Content Container -->
                        <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-xl flex flex-col max-h-[90vh] overflow-hidden" @click.outside="closeModal()">

                            <!-- Header -->
                            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 shrink-0">
                                <h3 class="text-base sm:text-lg font-bold text-slate-900">Detail Kandidat</h3>
                                <button @click="closeModal()"
                                    class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Body (Split Layout) -->
                            <div class="flex-1 overflow-y-auto overflow-x-hidden">
                                <div class="flex flex-col-reverse md:flex-row h-full">

                                    <!-- LEFT COLUMN: Content Fields (60%) -->
                                    <div class="w-full md:w-[60%] p-4 sm:p-6 md:p-8 space-y-4 sm:space-y-6">

                                        <!-- Modern Creative Banner -->
                                        <!-- Official KPUM Logo Header -->
                                        <!-- Official KPUM Logo Header -->
                                        <div class="w-full mb-4 sm:mb-6 md:mb-8 flex flex-col items-center justify-center text-center px-3 sm:px-4">
                                            @if(isset($settings['app_logo']) && $settings['app_logo'])
                                                <!-- Logomark (Clean, no shadows/shapes) -->
                                                <img src="{{ asset((string) $settings['app_logo']) }}" alt="KPUM" class="h-10 sm:h-14 md:h-20 w-auto mb-2 sm:mb-3 md:mb-4 object-contain">
                                            @else
                                                <div class="w-10 h-10 sm:w-12 sm:h-12 md:w-16 md:h-16 bg-blue-600 rounded-lg sm:rounded-xl flex items-center justify-center text-white font-bold text-sm sm:text-base md:text-xl mb-2 sm:mb-3 md:mb-4">
                                                    K
                                                </div>
                                            @endif

                                            <!-- Titles (Responsive typography) -->
                                            <h4 class="text-slate-900 font-black text-xs sm:text-sm md:text-lg lg:text-xl leading-snug sm:leading-tight mb-0.5 sm:mb-1 max-w-full">
                                                Komisi Pemilihan Umum Mahasiswa
                                            </h4>
                                            <p class="text-slate-500 text-[9px] sm:text-[10px] md:text-xs font-semibold uppercase tracking-tight sm:tracking-wide leading-relaxed max-w-full">
                                                Universitas Nahdlatul Ulama Al Ghazali Cilacap
                                            </p>
                                        </div>

                                        <!-- Coalition Parties (Dynamic from Kandidat -> Parties) -->
                                        <template x-if="selectedKandidat && selectedKandidat.parties && selectedKandidat.parties.length > 0">
                                            <div class="mb-6">
                                                <label class="block text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 ml-1">Partai Pengusung / Koalisi</label>
                                                <div class="flex flex-wrap gap-2 sm:gap-3 bg-slate-50/50 p-3 sm:p-4 rounded-xl border border-slate-100 shadow-sm">
                                                    <template x-for="party in selectedKandidat.parties" :key="party.id">
                                                        <div class="group/party relative">
                                                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-lg sm:rounded-xl border border-slate-200 overflow-hidden shadow-sm flex items-center justify-center transition-all duration-300 hover:border-blue-400 hover:shadow-md relative">
                                                                <template x-if="party.logo_path">
                                                                    <img :src="'/storage/' + (party.logo_path.includes('/') ? party.logo_path : 'images/medium/' + party.logo_path + '.webp')" 
                                                                         :alt="party.name" 
                                                                         class="w-full h-full object-contain p-1.5 relative z-10 bg-white"
                                                                         x-on:error="$event.target.style.display='none'">
                                                                </template>
                                                                <span class="absolute inset-0 flex items-center justify-center text-[10px] font-black text-slate-400 uppercase bg-slate-50 z-0" x-text="party.short_name || party.name.substring(0,2)"></span>
                                                            </div>
                                                            <!-- Tooltip Nama Partai -->
                                                            <div class="absolute -top-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-slate-900 text-white text-[9px] font-bold rounded opacity-0 group-hover/party:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-20">
                                                                <span x-text="party.name"></span>
                                                                <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-900"></div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Visi Field -->
                                        <div>
                                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 sm:mb-2 ml-1">Visi</label>
                                            <div class="w-full bg-white border border-slate-200 rounded-lg sm:rounded-xl px-3 sm:px-4 md:px-5 py-3 sm:py-4 text-xs sm:text-sm text-slate-700 leading-relaxed shadow-xs hover:border-indigo-300 transition-colors group/input">
                                                <p class="whitespace-pre-wrap group-hover/input:text-slate-900 transition-colors break-words" x-text="(selectedKandidat && selectedKandidat.visi) || 'Belum ada visi.'"></p>
                                            </div>
                                        </div>

                                        <!-- Misi Field -->
                                        <div>
                                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 sm:mb-2 ml-1">Misi</label>
                                            <div class="w-full bg-white border border-slate-200 rounded-lg sm:rounded-xl px-3 sm:px-4 md:px-5 py-3 sm:py-4 text-xs sm:text-sm text-slate-700 leading-relaxed shadow-xs hover:border-indigo-300 transition-colors group/input">
                                                <p class="whitespace-pre-wrap group-hover/input:text-slate-900 transition-colors break-words" x-text="(selectedKandidat && selectedKandidat.misi) || 'Belum ada misi.'"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- RIGHT COLUMN: Profile Preview (40%) -->
                                    <div class="w-full md:w-[40%] bg-white p-4 sm:p-6 md:p-8 flex flex-col items-center text-center border-b md:border-b-0 md:border-l md:border-dashed border-slate-200">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 sm:mb-6 md:mb-8 hidden md:block">Candidate Profile</span>

                                        <!-- Profile Photo (Auto-Flip Squircle) -->
                                        <div class="relative mb-6"
                                             x-data="{ flip: false }"
                                             x-init="$watch('selectedKandidat', value => { flip = false; }); if(selectedKandidat && selectedKandidat.foto_wakil) setInterval(() => flip = !flip, 3000)">

                                            <!-- PERSPECTIVE CONTAINER -->
                                            <div class="w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 perspective-[1000px] relative">
                                                <div class="relative w-full h-full transition-transform duration-1000 ease-in-out [transform-style:preserve-3d]"
                                                     :class="{ '[transform:rotateY(180deg)]': flip }">

                                                    <!-- FRONT: Ketua -->
                                                    <div class="absolute inset-0 w-full h-full [backface-visibility:hidden] rounded-[2rem] overflow-hidden border-4 border-white shadow-xl shadow-indigo-100 bg-slate-50 rotate-0 ring-1 ring-slate-100">
                                                        <template x-if="selectedKandidat && selectedKandidat.foto">
                                                            <img :src="'/storage/' + selectedKandidat.foto" class="w-full h-full object-cover">
                                                        </template>
                                                        <template x-if="selectedKandidat && !selectedKandidat.foto">
                                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                            </div>
                                                        </template>
                                                        <!-- Label Overlay -->
                                                        <div class="absolute bottom-0 inset-x-0 p-2 bg-gradient-to-t from-black/60 to-transparent">
                                                            <span class="text-white text-[10px] font-bold uppercase tracking-wider">Ketua</span>
                                                        </div>
                                                    </div>

                                                    <!-- BACK: Wakil -->
                                                    <template x-if="selectedKandidat && selectedKandidat.foto_wakil">
                                                        <div class="absolute inset-0 w-full h-full [backface-visibility:hidden] [transform:rotateY(180deg)] rounded-[2rem] overflow-hidden border-4 border-white shadow-xl shadow-indigo-100 bg-slate-50 ring-1 ring-slate-100">
                                                            <img :src="'/storage/' + selectedKandidat.foto_wakil" class="w-full h-full object-cover">
                                                            <!-- Label Overlay -->
                                                            <div class="absolute bottom-0 inset-x-0 p-2 bg-gradient-to-t from-black/60 to-transparent">
                                                                <span class="text-white text-[10px] font-bold uppercase tracking-wider">Wakil</span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Name & Title -->
                                        <div class="mb-4">
                                            <template x-if="activeModal === 'presma'">
                                                <div class="flex flex-col gap-1">
                                                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 leading-tight line-clamp-2" x-text="selectedKandidat ? selectedKandidat.nama_ketua : ''"></h3>
                                                    <!-- Info Ketua -->
                                                    <p class="text-[10px] sm:text-xs text-slate-500 font-medium break-words">
                                                        <span x-text="selectedKandidat ? selectedKandidat.prodi_ketua : ''"></span>
                                                        <span x-show="selectedKandidat && selectedKandidat.fakultas_ketua" class="hidden sm:inline"> - </span><span x-show="selectedKandidat && selectedKandidat.fakultas_ketua" x-text="selectedKandidat ? selectedKandidat.fakultas_ketua : ''"></span>
                                                    </p>

                                                    <!-- Separator if Wakil exists -->
                                                    <template x-if="selectedKandidat && selectedKandidat.nama_wakil">
                                                        <div class="my-2 flex items-center justify-center gap-2">
                                                            <span class="h-px w-8 bg-slate-200"></span>
                                                            <span class="text-[10px] text-slate-400 uppercase font-bold">&</span>
                                                            <span class="h-px w-8 bg-slate-200"></span>
                                                        </div>
                                                    </template>

                                                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 leading-tight line-clamp-2" x-show="selectedKandidat && selectedKandidat.nama_wakil" x-text="selectedKandidat ? selectedKandidat.nama_wakil : ''"></h3>
                                                    <!-- Info Wakil -->
                                                    <p class="text-[10px] sm:text-xs text-slate-500 font-medium break-words" x-show="selectedKandidat && selectedKandidat.nama_wakil">
                                                        <span x-text="selectedKandidat ? selectedKandidat.prodi_wakil : ''"></span>
                                                        <span x-show="selectedKandidat && selectedKandidat.fakultas_wakil" class="hidden sm:inline"> - </span><span x-show="selectedKandidat && selectedKandidat.fakultas_wakil" x-text="selectedKandidat ? selectedKandidat.fakultas_wakil : ''"></span>
                                                    </p>
                                                </div>
                                            </template>

                                            <template x-if="activeModal === 'dpm'">
                                                <div class="flex flex-col gap-1">
                                                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 leading-tight line-clamp-2" x-text="selectedKandidat ? selectedKandidat.nama : ''"></h3>
                                                    <p class="text-xs sm:text-sm font-medium text-slate-500 break-words">
                                                        <span x-text="selectedKandidat ? selectedKandidat.prodi : ''"></span>
                                                    </p>
                                                    <p class="text-[10px] sm:text-xs font-bold text-indigo-600 uppercase tracking-tight bg-indigo-50 px-2 py-1 rounded-md self-center inline-block mt-1 max-w-full break-words text-center">
                                                        <span x-text="selectedKandidat ? selectedKandidat.fakultas : ''"></span>
                                                    </p>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Number Badge -->
                                        <div class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 md:px-5 py-2 sm:py-2.5 rounded-lg sm:rounded-xl bg-slate-50 border border-slate-100 shadow-sm">
                                            <span class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-widest">No. Urut</span>
                                            <span class="text-xl sm:text-2xl font-black text-slate-900" x-text="selectedKandidat ? (selectedKandidat.no_urut || selectedKandidat.urutan_tampil) : ''"></span>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Footer (Action Bar) -->
                            <div class="px-6 py-4 border-t border-slate-100 bg-white flex items-center justify-end gap-3 shrink-0">
                                <button @click="closeModal()"
                                    class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                                    Kembali
                                </button>
                                <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('login.mahasiswa') }}"
                                    class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition-colors shadow-sm">
                                    Vote Sekarang
                                </a>
                            </div>

                        </div>
                    </div>
                </template>
            </div>

        </div>
    </section>
@endif
