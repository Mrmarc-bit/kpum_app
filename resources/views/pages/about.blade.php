<x-layouts.guest title="Tentang KPUM UNUGHA - Pemilwa Universitas Nahdlatul Ulama Al Ghazali">

    {{-- Shared Navbar --}}
    @include('pages._navbar')

    {{-- Hero --}}
    <section class="pt-32 pb-16 relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808008_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        <div class="absolute top-20 right-20 w-72 h-72 bg-blue-300/20 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-0 left-10 w-64 h-64 bg-indigo-300/20 rounded-full blur-[80px]"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-100 border border-blue-200 text-blue-700 text-xs font-bold uppercase tracking-widest mb-6">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                Profil Organisasi
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight mb-4">Tentang KPUM UNUGHA</h1>
            <p class="text-lg text-slate-600 font-medium max-w-2xl mx-auto">Komisi Pemilihan Umum Mahasiswa (KPUM) adalah lembaga independen yang bertanggung jawab menyelenggarakan pesta demokrasi mahasiswa di lingkungan UNUGHA Cilacap.</p>
        </div>
    </section>

    {{-- Content --}}
    <main class="py-16 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Image/Logo Focus -->
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-[3rem] opacity-10 blur-2xl animate-pulse"></div>
                    <div class="relative bg-white rounded-[3rem] p-8 md:p-12 border border-slate-100 shadow-2xl flex items-center justify-center">
                        @if(setting('app_logo'))
                            <img src="{{ asset(setting('app_logo')) }}" alt="Logo KPUM" class="w-64 h-64 object-contain">
                        @else
                            <div class="w-64 h-64 bg-blue-600 rounded-full flex items-center justify-center text-white text-9xl font-black">K</div>
                        @endif
                    </div>

                    <!-- Floating Stats -->
                    <div class="absolute -bottom-6 -right-6 bg-white p-6 rounded-3xl shadow-xl border border-slate-100 animate-bounce transition-all duration-1000">
                        <div class="text-blue-600 font-black text-3xl">2026</div>
                        <div class="text-slate-500 text-xs font-bold uppercase tracking-widest">E-Voting Era</div>
                    </div>
                </div>

                <!-- Text Content -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Visi & Misi</h2>
                        <div class="w-12 h-1.5 bg-blue-600 rounded-full mb-6"></div>
                        <p class="text-slate-600 leading-relaxed mb-6 font-medium">Mewujudkan sistem pemilihan mahasiswa yang modern, berintegritas, dan menjunjung tinggi nilai-nilai demokrasi demi terciptanya kepemimpinan mahasiswa yang kredibel.</p>

                        <ul class="space-y-4">
                            @foreach([
                                'Menyelenggarakan pemilu raya dengan asas LUBER JURDIL.',
                                'Implementasi teknologi digital (e-voting) yang aman dan transparan.',
                                'Meningkatkan partisipasi aktif mahasiswa dalam demokrasi kampus.',
                                'Menjaga netralitas dan independensi sebagai penyelenggara pemilu.'
                            ] as $misi)
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </div>
                                <span class="text-slate-600 font-medium">{{ $misi }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <h3 class="font-bold text-slate-900 mb-2">Independen</h3>
                            <p class="text-sm text-slate-500">KPUM bekerja secara mandiri tanpa intervensi dari pihak luar manapun.</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <h3 class="font-bold text-slate-900 mb-2">Transparan</h3>
                            <p class="text-sm text-slate-500">Seluruh proses dan hasil pemilihan dapat diaudit dan dipertanggungjawabkan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team / Structural Focus -->
            <div class="mt-32">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Struktur Organisasi</h2>
                    <p class="text-slate-500 font-medium">Pengurus KPUM periode 2026 yang berdedikasi.</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach([
                        ['Jabatan' => 'Ketua Umum', 'Nama' => 'Fulan bin Fulan'],
                        ['Jabatan' => 'Sekretaris', 'Nama' => 'Fulanah binti Fulan'],
                        ['Jabatan' => 'Bendahara', 'Nama' => 'Fulanah'],
                        ['Jabatan' => 'Divisi Teknis', 'Nama' => 'Tim Technical Support'],
                    ] as $staff)
                    <div class="group relative bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-2xl mx-auto mb-6 flex items-center justify-center text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h4 class="font-black text-slate-900 mb-1 text-lg">{{ $staff['Nama'] }}</h4>
                        <p class="text-blue-600 text-xs font-bold uppercase tracking-widest">{{ $staff['Jabatan'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    @include('pages._footer')

</x-layouts.guest>
