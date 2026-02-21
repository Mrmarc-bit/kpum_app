<x-layouts.mahasiswa title="Bilik Suara">
    <div x-data="{ 
        hasVotedPresma: {{ $hasVotedPresma ? 'true' : 'false' }},
        hasVotedDpm: {{ $hasVotedDpm ? 'true' : 'false' }},
        isVotingClosed: {{ $isVotingClosed ? 'true' : 'false' }},
        selectedKandidat: null,
        voteType: 'presma',
        submitting: false,
        
        openModal(id, name, no, type = 'presma') {
            if(this.isVotingClosed) return;
            if(type === 'presma' && this.hasVotedPresma) return;
            if(type === 'dpm' && this.hasVotedDpm) return;
            
            this.selectedKandidat = { id, name, no };
            this.voteType = type;
            this.$refs.confirmModal.showModal();
        }
    }" class="space-y-10">

        <!-- Simulation Banner -->
        @if(isset($isSimulation) && $isSimulation)
        <div class="bg-yellow-400 border-2 border-yellow-500 rounded-3xl p-6 shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMCwgMCwgMCwgMC4wNSkiLz48L3N2Zz4=')] opacity-20"></div>
            <div class="flex items-start md:items-center gap-4 relative z-10">
                <div class="w-12 h-12 bg-white text-yellow-600 rounded-2xl flex items-center justify-center flex-shrink-0 animate-pulse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-black text-slate-900 text-xl mb-1 uppercase tracking-tight">Mode Simulasi Aktif</h3>
                    <p class="text-slate-800 font-medium text-sm leading-relaxed">
                        Sistem sedang dalam mode uji coba. <strong>Suara yang Anda berikan saat ini hanyalah simulasi</strong> dan dapat direset sebelum pemilihan resmi dimulai.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Modern Welcome Banner -->
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 shadow-2xl p-8 md:p-12 text-white border border-white/10 isolate">
            <!-- Decorative Elements -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="space-y-4 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs font-bold text-blue-200 tracking-widest uppercase backdrop-blur-md">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        Bilik Suara Digital
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight">
                        Selamat Datang, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-indigo-300">{{ Auth::guard('mahasiswa')->user()->name }}</span>
                    </h1>
                    <p class="text-slate-300 text-lg leading-relaxed max-w-xl">
                        Suara Anda adalah kunci perubahan. Silakan gunakan hak pilih Anda dengan bijak, jujur, dan bebas dari intervensi.
                    </p>
                </div>

                <!-- Status Cards -->
                <div class="flex flex-col gap-3 min-w-[200px]">
                    <!-- Presma Status -->
                    <div class="px-5 py-4 rounded-2xl backdrop-blur-md border transition-all duration-300"
                        :class="hasVotedPresma ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300' : 'bg-white/5 border-white/10 text-slate-300'"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm font-bold">Presiden Mahasiswa</span>
                            <template x-if="hasVotedPresma">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </template>
                            <template x-if="!hasVotedPresma">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                            </template>
                        </div>
                        <div class="text-xs mt-1 font-medium opacity-80" x-text="hasVotedPresma ? 'Sudah Memilih' : 'Belum Memilih'"></div>
                    </div>

                    <!-- DPM Status -->
                    <div class="px-5 py-4 rounded-2xl backdrop-blur-md border transition-all duration-300"
                        :class="hasVotedDpm ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300' : 'bg-white/5 border-white/10 text-slate-300'"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm font-bold">DPM</span>
                            <template x-if="hasVotedDpm">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </template>
                            <template x-if="!hasVotedDpm">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                            </template>
                        </div>
                        <div class="text-xs mt-1 font-medium opacity-80" x-text="hasVotedDpm ? 'Sudah Memilih' : 'Belum Memilih'"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Voting Closed Warning Banner -->
        @if($isVotingClosed)
        <div class="bg-red-50 border-2 border-red-200 rounded-3xl p-6 shadow-lg">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-red-900 text-lg mb-1">Waktu Pemilihan Telah Berakhir</h3>
                    <p class="text-red-700 text-sm leading-relaxed">
                        Periode pemilihan sudah ditutup. Anda masih dapat melihat daftar kandidat, namun tidak dapat memberikan suara. 
                        Terima kasih atas partisipasi Anda dalam proses demokrasi kampus.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Proof of Vote Button -->
        <template x-if="hasVotedPresma && hasVotedDpm">
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-1 shadow-2xl shadow-blue-500/20">
                <div class="bg-white rounded-[1.4rem] p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 text-xl md:text-2xl mb-1">Bukti Partisipasi Tersedia!</h3>
                            <div class="text-slate-500 text-sm md:text-base space-y-2">
                                <p>Terima kasih telah menggunakan hak pilih Anda. Unduh sertifikat bukti pemilihan sebagai tanda partisipasi dalam demokrasi kampus.</p>
                                @if(Auth::guard('mahasiswa')->user()->email)
                                    <div class="inline-flex items-center gap-2 text-emerald-600 font-bold bg-emerald-50 px-3 py-1.5 rounded-lg text-xs md:text-sm border border-emerald-100">
                                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        <span>Bukti terkirim ke: {{ Auth::guard('mahasiswa')->user()->email }}</span>
                                    </div>
                                @else
                                    <form action="{{ route('vote.proof.email') }}" method="POST" class="mt-2 flex gap-2 items-center w-full max-w-sm">
                                        @csrf
                                        <input type="email" name="email" placeholder="Masukkan email untuk kirim bukti..." required
                                            class="flex-1 px-4 py-2 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                                            Kirim
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('vote.proof.download') }}" target="_blank" class="group px-8 py-4 bg-slate-900 hover:bg-black text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1 hover:shadow-xl flex items-center gap-3 shrink-0">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span>Unduh Bukti PDF</span>
                    </a>
                </div>
            </div>
        </template>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm" role="alert">
                <p class="font-bold">Terjadi Kesalahan!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Presma Section -->
        <div>
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Daftar Kandidat Ketua & Wakil
                </h2>
                <span class="text-sm text-slate-500">Periode 2026/2027</span>
            </div>

            {{-- Mobile: Horizontal Scroll (Snap), Desktop: Grid --}}
            <div class="flex md:grid md:grid-cols-2 lg:grid-cols-3 gap-6 overflow-x-auto pb-8 snap-x snap-mandatory scrollbar-hide md:overflow-visible md:pb-0">
                @foreach($kandidats as $kandidat)
                    <div :class="hasVotedPresma ? 'opacity-80 grayscale contrast-75 cursor-not-allowed transform scale-95' : 'hover:shadow-2xl hover:border-blue-300/50 hover:-translate-y-2'"
                        class="group bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-xl transition-all duration-500 flex flex-col h-full relative w-[85vw] sm:w-[400px] flex-shrink-0 snap-center">
                        
                        <!-- Voted Overlay -->
                        <template x-if="hasVotedPresma">
                            <div class="absolute inset-0 z-50 bg-slate-900/10 backdrop-blur-[2px] flex items-center justify-center">
                                <div class="bg-white/90 px-6 py-3 rounded-2xl shadow-lg border border-slate-200">
                                    <span class="text-slate-500 font-bold uppercase tracking-widest text-sm flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Sesi Terkunci
                                    </span>
                                </div>
                            </div>
                        </template>

                        <!-- Photo Area -->
                        <div class="h-64 bg-slate-100 relative overflow-hidden flex items-center justify-center group-hover:h-72 transition-all duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent z-10"></div>
                            @if($kandidat->foto)
                                <img src="{{ asset($kandidat->foto) }}" alt="{{ $kandidat->nama_ketua }}"
                                    class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="text-slate-300 flex flex-col items-center">
                                    <svg class="w-20 h-20 mb-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                    <span class="text-xs font-bold uppercase tracking-widest">No Photo</span>
                                </div>
                            @endif
                            
                            <div class="absolute bottom-0 left-0 right-0 p-6 z-20 translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                                <div class="inline-flex items-center gap-2 bg-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg mb-2 shadow-lg">
                                    <span>No. Urut</span>
                                    <span class="text-lg">{{ str_pad($kandidat->no_urut, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <h3 class="text-2xl font-black text-white leading-tight drop-shadow-md">
                                    {{ $kandidat->nama_ketua }}
                                </h3>
                                <p class="text-blue-100 font-medium text-sm drop-shadow-sm">& {{ $kandidat->nama_wakil }}</p>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 md:p-8 flex flex-col flex-1 bg-white relative z-20">
                            <div class="flex-1 space-y-4">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Visi & Misi</span>
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-slate-600 text-sm italic leading-relaxed line-clamp-3 group-hover:line-clamp-none transition-all duration-300">
                                        {{ $kandidat->visi ?: 'Visi dan misi pasangan calon ini sedang dalam penyempurnaan.' }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col gap-3">
                                <a href="{{ route('student.kandidat.show', $kandidat->id) }}"
                                    class="w-full inline-flex justify-center items-center px-4 py-3 rounded-xl transition-all border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 text-sm">
                                    Lihat Detail Kandidat
                                </a>

                                <button
                                    @click="openModal('{{ $kandidat->id }}', {{ json_encode($kandidat->nama_ketua . ' & ' . $kandidat->nama_wakil) }}, '{{ $kandidat->no_urut }}', 'presma')"
                                    :disabled="hasVotedPresma || isVotingClosed"
                                    class="w-full inline-flex justify-center items-center px-4 py-4 rounded-xl transition-all font-bold text-white shadow-lg shadow-blue-500/20 relative overflow-hidden"
                                    :class="(hasVotedPresma || isVotingClosed) ? 'bg-slate-300 cursor-not-allowed opacity-50' : 'bg-slate-900 hover:bg-blue-600 hover:shadow-blue-500/40 hover:-translate-y-1'">
                                    
                                    <span class="relative z-10 flex items-center gap-2">
                                        <span x-show="!hasVotedPresma && !isVotingClosed" class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Pilih Paslon {{ str_pad($kandidat->no_urut, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span x-show="hasVotedPresma">Telah Memilih</span>
                                        <span x-show="!hasVotedPresma && isVotingClosed">Waktu Habis</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- DPM Section -->
        @if(isset($calonDpms) && $calonDpms->count() > 0)
        <div class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Daftar Calon DPM
                </h2>
                <span class="text-sm text-slate-500">Dewan Perwakilan Mahasiswa</span>
            </div>

            {{-- Mobile: Horizontal Scroll (Snap), Desktop: Grid --}}
            <div class="flex md:grid md:grid-cols-2 lg:grid-cols-3 gap-6 overflow-x-auto pb-8 snap-x snap-mandatory scrollbar-hide md:overflow-visible md:pb-0">
                @foreach($calonDpms as $dpm)
                    <div :class="hasVotedDpm ? 'opacity-80 grayscale contrast-75 cursor-not-allowed transform scale-95' : 'hover:shadow-2xl hover:border-purple-300/50 hover:-translate-y-2'"
                        class="group bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-xl transition-all duration-500 flex flex-col h-full relative w-[85vw] sm:w-[400px] flex-shrink-0 snap-center">
                        
                         <!-- Voted Overlay -->
                        <template x-if="hasVotedDpm">
                            <div class="absolute inset-0 z-50 bg-slate-900/10 backdrop-blur-[2px] flex items-center justify-center">
                                <div class="bg-white/90 px-6 py-3 rounded-2xl shadow-lg border border-slate-200">
                                    <span class="text-slate-500 font-bold uppercase tracking-widest text-sm flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Sesi Terkunci
                                    </span>
                                </div>
                            </div>
                        </template>

                        <!-- Photo Area -->
                        <div class="h-64 bg-slate-100 relative overflow-hidden flex items-center justify-center group-hover:h-72 transition-all duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent z-10"></div>
                            @if($dpm->foto)
                                <img src="{{ asset($dpm->foto) }}" alt="{{ $dpm->nama }}"
                                    class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="text-slate-300 flex flex-col items-center">
                                    <svg class="w-20 h-20 mb-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                    <span class="text-xs font-bold uppercase tracking-widest">No Photo</span>
                                </div>
                            @endif
                            
                             <div class="absolute bottom-0 left-0 right-0 p-6 z-20 translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                                <div class="inline-flex items-center gap-2 bg-purple-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg mb-2 shadow-lg">
                                    <span>No. Urut</span>
                                    <span class="text-lg">{{ str_pad($dpm->urutan_tampil, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <h3 class="text-2xl font-black text-white leading-tight drop-shadow-md">
                                    {{ $dpm->nama }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 rounded bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wide border border-white/10">{{ $dpm->prodi }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                         <div class="p-6 md:p-8 flex flex-col flex-1 bg-white relative z-20">
                             <div class="flex-1 space-y-4">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Visi & Misi</span>
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-slate-600 text-sm italic leading-relaxed line-clamp-3 group-hover:line-clamp-none transition-all duration-300">
                                        {{ $dpm->visi ?: 'Visi dan misi calon ini sedang dalam penyempurnaan.' }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col gap-3">
                                <button
                                    @click="openModal('{{ $dpm->id }}', {{ json_encode($dpm->nama) }}, '{{ $dpm->urutan_tampil }}', 'dpm')"
                                    :disabled="hasVotedDpm || isVotingClosed"
                                     class="w-full inline-flex justify-center items-center px-4 py-4 rounded-xl transition-all font-bold text-white shadow-lg shadow-purple-500/20 relative overflow-hidden"
                                    :class="(hasVotedDpm || isVotingClosed) ? 'bg-slate-300 cursor-not-allowed opacity-50' : 'bg-slate-900 hover:bg-purple-600 hover:shadow-purple-500/40 hover:-translate-y-1'">
                                    
                                     <span class="relative z-10 flex items-center gap-2">
                                        <span x-show="!hasVotedDpm && !isVotingClosed" class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Pilih DPM {{ str_pad($dpm->urutan_tampil, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span x-show="hasVotedDpm">Telah Memilih</span>
                                        <span x-show="!hasVotedDpm && isVotingClosed">Waktu Habis</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="mt-16 bg-blue-50/50 border border-blue-100 p-8 rounded-3xl text-center">
            <h3 class="text-blue-900 font-bold text-lg">Tidak ada calon DPM</h3>
            <p class="text-blue-600 text-sm mt-1">Saat ini belum ada data calon DPM yang tersedia.</p>
        </div>
        @endif

        <!-- Vote Confirmation Modal -->
        <dialog x-ref="confirmModal"
            class="backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm bg-transparent p-0 w-full max-w-lg open:animate-fade-in-up">
            <div
                class="bg-white/80 backdrop-blur-2xl rounded-3xl p-1 shadow-2xl border border-white/50 ring-1 ring-white/60 mx-4">
                <div class="bg-white/50 rounded-[1.2rem] p-6 lg:p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-12 h-12 bg-blue-100/50 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm border border-blue-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Konfirmasi Pilihan</h3>
                            <p class="text-sm text-slate-500 font-medium">Langkah Terakhir</p>
                        </div>
                    </div>

                    <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100/50 mb-8 backdrop-blur-sm">
                        <p class="text-slate-700 text-sm leading-relaxed">
                            Anda akan memberikan suara <span x-text="voteType === 'presma' ? 'Presiden Mahasiswa' : 'Anggota DPM'"></span> untuk paslon <strong
                                x-text="'No. ' + (selectedKandidat?.no || '') + ' (' + (selectedKandidat?.name || '') + ')'"></strong>.
                            <br><br>
                            <span
                                class="text-red-500 font-bold text-xs uppercase tracking-wide flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Peringatan Penting
                            </span>
                            <span class="block mt-1 text-slate-500 text-xs">Pilihan yang sudah disimpan tidak dapat
                                diubah kembali.</span>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('vote.store') }}" @submit="submitting = true">
                        @csrf
                        <input type="hidden" name="type" :value="voteType">
                        <input type="hidden" name="kandidat_id" :value="selectedKandidat?.id">

                        <div class="flex gap-3 justify-end">
                            <button type="button" @click="$refs.confirmModal.close()" :disabled="submitting"
                                class="px-5 py-2.5 text-slate-600 hover:bg-slate-50/50 hover:text-slate-900 rounded-xl font-bold transition-all border border-transparent hover:border-slate-200">
                                Batalkan
                            </button>
                            <button type="submit" :disabled="submitting"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all hover:scale-[1.02] hover:shadow-blue-500/40 flex items-center gap-2">
                                <span x-show="submitting"
                                    class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <span x-text="submitting ? 'Menyimpan...' : 'Ya, Saya Yakin'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </dialog>
    </div>
</x-layouts.mahasiswa>