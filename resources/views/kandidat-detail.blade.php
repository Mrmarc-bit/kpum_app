<x-layouts.mahasiswa title="{{ $title }}">
    <div class="max-w-4xl mx-auto space-y-8 py-8">
        <!-- Back Button -->
        <div>
            <a href="{{ route('student.dashboard') }}"
                class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:underline">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Bilik Suara
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
            <!-- Header -->
            <div class="bg-linear-to-br from-blue-600 to-indigo-700 p-8 lg:p-12 text-white">
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="w-48 h-64 bg-white/10 rounded-2xl overflow-hidden border-4 border-white/20 shrink-0">
                        @if($kandidat->foto)
                            <img src="{{ asset('storage/' . $kandidat->foto) }}" alt="{{ $kandidat->nama_ketua }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white/50">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="text-center md:text-left space-y-4">
                        <div class="inline-block px-4 py-1 bg-white/20 rounded-full text-sm font-bold">
                            Pasangan Calon Nomor Urut #{{ $kandidat->no_urut }}
                        </div>
                        <h1 class="text-3xl lg:text-4xl font-bold leading-tight">
                            {{ $kandidat->nama_ketua }} & {{ $kandidat->nama_wakil }}
                        </h1>
                        <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                            <div class="text-blue-100">
                                <p class="text-[10px] uppercase font-bold opacity-70">Ketua</p>
                                <p class="font-semibold">{{ $kandidat->nama_ketua }}
                                    ({{ $kandidat->prodi_ketua ?: 'Mahasiswa' }})</p>
                            </div>
                            <div class="text-blue-100">
                                <p class="text-[10px] uppercase font-bold opacity-70">Wakil</p>
                                <p class="font-semibold">{{ $kandidat->nama_wakil }}
                                    ({{ $kandidat->prodi_wakil ?: 'Mahasiswa' }})</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 lg:p-12 space-y-12">
                <!-- Visi -->
                <section class="space-y-4">
                    <h2 class="text-2xl font-bold text-slate-900 border-l-4 border-blue-600 pl-4">Visi</h2>
                    <p class="text-lg text-slate-700 leading-relaxed italic">
                        "{{ $kandidat->visi ?: 'Visi belum diisi.' }}"
                    </p>
                </section>

                <!-- Misi -->
                <section class="space-y-6">
                    <h2 class="text-2xl font-bold text-slate-900 border-l-4 border-blue-600 pl-4">Misi</h2>
                    <ul class="space-y-4">
                        @php
                            $misiItems = $kandidat->misi ? explode("\n", $kandidat->misi) : [];
                        @endphp
                        @forelse($misiItems as $item)
                            @if(trim($item))
                                <li class="flex gap-4 items-start">
                                    <div class="mt-1.5 w-2 h-2 rounded-full bg-blue-600 shrink-0"></div>
                                    <p class="text-slate-700 font-medium">{{ trim($item) }}</p>
                                </li>
                            @endif
                        @empty
                            <p class="text-slate-500 italic">Misi belum diisi.</p>
                        @endforelse
                    </ul>
                </section>
            </div>

            <!-- Footer Action -->
            <div class="bg-slate-50 p-8 text-center border-t border-slate-100">
                <a href="{{ route('student.dashboard') }}"
                    class="inline-block px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                    Kembali dan Berikan Suara
                </a>
            </div>
        </div>
    </div>
</x-layouts.mahasiswa>