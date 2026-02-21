<x-layouts.guest title="Contact Support - KPUM UNUGHA">

    {{-- Shared Navbar --}}
    @include('pages._navbar')

    {{-- Hero --}}
    <section class="pt-32 pb-16 relative overflow-hidden bg-gradient-to-br from-slate-50 via-green-50/30 to-teal-50/50">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808008_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        <div class="absolute top-20 right-20 w-72 h-72 bg-green-300/20 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-0 left-10 w-64 h-64 bg-teal-300/20 rounded-full blur-[80px]"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-green-100 border border-green-200 text-green-700 text-xs font-bold uppercase tracking-widest mb-6">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Bantuan & Dukungan
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight mb-4">Contact Support</h1>
            <p class="text-lg text-slate-600 font-medium max-w-2xl mx-auto">Kami siap membantu Anda 24/7. Pilih saluran komunikasi yang paling nyaman untuk Anda.</p>
        </div>
    </section>

    <main class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Contact Cards --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                {{-- WhatsApp --}}
                @php
                    $rawNumber = $settings['contact_person'] ?? '6285183750294';
                    $waNumber  = preg_replace('/[^0-9]/', '', $rawNumber);
                    // Validasi: nomor internasional valid 10–15 digit, fallback ke default
                    if (strlen($waNumber) < 10 || strlen($waNumber) > 15) {
                        $waNumber = '6285183750294';
                    }
                    $waLink = 'https://wa.me/' . $waNumber;
                @endphp
                <a href="{{ $waLink }}" target="_blank" class="group block bg-white rounded-3xl p-7 border border-slate-100 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-green-500/10 hover:border-green-200 hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">WhatsApp</div>
                    <div class="text-lg font-black text-slate-900 group-hover:text-green-600 transition-colors">Chat Sekarang</div>
                    <div class="text-sm text-slate-500 mt-1">Respon dalam hitungan menit</div>
                    <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-green-600">
                        <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                        Online 24/7
                    </div>
                </a>

                {{-- Email --}}
                @if(isset($settings['email_kpum']) && $settings['email_kpum'])
                <a href="mailto:{{ $settings['email_kpum'] }}" class="group block bg-white rounded-3xl p-7 border border-slate-100 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-blue-500/10 hover:border-blue-200 hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Resmi</div>
                    <div class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors truncate">{{ $settings['email_kpum'] }}</div>
                    <div class="text-sm text-slate-500 mt-1">Respon dalam 1x24 jam</div>
                    <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-blue-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Hari kerja 08:00–17:00
                    </div>
                </a>
                @endif

                {{-- Visit --}}
                <div class="group block bg-white rounded-3xl p-7 border border-slate-100 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:shadow-violet-500/10 hover:border-violet-200 hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                    <div class="w-14 h-14 bg-gradient-to-br from-violet-400 to-violet-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-violet-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kunjungi Langsung</div>
                    <div class="text-lg font-black text-slate-900 group-hover:text-violet-600 transition-colors">Sekretariat KPUM</div>
                    <div class="text-sm text-slate-500 mt-1">Kampus UNUGHA Cilacap</div>
                    <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-violet-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Senin–Jumat 08:00–16:00
                    </div>
                </div>
            </div>

            {{-- FAQ --}}
            <div class="mb-16">
                <div class="text-center mb-10">
                    <span class="inline-block py-1 px-3 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-xs font-bold tracking-widest uppercase mb-3">FAQ</span>
                    <h2 class="text-3xl font-black text-slate-900">Pertanyaan yang Sering Diajukan</h2>
                </div>

                <div class="space-y-4" x-data="{ openFaq: null }">
                    @php
                    $faqs = [
                        ['q'=>'Saya tidak bisa login menggunakan NIM saya. Apa yang harus dilakukan?','a'=>'Pastikan NIM yang Anda masukkan sudah benar dan sesuai dengan format yang digunakan (tanpa spasi atau karakter khusus). Jika masih bermasalah, kemungkinan NIM Anda belum terdaftar dalam DPT. Silakan hubungi panitia KPUM melalui WhatsApp untuk verifikasi manual.'],
                        ['q'=>'Saya sudah memilih tapi tidak menerima bukti pilih via email. Normal?','a'=>'Bukti pilih dikirim otomatis ke email yang terdaftar di sistem universitas. Periksa folder spam/junk email Anda. Jika dalam 30 menit belum diterima, Anda bisa mengunduh bukti pilih secara langsung dari halaman bilik suara setelah voting.'],
                        ['q'=>'Apakah saya bisa mengubah pilihan suara setelah memilih?','a'=>'Tidak. Sesuai prinsip pemilu yang berlaku, setiap suara yang telah diberikan bersifat final dan tidak dapat diubah. Hal ini untuk menjaga integritas dan keadilan pemilihan.'],
                        ['q'=>'Bagaimana cara mengecek apakah saya terdaftar sebagai pemilih (DPT)?','a'=>'Anda dapat menggunakan fitur "Cek DPT" yang tersedia di halaman utama. Masukkan NIM Anda untuk mengetahui status pendaftaran sebagai pemilih.'],
                        ['q'=>'Sistem menunjukkan "Voting belum dimulai" padahal sudah waktunya. Kenapa?','a'=>'Waktu sistem menggunakan zona waktu WIB (UTC+7). Pastikan jam perangkat Anda sudah benar. Jika masalah berlanjut, lakukan refresh halaman atau bersihkan cache browser Anda.'],
                        ['q'=>'Apakah data pilihan saya aman dan tidak bisa dilacak?','a'=>'Ya. Sistem kami menggunakan enkripsi blockchain yang memastikan isi pilihan Anda tidak bisa dilacak oleh siapapun, termasuk administrator sistem. Yang tercatat hanya bahwa Anda telah menggunakan hak pilih.'],
                    ];
                    @endphp

                    @foreach($faqs as $i => $faq)
                    <div class="border border-slate-200 rounded-2xl overflow-hidden" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            class="w-full flex items-center justify-between gap-4 p-5 text-left hover:bg-slate-50 transition-colors"
                        >
                            <span class="font-semibold text-slate-800 text-sm sm:text-base">{{ $faq['q'] }}</span>
                            <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            style="display: none;"
                        >
                            <div class="px-5 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100 pt-4">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Map + Info --}}
            <div class="grid lg:grid-cols-2 gap-8">
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100">
                    <h3 class="text-xl font-black text-slate-900 mb-6">Informasi Sekretariat</h3>
                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm mb-1">Alamat</div>
                                <div class="text-sm text-slate-600">{{ $settings['address'] ?? 'Jl. Kemerdekaan Barat No.17, Kesugihan Kidul, Kec. Kesugihan, Kabupaten Cilacap, Jawa Tengah 53274' }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm mb-1">Jam Operasional</div>
                                <div class="text-sm text-slate-600">Senin – Jumat: 08:00 – 16:00 WIB<br>Sabtu: 08:00 – 12:00 WIB<br>Minggu & Hari Libur: Tutup</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm mb-1">Catatan Penting</div>
                                <div class="text-sm text-slate-600">Saat periode pemilihan aktif, tim teknis tersedia hingga 22:00 WIB untuk penanganan masalah darurat.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-lg h-80 lg:h-auto">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3362.385848888454!2d109.11083547422076!3d-7.625303675399003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e656bebb486d297%3A0x9acd9d808c1dda09!2sUniversitas%20Nahdlatul%20Ulama%20Al%20Ghazali!5e1!3m2!1sid!2sid!4v1771676536438!5m2!1sid!2sid"
                        class="w-full h-full"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

        </div>
    </main>

    @include('pages._footer')

</x-layouts.guest>
