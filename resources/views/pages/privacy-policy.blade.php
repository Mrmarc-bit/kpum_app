<x-layouts.guest title="Privacy Policy - KPUM UNUGHA">

    {{-- Shared Navbar --}}
    @include('pages._navbar')

    {{-- Hero --}}
    <section class="pt-32 pb-16 relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808008_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        <div class="absolute top-20 right-20 w-72 h-72 bg-blue-300/20 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-0 left-10 w-64 h-64 bg-indigo-300/20 rounded-full blur-[80px]"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-100 border border-blue-200 text-blue-700 text-xs font-bold uppercase tracking-widest mb-6">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Privasi & Keamanan
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight mb-4">Privacy Policy</h1>
            <p class="text-lg text-slate-600 font-medium max-w-2xl mx-auto">Kami berkomitmen untuk melindungi privasi dan keamanan data Anda sebagai bagian dari sistem pemilu mahasiswa yang transparan dan terpercaya.</p>
            <div class="mt-6 inline-flex items-center gap-2 text-sm text-slate-500 font-medium bg-white/60 border border-slate-200 rounded-full px-4 py-2 backdrop-blur-md">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Terakhir diperbarui: Februari 2026
            </div>
        </div>
    </section>

    {{-- Content --}}
    <main class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Table of Contents --}}
            <div class="bg-blue-50 border border-blue-100 rounded-3xl p-6 mb-12">
                <h2 class="text-sm font-bold text-blue-700 uppercase tracking-wider mb-4">Daftar Isi</h2>
                <ol class="space-y-2">
                    @foreach([
                        '1. Informasi yang Kami Kumpulkan',
                        '2. Cara Kami Menggunakan Informasi',
                        '3. Penyimpanan & Keamanan Data',
                        '4. Berbagi Informasi',
                        '5. Hak-hak Anda',
                        '6. Cookie & Teknologi Pelacakan',
                        '7. Perubahan Kebijakan',
                        '8. Kontak Kami',
                    ] as $i => $item)
                    <li>
                        <a href="#section-{{ $i+1 }}" class="flex items-center gap-3 text-blue-600 hover:text-blue-800 font-semibold text-sm transition-colors group">
                            <span class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-xs font-black text-blue-700 group-hover:bg-blue-600 group-hover:text-white transition-all">{{ $i+1 }}</span>
                            {{ $item }}
                        </a>
                    </li>
                    @endforeach
                </ol>
            </div>

            {{-- Sections --}}
            <div class="prose prose-slate max-w-none space-y-12">

                <section id="section-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">1. Informasi yang Kami Kumpulkan</h2>
                    </div>
                    <div class="pl-13 space-y-4 text-slate-600 leading-relaxed ml-13">
                        <p>Sistem KPUM mengumpulkan informasi berikut saat Anda menggunakan platform pemilihan elektronik kami:</p>
                        <div class="grid sm:grid-cols-2 gap-4">
                            @foreach([
                                ['icon'=>'🎓','title'=>'Data Akademik','desc'=>'NIM, nama lengkap, program studi, dan fakultas yang berasal dari data DPT resmi universitas.'],
                                ['icon'=>'📧','title'=>'Data Kontak','desc'=>'Alamat email untuk pengiriman bukti suara dan notifikasi pemilihan.'],
                                ['icon'=>'🗳️','title'=>'Data Pemilihan','desc'=>'Pilihan suara Anda yang dienkripsi menggunakan teknologi blockchain dan tidak dapat dilacak kembali ke identitas Anda.'],
                                ['icon'=>'💻','title'=>'Data Teknis','desc'=>'Alamat IP, jenis browser, dan timestamp untuk keperluan keamanan dan pencegahan kecurangan.'],
                            ] as $item)
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                <div class="text-2xl mb-2">{{ $item['icon'] }}</div>
                                <h3 class="font-bold text-slate-800 mb-1">{{ $item['title'] }}</h3>
                                <p class="text-sm text-slate-600">{{ $item['desc'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                <section id="section-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">2. Cara Kami Menggunakan Informasi</h2>
                    </div>
                    <div class="ml-13 space-y-3">
                        @foreach([
                            'Verifikasi identitas mahasiswa sebagai pemilih terdaftar (DPT)',
                            'Memastikan setiap mahasiswa hanya dapat memberikan satu suara',
                            'Mengirimkan bukti suara digital ke email yang terdaftar',
                            'Menghasilkan laporan statistik partisipasi pemilu yang bersifat anonim',
                            'Mendeteksi dan mencegah upaya kecurangan atau manipulasi sistem',
                            'Memenuhi persyaratan audit dan akuntabilitas penyelenggaraan pemilu',
                        ] as $item)
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="text-slate-700 text-sm font-medium">{{ $item }}</p>
                        </div>
                        @endforeach
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                <section id="section-3">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">3. Penyimpanan & Keamanan Data</h2>
                    </div>
                    <div class="ml-13 space-y-4">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                            <p class="text-slate-700 leading-relaxed mb-4">Data Anda disimpan dengan standar keamanan tinggi menggunakan enkripsi <strong class="text-slate-900">AES-256</strong> dan teknologi <strong class="text-slate-900">Blockchain</strong> untuk memastikan integritas data pemilihan.</p>
                            <div class="grid sm:grid-cols-3 gap-3">
                                @foreach([
                                    ['🔐','AES-256 Encryption','Enkripsi tingkat militer untuk semua data sensitif'],
                                    ['⛓️','Blockchain Proof','Jejak kriptografi yang tidak dapat dimanipulasi'],
                                    ['🔒','Zero-Knowledge','Suara Anda privat bahkan dari administrator sistem'],
                                ] as $f)
                                <div class="bg-white/70 rounded-xl p-4 text-center border border-green-100">
                                    <div class="text-2xl mb-2">{{ $f[0] }}</div>
                                    <div class="font-bold text-slate-800 text-sm">{{ $f[1] }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $f[2] }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed">Data pemilihan disimpan selama 7 (tujuh) tahun sesuai ketentuan arsip organisasi kemahasiswaan, setelah itu akan dihapus secara permanen.</p>
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                <section id="section-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">4. Berbagi Informasi</h2>
                    </div>
                    <div class="ml-13 space-y-3">
                        <p class="text-slate-600">Kami <strong class="text-red-600">tidak menjual, menyewakan, atau memperdagangkan</strong> informasi pribadi Anda kepada pihak ketiga. Data hanya dapat dibagikan kepada:</p>
                        @foreach([
                            ['Pimpinan Universitas', 'Hanya data statistik anonim untuk laporan pemilihan', 'bg-blue-50 border-blue-100'],
                            ['Panitia KPUM', 'Data yang diperlukan untuk penyelenggaraan teknis pemilu', 'bg-slate-50 border-slate-100'],
                            ['Penegak Hukum', 'Hanya jika diwajibkan oleh hukum yang berlaku dengan surat resmi', 'bg-red-50 border-red-100'],
                        ] as $s)
                        <div class="flex gap-4 p-4 rounded-xl border {{ $s[2] }}">
                            <div class="shrink-0 w-2 h-auto rounded-full bg-current opacity-30"></div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm">{{ $s[0] }}</div>
                                <div class="text-sm text-slate-600 mt-0.5">{{ $s[1] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                <section id="section-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-violet-100 flex items-center justify-center text-violet-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">5. Hak-hak Anda</h2>
                    </div>
                    <div class="ml-13 grid sm:grid-cols-2 gap-4">
                        @foreach([
                            ['Akses Data','Anda berhak meminta salinan data pribadi yang kami miliki tentang Anda.'],
                            ['Koreksi Data','Anda dapat meminta koreksi data yang tidak akurat melalui panitia KPUM.'],
                            ['Hapus Data','Setelah periode pemilihan berakhir, Anda dapat meminta penghapusan data non-esensial.'],
                            ['Portabilitas','Anda berhak menerima data Anda dalam format yang terstruktur dan dapat dibaca mesin.'],
                        ] as $r)
                        <div class="p-5 bg-violet-50 rounded-2xl border border-violet-100">
                            <h3 class="font-bold text-slate-800 mb-2">{{ $r[0] }}</h3>
                            <p class="text-sm text-slate-600">{{ $r[1] }}</p>
                        </div>
                        @endforeach
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                <section id="section-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">6. Cookie & Teknologi Pelacakan</h2>
                    </div>
                    <div class="ml-13 space-y-3 text-slate-600">
                        <p>Kami menggunakan cookie sesi yang diperlukan untuk menjaga keamanan login dan mencegah serangan CSRF. Cookie ini bersifat <strong class="text-slate-800">esensial</strong> dan tidak dapat dinonaktifkan.</p>
                        <div class="bg-orange-50 border border-orange-100 rounded-2xl p-5">
                            <p class="text-sm font-semibold text-orange-800">⚠️ Catatan Penting</p>
                            <p class="text-sm text-orange-700 mt-1">Kami tidak menggunakan cookie pelacak atau analitik pihak ketiga (seperti Google Analytics) yang dapat mengidentifikasi Anda lintas situs.</p>
                        </div>
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                <section id="section-7">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">7. Perubahan Kebijakan</h2>
                    </div>
                    <div class="ml-13 text-slate-600">
                        <p>Kebijakan privasi ini dapat diperbarui sewaktu-waktu. Perubahan signifikan akan diberitahukan melalui pengumuman di halaman utama atau melalui email. Dengan terus menggunakan sistem ini, Anda menyetujui kebijakan privasi yang berlaku.</p>
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                <section id="section-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">8. Kontak Kami</h2>
                    </div>
                    <div class="ml-13">
                        <p class="text-slate-600 mb-4">Jika Anda memiliki pertanyaan atau kekhawatiran mengenai kebijakan privasi ini, silakan hubungi kami:</p>
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                            <div class="font-black text-slate-900 text-lg mb-1">KPUM UNUGHA</div>
                            <div class="text-slate-600 text-sm space-y-1">
                                <p>Universitas Nahdlatul Ulama Al-Ghazali Cilacap</p>
                                <p>Jl. Kemerdekaan Barat No.17, Kesugihan Kidul, Kec. Kesugihan, Kab. Cilacap, Jawa Tengah 53274</p>
                            </div>
                            <a href="{{ route('contact-support') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                Hubungi Support →
                            </a>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </main>

    {{-- Footer --}}
    @include('pages._footer')

</x-layouts.guest>
