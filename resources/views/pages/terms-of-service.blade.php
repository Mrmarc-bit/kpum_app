<x-layouts.guest title="Terms of Service - KPUM UNUGHA">

    {{-- Shared Navbar --}}
    @include('pages._navbar')

    {{-- Hero --}}
    <section class="pt-32 pb-16 relative overflow-hidden bg-gradient-to-br from-slate-50 via-indigo-50/30 to-violet-50/50">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808008_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        <div class="absolute top-20 left-20 w-72 h-72 bg-indigo-300/20 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-0 right-10 w-64 h-64 bg-violet-300/20 rounded-full blur-[80px]"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-100 border border-indigo-200 text-indigo-700 text-xs font-bold uppercase tracking-widest mb-6">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Syarat & Ketentuan
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight mb-4">Terms of Service</h1>
            <p class="text-lg text-slate-600 font-medium max-w-2xl mx-auto">Syarat dan ketentuan penggunaan platform pemilihan elektronik KPUM Universitas Nahdlatul Ulama Al-Ghazali Cilacap.</p>
            <div class="mt-6 inline-flex items-center gap-2 text-sm text-slate-500 font-medium bg-white/60 border border-slate-200 rounded-full px-4 py-2 backdrop-blur-md">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Berlaku sejak: Februari 2026
            </div>
        </div>
    </section>

    {{-- Acceptance Banner --}}
    <div class="bg-indigo-700 text-white py-4 px-4">
        <div class="max-w-4xl mx-auto flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0 text-indigo-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
            <p class="text-sm font-medium text-indigo-100">Dengan menggunakan platform KPUM, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan ini.</p>
        </div>
    </div>

    {{-- Content --}}
    <main class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Sections --}}
            <div class="space-y-12">

                {{-- 1 --}}
                <section>
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black shrink-0 shadow-lg shadow-indigo-500/30">1</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Penerimaan Syarat</h2>
                            <p class="text-slate-600 mt-2 leading-relaxed">Dengan mengakses atau menggunakan sistem pemilihan elektronik KPUM, Anda menyetujui untuk terikat oleh syarat dan ketentuan ini. Jika Anda tidak menyetujui syarat ini, harap tidak menggunakan sistem ini.</p>
                        </div>
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                {{-- 2 --}}
                <section>
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black shrink-0 shadow-lg shadow-indigo-500/30">2</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Kelayakan Pengguna</h2>
                            <p class="text-slate-600 mt-2 leading-relaxed mb-4">Platform ini hanya dapat digunakan oleh pengguna yang memenuhi kriteria berikut:</p>
                        </div>
                    </div>
                    <div class="ml-14 space-y-3">
                        @foreach([
                            ['✅','Mahasiswa aktif','Terdaftar sebagai mahasiswa aktif di UNUGHA Cilacap pada semester yang bersangkutan'],
                            ['✅','Terdaftar DPT','Terdaftar dalam Daftar Pemilih Tetap (DPT) yang telah diverifikasi oleh panitia KPUM'],
                            ['✅','NIM Valid','Memiliki Nomor Induk Mahasiswa (NIM) yang valid dan aktif dalam sistem universitas'],
                            ['❌','Tidak Pernah Kena Sanksi','Tidak sedang menjalani sanksi akademik atau organisasi yang menghilangkan hak pilih'],
                        ] as $e)
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-lg shrink-0">{{ $e[0] }}</span>
                            <div>
                                <div class="font-bold text-slate-800 text-sm">{{ $e[1] }}</div>
                                <div class="text-sm text-slate-600 mt-0.5">{{ $e[2] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                {{-- 3 --}}
                <section>
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black shrink-0 shadow-lg shadow-indigo-500/30">3</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Kewajiban Pengguna</h2>
                            <p class="text-slate-600 mt-2 mb-4 leading-relaxed">Sebagai pengguna terdaftar, Anda berkewajiban untuk:</p>
                        </div>
                    </div>
                    <div class="ml-14 grid sm:grid-cols-2 gap-4">
                        @foreach([
                            ['Kerahasiaan Akses','Menjaga kerahasiaan kode akses/KTA Anda dan tidak membagikannya kepada pihak lain'],
                            ['Penggunaan Personal','Hanya menggunakan akun untuk kepentingan pribadi dan tidak mentransfer hak akses'],
                            ['Pelaporan Insiden','Segera melaporkan jika mendeteksi adanya anomali, bug, atau upaya kecurangan dalam sistem'],
                            ['Informasi Akurat','Memastikan semua informasi yang Anda berikan adalah benar dan akurat'],
                            ['Satu Suara','Hanya memberikan satu suara selama periode pemilihan berlangsung'],
                            ['Kepatuhan Aturan','Mengikuti semua peraturan pemilihan yang ditetapkan oleh panitia KPUM'],
                        ] as $k)
                        <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                            <div class="font-bold text-slate-800 text-sm mb-1.5">{{ $k[0] }}</div>
                            <div class="text-xs text-slate-600">{{ $k[1] }}</div>
                        </div>
                        @endforeach
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                {{-- 4 --}}
                <section>
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-red-600 flex items-center justify-center text-white font-black shrink-0 shadow-lg shadow-red-500/30">4</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Larangan & Tindakan Terlarang</h2>
                        </div>
                    </div>
                    <div class="ml-14">
                        <div class="bg-red-50 border border-red-100 rounded-2xl p-6 space-y-3">
                            <p class="text-sm font-semibold text-red-800 mb-4">Kegiatan berikut <strong>DILARANG KERAS</strong> dan dapat berakibat sanksi hukum:</p>
                            @foreach([
                                'Mencoba membobol, meretas, atau memanipulasi sistem pemilihan',
                                'Menggunakan bot, skrip otomatis, atau tools untuk mempengaruhi hasil pemilihan',
                                'Mencuri identitas atau menggunakan akun milik mahasiswa lain',
                                'Menyebarkan informasi palsu atau kampanye hitam melalui platform',
                                'Mencoba mengakses data pemilihan milik mahasiswa lain',
                                'Melakukan denial-of-service attack atau upaya untuk melumpuhkan sistem',
                                'Memfoto atau merekam layar pada saat proses pemilihan berlangsung',
                            ] as $l)
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                <p class="text-sm text-red-700 font-medium">{{ $l }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                {{-- 5 --}}
                <section>
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black shrink-0 shadow-lg shadow-indigo-500/30">5</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Ketersediaan Layanan</h2>
                            <p class="text-slate-600 mt-2 leading-relaxed">Sistem pemilihan hanya dapat diakses selama periode yang telah ditetapkan oleh panitia KPUM. Kami berhak untuk menghentikan, menangguhkan, atau membatasi akses kapan saja untuk alasan teknis, keamanan, atau administratif.</p>
                        </div>
                    </div>
                    <div class="ml-14 bg-amber-50 border border-amber-100 rounded-2xl p-5">
                        <p class="text-sm text-amber-800 font-semibold">⏰ Catatan Waktu Pemilihan</p>
                        <p class="text-sm text-amber-700 mt-1">Suara yang diberikan di luar periode voting yang sah tidak akan dihitung. Pemilih bertanggung jawab untuk memastikan mereka memberikan suara dalam waktu yang ditentukan.</p>
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                {{-- 6 --}}
                <section>
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black shrink-0 shadow-lg shadow-indigo-500/30">6</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Batasan Tanggung Jawab</h2>
                            <p class="text-slate-600 mt-2 leading-relaxed">KPUM tidak bertanggung jawab atas kerugian yang timbul dari: gangguan jaringan internet di sisi pengguna, perangkat yang tidak kompatibel, atau kegagalan menyimpan bukti pilih secara mandiri. Kami menyediakan sistem cadangan namun tidak dapat menjamin ketersediaan 100% selama periode puncak.</p>
                        </div>
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                {{-- 7 --}}
                <section>
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black shrink-0 shadow-lg shadow-indigo-500/30">7</div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Hukum yang Berlaku</h2>
                            <p class="text-slate-600 mt-2 leading-relaxed">Syarat dan ketentuan ini diatur oleh hukum Republik Indonesia. Setiap perselisihan yang timbul dari penggunaan platform ini akan diselesaikan melalui jalur musyawarah, dan jika tidak tercapai kesepakatan, akan diselesaikan melalui mekanisme yang ditetapkan oleh Rektor UNUGHA Cilacap.</p>
                        </div>
                    </div>
                </section>

                {{-- CTA --}}
                <div class="bg-gradient-to-r from-indigo-600 to-violet-600 rounded-3xl p-8 text-white text-center">
                    <h3 class="text-2xl font-black mb-2">Ada Pertanyaan?</h3>
                    <p class="text-indigo-200 mb-6">Tim support kami siap membantu Anda memahami syarat dan ketentuan ini.</p>
                    <a href="{{ route('contact-support') }}" class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-6 py-3 rounded-2xl hover:bg-indigo-50 transition-colors shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Hubungi Support
                    </a>
                </div>

            </div>
        </div>
    </main>

    @include('pages._footer')

</x-layouts.guest>
