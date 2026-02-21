<x-layouts.guest title="Dokumentasi - KPUM UNUGHA">

    {{-- Shared Navbar --}}
    @include('pages._navbar')

    {{-- Hero --}}
    <section class="pt-32 pb-16 relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff06_1px,transparent_1px),linear-gradient(to_bottom,#ffffff06_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        <div class="absolute top-20 right-20 w-96 h-96 bg-blue-600/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-10 w-72 h-72 bg-indigo-600/20 rounded-full blur-[100px]"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-blue-300 text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-md">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Panduan Penggunaan
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight mb-4">Dokumentasi Sistem</h1>
            <p class="text-lg text-blue-200 font-medium max-w-2xl mx-auto">Panduan lengkap penggunaan platform pemilihan elektronik KPUM untuk semua pengguna.</p>

            {{-- Search Bar --}}
            <div class="mt-8 max-w-lg mx-auto">
                <div class="relative">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input id="doc-search" type="text" placeholder="Cari dokumen, panduan, atau topik..." class="w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl pl-12 pr-4 py-4 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all text-sm font-medium">
                </div>
            </div>
        </div>
    </section>

    <main class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Quick Access --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-16">
                @foreach([
                    ['href'=>'#panduan-mahasiswa','icon'=>'🎓','label'=>'Panduan Mahasiswa','color'=>'bg-blue-50 border-blue-100 text-blue-700 hover:bg-blue-100'],
                    ['href'=>'#sistem-keamanan','icon'=>'🔐','label'=>'Sistem Keamanan','color'=>'bg-green-50 border-green-100 text-green-700 hover:bg-green-100'],
                    ['href'=>'#troubleshooting','icon'=>'🛠️','label'=>'Troubleshooting','color'=>'bg-amber-50 border-amber-100 text-amber-700 hover:bg-amber-100'],
                    ['href'=>'#peran-akses','icon'=>'👥','label'=>'Peran & Akses','color'=>'bg-violet-50 border-violet-100 text-violet-700 hover:bg-violet-100'],
                ] as $q)
                <a href="{{ $q['href'] }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl border {{ $q['color'] }} transition-all hover:-translate-y-0.5 text-center">
                    <span class="text-2xl">{{ $q['icon'] }}</span>
                    <span class="text-xs font-bold">{{ $q['label'] }}</span>
                </a>
                @endforeach
            </div>

            {{-- Section: Panduan Mahasiswa --}}
            <section id="panduan-mahasiswa" class="mb-16 scroll-mt-24">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900">Panduan Mahasiswa</h2>
                        <p class="text-slate-500 text-sm">Cara menggunakan hak pilih di platform KPUM</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @php
                    $steps = [
                        ['step'=>'01','title'=>'Cek Status DPT Anda','desc'=>'Sebelum hari pemilihan, pastikan Anda terdaftar sebagai pemilih dengan menggunakan fitur "Cek DPT" di halaman utama. Masukkan NIM Anda untuk verifikasi.','badge'=>'Pra-Pemilihan','badgeColor'=>'bg-slate-100 text-slate-600'],
                        ['step'=>'02','title'=>'Akses Platform Voting','desc'=>'Klik tombol "Masuk & Vote" di halaman utama. Anda akan diarahkan ke halaman autentikasi menggunakan KTA (Kartu Tanda Anggota) atau NIM mahasiswa.','badge'=>'Hari Pemilihan','badgeColor'=>'bg-blue-100 text-blue-700'],
                        ['step'=>'03','title'=>'Login dengan Kredensial','desc'=>'Masukkan NIM atau nomor KTA Anda. Sistem akan memverifikasi identitas Anda secara otomatis. Proses autentikasi aman dan terenkripsi.','badge'=>'Autentikasi','badgeColor'=>'bg-indigo-100 text-indigo-700'],
                        ['step'=>'04','title'=>'Pilih Kandidat di Bilik Suara','desc'=>'Setelah login, Anda akan masuk ke "Bilik Suara Digital". Pelajari visi-misi setiap kandidat, lalu klik kandidat pilihan Anda. Konfirmasi pilihan pada dialog yang muncul.','badge'=>'Proses Voting','badgeColor'=>'bg-green-100 text-green-700'],
                        ['step'=>'05','title'=>'Unduh Bukti Pilih','desc'=>'Setelah berhasil memilih, sistem akan mengirimkan bukti pilih ke email Anda dan menyediakan opsi unduh PDF. Simpan bukti ini sebagai dokumentasi partisipasi Anda.','badge'=>'Pasca-Voting','badgeColor'=>'bg-amber-100 text-amber-700'],
                    ];
                    @endphp

                    @foreach($steps as $i => $step)
                    <div class="flex gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="shrink-0">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-blue-500/20">{{ $step['step'] }}</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <h3 class="font-black text-slate-900">{{ $step['title'] }}</h3>
                                <span class="inline-block px-2.5 py-0.5 rounded-full {{ $step['badgeColor'] }} text-xs font-bold">{{ $step['badge'] }}</span>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- Section: Keamanan --}}
            <section id="sistem-keamanan" class="mb-16 scroll-mt-24">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-green-600 flex items-center justify-center text-white shadow-lg shadow-green-500/30 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900">Sistem Keamanan</h2>
                        <p class="text-slate-500 text-sm">Arsitektur keamanan berlapis yang melindungi integritas pemilu</p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    @foreach([
                        ['icon'=>'🔐','title'=>'Enkripsi AES-256','desc'=>'Semua data sensitif dienkripsi menggunakan standar enkripsi militer AES-256 sebelum disimpan ke database.','color'=>'border-l-4 border-green-500 bg-green-50'],
                        ['icon'=>'⛓️','title'=>'Blockchain Integrity','desc'=>'Setiap suara menghasilkan hash kriptografi yang disimpan dalam rantai yang tidak dapat dimanipulasi.','color'=>'border-l-4 border-blue-500 bg-blue-50'],
                        ['icon'=>'🛡️','title'=>'CSRF Protection','desc'=>'Semua form dilindungi oleh token CSRF untuk mencegah serangan cross-site request forgery.','color'=>'border-l-4 border-indigo-500 bg-indigo-50'],
                        ['icon'=>'🚫','title'=>'Rate Limiting','desc'=>'Pembatasan request otomatis mencegah brute force attack dan serangan DDoS pada endpoint kritis.','color'=>'border-l-4 border-red-500 bg-red-50'],
                        ['icon'=>'👁️','title'=>'Audit Log','desc'=>'Semua aktivitas sistem tercatat dalam audit log yang komprehensif dan tidak dapat dihapus oleh user biasa.','color'=>'border-l-4 border-amber-500 bg-amber-50'],
                        ['icon'=>'🔏','title'=>'Signed URLs','desc'=>'URL login menggunakan tanda tangan digital (signed URL) untuk memastikan keaslian dan mencegah manipulasi.','color'=>'border-l-4 border-violet-500 bg-violet-50'],
                    ] as $sec)
                    <div class="p-5 rounded-2xl {{ $sec['color'] }}">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl shrink-0">{{ $sec['icon'] }}</span>
                            <div>
                                <h3 class="font-bold text-slate-800 mb-1">{{ $sec['title'] }}</h3>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $sec['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- Section: Troubleshooting --}}
            <section id="troubleshooting" class="mb-16 scroll-mt-24">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900">Troubleshooting</h2>
                        <p class="text-slate-500 text-sm">Solusi untuk masalah umum yang sering ditemui</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach([
                        ['error'=>'Error: "NIM tidak ditemukan"','solutions'=>['Periksa ejaan NIM (harus tanpa spasi/karakter khusus)','Konfirmasi ke panitia bahwa NIM terdaftar di DPT','Gunakan fitur Cek DPT di halaman utama untuk verifikasi'],'severity'=>'red'],
                        ['error'=>'Error: "Sesi telah berakhir" atau otomatis logout','solutions'=>['Refresh halaman dan login kembali','Pastikan browser tidak dalam mode private/incognito','Aktifkan cookie untuk domain ini di pengaturan browser'],'severity'=>'amber'],
                        ['error'=>'Halaman loading sangat lambat atau tidak mau terbuka','solutions'=>['Coba gunakan browser lain (Chrome/Firefox/Edge terbaru)','Bersihkan cache browser (Ctrl+Shift+Delete)','Coba gunakan koneksi internet yang berbeda','Nonaktifkan VPN atau proxy jika aktif'],'severity'=>'blue'],
                        ['error'=>'Tombol "Pilih" tidak bisa diklik','solutions'=>['Pastikan periode voting sedang aktif (cek countdown di halaman utama)','Anda mungkin sudah pernah memilih sebelumnya','Coba refresh halaman','Hubungi panitia jika masalah berlanjut'],'severity'=>'violet'],
                    ] as $t)
                    <div class="border border-{{ $t['severity'] }}-200 rounded-2xl overflow-hidden">
                        <div class="bg-{{ $t['severity'] }}-50 px-6 py-4 flex items-center gap-3">
                            <svg class="w-5 h-5 text-{{ $t['severity'] }}-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span class="font-bold text-{{ $t['severity'] }}-800 text-sm">{{ $t['error'] }}</span>
                        </div>
                        <div class="bg-white px-6 py-4">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Solusi yang dapat dicoba:</p>
                            <ul class="space-y-2">
                                @foreach($t['solutions'] as $sol)
                                <li class="flex items-start gap-2 text-sm text-slate-700">
                                    <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    {{ $sol }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- Section: Peran & Akses --}}
            <section id="peran-akses" class="mb-16 scroll-mt-24">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-violet-600 flex items-center justify-center text-white shadow-lg shadow-violet-500/30 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900">Peran & Hak Akses</h2>
                        <p class="text-slate-500 text-sm">Sistem RBAC (Role-Based Access Control) yang diterapkan</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border border-slate-200 rounded-xl">
                                <th class="px-4 py-3 text-left font-bold text-slate-700 rounded-l-xl">Fitur</th>
                                <th class="px-4 py-3 text-center font-bold text-blue-700 whitespace-nowrap">Mahasiswa</th>
                                <th class="px-4 py-3 text-center font-bold text-amber-700 whitespace-nowrap">KPPS</th>
                                <th class="px-4 py-3 text-center font-bold text-green-700 whitespace-nowrap">Panitia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php
                            $tableRows = [
                                ['feature'=>'Login & Voting','mhs'=>true,'kpps'=>false,'panitia'=>false],
                                ['feature'=>'Cek DPT','mhs'=>true,'kpps'=>true,'panitia'=>true],
                                ['feature'=>'Unduh Bukti Pilih','mhs'=>true,'kpps'=>false,'panitia'=>false],
                                ['feature'=>'Scan QR Absensi','mhs'=>false,'kpps'=>true,'panitia'=>true],
                                ['feature'=>'Manajemen DPT','mhs'=>false,'kpps'=>false,'panitia'=>true],
                                ['feature'=>'Manajemen Kandidat','mhs'=>false,'kpps'=>false,'panitia'=>true],
                                ['feature'=>'Laporan & Rekap','mhs'=>false,'kpps'=>false,'panitia'=>true],
                                ['feature'=>'Audit Log','mhs'=>false,'kpps'=>false,'panitia'=>true],
                                ['feature'=>'Pengaturan Sistem','mhs'=>false,'kpps'=>false,'panitia'=>true],
                                ['feature'=>'Reset Pemilu','mhs'=>false,'kpps'=>false,'panitia'=>true],
                            ];
                            @endphp
                            @foreach($tableRows as $row)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $row['feature'] }}</td>
                                @foreach(['mhs','kpps','panitia'] as $role)
                                <td class="px-4 py-3 text-center">
                                    @if($row[$role])
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600 mx-auto">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </span>
                                    @else
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-400 mx-auto">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Still Need Help --}}
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-8 sm:p-12 text-center text-white">
                <div class="text-4xl mb-4">💬</div>
                <h3 class="text-2xl font-black mb-2">Masih Butuh Bantuan?</h3>
                <p class="text-slate-400 mb-8 max-w-md mx-auto">Dokumentasi ini terus diperbarui. Jika Anda tidak menemukan jawaban yang dicari, tim kami siap membantu.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('contact-support') }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold px-7 py-3.5 rounded-2xl transition-colors shadow-lg shadow-blue-900/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Hubungi Support
                    </a>
                    <a href="{{ route('check-dpt') }}" class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold px-7 py-3.5 rounded-2xl transition-colors border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Cek DPT Saya
                    </a>
                </div>
            </div>

        </div>
    </main>

    @include('pages._footer')

    <script>
        // Simple search functionality
        document.getElementById('doc-search').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            if (query.length < 2) return;
            // Scroll to matching section based on keywords
            const sectionMap = {
                'login': '#panduan-mahasiswa', 'voting': '#panduan-mahasiswa', 'pilih': '#panduan-mahasiswa', 'mahasiswa': '#panduan-mahasiswa',
                'keamanan': '#sistem-keamanan', 'enkripsi': '#sistem-keamanan', 'security': '#sistem-keamanan', 'blockchain': '#sistem-keamanan',
                'error': '#troubleshooting', 'masalah': '#troubleshooting', 'solusi': '#troubleshooting', 'troubleshoot': '#troubleshooting',
                'peran': '#peran-akses', 'admin': '#peran-akses', 'hak': '#peran-akses', 'akses': '#peran-akses',
            };
            for (const [keyword, section] of Object.entries(sectionMap)) {
                if (query.includes(keyword)) {
                    document.querySelector(section)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    break;
                }
            }
        });
    </script>

</x-layouts.guest>
