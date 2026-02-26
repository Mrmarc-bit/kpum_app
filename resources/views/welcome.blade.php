<x-layouts.guest title="KPUM - Universitas Nahdlatul Ulama Al Ghazali Cilacap">
    <!-- Floating Navbar -->
    <div class="fixed top-6 inset-x-0 z-50 flex justify-center px-4">
        <nav x-data="{ mobileMenuOpen: false }"
            class="w-full max-w-5xl bg-white/70 backdrop-blur-2xl border border-white/40 shadow-xl shadow-blue-900/5 rounded-[2rem] md:rounded-full px-6 py-3 transition-all duration-300 supports-backdrop-filter:bg-white/60">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-3">
                    @if(isset($settings['app_logo']) && $settings['app_logo'])
                        <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo"
                            class="w-9 h-9 rounded-full object-contain bg-white shadow-sm ring-2 ring-white/50">
                    @else
                        <div
                            class="w-9 h-9 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-black shadow-lg shadow-blue-500/30 ring-2 ring-white/50">
                            K</div>
                    @endif
                    <span
                        class="font-bold text-lg tracking-tight text-slate-900">{{ $settings['app_name'] ?? 'KPUM System' }}</span>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 -mr-2 text-slate-600 hover:text-blue-600 focus:outline-none transition-colors">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Links (Desktop) -->
                <div class="hidden md:flex space-x-1 items-center">
                    <a href="#home"
                        class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all">Beranda</a>
                    <a href="#jadwal"
                        class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all">Jadwal</a>
                    <a href="{{ route('check-dpt') }}"
                        class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all">Cek DPT</a>
                    <div class="w-px h-5 bg-slate-200 mx-2"></div>
                    <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('login.mahasiswa') }}"
                        class="group inline-flex items-center justify-center px-5 py-2 text-sm font-bold rounded-full text-white bg-slate-900 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 transition-all duration-300">
                        <span>Masuk & Vote</span>
                        <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" style="display: none;"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden pt-4 pb-2 space-y-1 border-t border-slate-100 mt-3">
                <a href="#home" @click="mobileMenuOpen = false" class="block px-4 py-2.5 text-base font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">Beranda</a>
                <a href="#jadwal" @click="mobileMenuOpen = false" class="block px-4 py-2.5 text-base font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">Jadwal</a>
                <a href="{{ route('check-dpt') }}" @click="mobileMenuOpen = false" class="block px-4 py-2.5 text-base font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">Cek DPT</a>
                <div class="pt-3 px-2">
                    <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('login.mahasiswa') }}"
                       class="w-full flex items-center justify-center px-5 py-3 text-base font-bold rounded-xl text-white bg-slate-900 hover:bg-blue-600 shadow-md transition-all">
                        <span>Masuk & Vote</span>
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Hero Section -->
    <section id="home"
        class="relative min-h-screen flex items-center pt-40 pb-20 overflow-hidden selection:bg-indigo-100 selection:text-indigo-900">
        <!-- Animated Background with Grid -->
        <div class="absolute inset-0 -z-10 bg-slate-50">
            <!-- Grid Pattern -->
            <div class="absolute inset-0 opacity-20 mix-blend-overlay"></div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]">
            </div>

            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,var(--tw-gradient-stops))] from-blue-100/40 via-transparent to-transparent opacity-70">
            </div>
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,var(--tw-gradient-stops))] from-indigo-100/40 via-transparent to-transparent opacity-70">
            </div>

            <!-- Floating Orbs -->
            <div
                class="absolute top-1/4 left-10 w-72 h-72 bg-blue-300/30 rounded-full blur-[100px] animate-[pulse_8s_ease-in-out_infinite]">
            </div>
            <div
                class="absolute bottom-1/4 right-10 w-96 h-96 bg-indigo-300/30 rounded-full blur-[120px] animate-[pulse_10s_ease-in-out_infinite_reverse]">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div
                class="bg-white/40 backdrop-blur-3xl rounded-[3rem] p-8 md:p-16 border border-white/50 shadow-2xl shadow-blue-900/5 relative overflow-hidden group ring-1 ring-white/60">
                <!-- Shine Effect -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-white/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none">
                </div>

                <div class="text-center max-w-4xl mx-auto relative z-10">
                    <div
                        class="inline-flex items-center gap-2 pl-2 pr-4 py-1.5 rounded-full bg-white/60 border border-white/60 text-blue-600 text-xs font-bold tracking-wide uppercase mb-8 shadow-sm backdrop-blur-md animate-[bounce_3s_infinite]">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                        </span>
                        Demokrasi Mahasiswa 2026
                    </div>

                    <h1 class="text-4xl sm:text-5xl tracking-tighter font-black text-slate-900 md:text-8xl mb-6 sm:mb-8 leading-[1.1] sm:leading-[0.9] min-h-[3em] sm:min-h-[2em]"
                        x-data="{
                            text: '',
                            wordIndex: 0,
                            isDeleting: false,
                            words: ['Masa Depan Kita', 'Harapan Bangsa', 'Aspirasi Kampus'],
                            wait: 2000,
                            speed: 100,
                            init() { this.type(); },
                            type() {
                                const current = this.wordIndex % this.words.length;
                                const fullTxt = this.words[current];
                                if (this.isDeleting) {
                                    this.text = fullTxt.substring(0, this.text.length - 1);
                                } else {
                                    this.text = fullTxt.substring(0, this.text.length + 1);
                                }
                                let typeSpeed = this.speed;
                                if (this.isDeleting) { typeSpeed /= 2; }
                                if (!this.isDeleting && this.text === fullTxt) {
                                    typeSpeed = this.wait;
                                    this.isDeleting = true;
                                } else if (this.isDeleting && this.text === '') {
                                    this.isDeleting = false;
                                    this.wordIndex++;
                                    typeSpeed = 500;
                                }
                                setTimeout(() => this.type(), typeSpeed);
                            }
                        }">
                        Suara Anda<span class="text-blue-600">.</span><br>
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 animate-gradient-x"
                            x-text="text"></span><span class="text-blue-600 animate-[pulse_1s_infinite] ml-1">|</span>
                    </h1>

                    <p class="mt-6 max-w-2xl mx-auto text-xl text-slate-600/90 leading-relaxed font-medium">
                        Platform pemilihan elektronik modern yang <span
                            class="text-slate-900 font-bold decoration-blue-300/50 underline decoration-4 underline-offset-4">Transparan</span>,
                        <span
                            class="text-slate-900 font-bold decoration-indigo-300/50 underline decoration-4 underline-offset-4">Aman</span>,
                        dan <span
                            class="text-slate-900 font-bold decoration-violet-300/50 underline decoration-4 underline-offset-4">Jujur</span>.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row gap-5 justify-center items-center">
                        <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('login.mahasiswa') }}"
                            class="group relative px-8 py-4 bg-slate-900 text-white text-lg font-bold rounded-2xl overflow-hidden shadow-xl shadow-blue-900/20 hover:shadow-2xl hover:shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                            <div
                                class="absolute inset-0 w-full h-full bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <span class="relative flex items-center justify-center gap-3">
                                Gunakan Hak Pilih
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </a>


                        @if(($settings['enable_quick_count'] ?? 'false') === 'true')
                        <a href="#quick-count"
                           class="px-8 py-4 bg-white/80 text-indigo-600 text-lg font-bold rounded-2xl border border-indigo-100 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 shadow-lg shadow-indigo-500/10 hover:shadow-xl hover:shadow-indigo-500/20 transition-all duration-300 backdrop-blur-sm w-full sm:w-auto hover:-translate-y-1 flex items-center justify-center gap-2">
                             <span class="relative flex h-3 w-3 mr-1">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                              </span>
                             Live Count
                        </a>
                        @else
                        <a href="#jadwal"
                            class="px-8 py-4 bg-white/50 text-slate-700 text-lg font-bold rounded-2xl border border-white/50 hover:bg-white hover:text-blue-600 hover:border-blue-200 shadow-lg hover:shadow-xl transition-all duration-300 backdrop-blur-sm w-full sm:w-auto hover:-translate-y-1">
                            Pelajari Jadwal
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Floating Glass Cards (Decorations) -->
                <div
                    class="hidden lg:block absolute top-[20%] left-[5%] p-4 bg-white/60 backdrop-blur-md rounded-2xl border border-white/50 shadow-xl animate-[pulse_4s_ease-in-out_infinite] -rotate-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold">
                            ✓</div>
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Status</div>
                            <div class="text-sm font-bold text-slate-900">Terverifikasi</div>
                        </div>
                    </div>
                </div>

                <div
                    class="hidden lg:block absolute bottom-[20%] right-[5%] p-4 bg-white/60 backdrop-blur-md rounded-2xl border border-white/50 shadow-xl animate-[pulse_5s_ease-in-out_infinite] rotate-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                            3</div>
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Kandidat</div>
                            <div class="text-sm font-bold text-slate-900">Siap Dipilih</div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <!-- Quick Count Section (If Enabled) -->
    @if(($settings['enable_quick_count'] ?? 'false') === 'true')
        @include('partials.quick-count-section')
    @endif

    <!-- Countdown Section -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#80808008_1px,transparent_1px),linear-gradient(to_bottom,#80808008_1px,transparent_1px)] bg-[size:32px_32px]">
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div
                class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-[2.5rem] p-6 md:p-16 shadow-2xl text-white text-center relative overflow-hidden isolate ring-4 ring-offset-4 ring-offset-white ring-blue-100">
                <!-- Noise Texture -->
                <div class="absolute inset-0 opacity-20 mix-blend-overlay"></div>
                <div
                    class="absolute -top-1/2 -left-1/2 w-full h-full bg-gradient-to-br from-white/10 to-transparent transform rotate-12 blur-3xl">
                </div>

                <span
                    class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/20 text-blue-100 text-xs font-bold tracking-widest uppercase mb-6 backdrop-blur-md">Live
                    Countdown</span>

                <h2 class="text-3xl md:text-5xl font-black mb-6 relative z-10 tracking-tight">Menuju Puncak Pemilihan
                </h2>
                <p class="text-blue-100 mb-12 relative z-10 text-lg max-w-2xl mx-auto">Jangan lewatkan momen penting
                    demokrasi mahasiswa. Pastikan Anda siap memberikan suara tepat waktu.</p>


                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 max-w-4xl mx-auto relative z-10" x-data="{
                        expiry: new Date('{{ $settings['voting_start_time'] ?? '2026-02-01' }}').getTime(),
                        time: { days: '00', hours: '00', minutes: '00', seconds: '00' },
                        init() {
                            this.tick();
                            setInterval(() => this.tick(), 1000);
                        },
                        tick() {
                            const now = new Date().getTime();
                            const distance = this.expiry - now;
                            if (distance < 0) {
                                this.time = { days: '00', hours: '00', minutes: '00', seconds: '00' };
                                return;
                            }
                            this.time.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                            this.time.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                            this.time.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                            this.time.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                        }
                    }">
                    <template x-for="(value, label) in time" :key="label">
                        <div
                            class="bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 shadow-lg hover:bg-white/20 transition-all duration-300 hover:-translate-y-1">
                            <div class="text-5xl md:text-7xl font-black font-mono tracking-tighter tabular-nums"
                                x-text="value">00</div>
                            <div class="text-xs md:text-sm uppercase tracking-widest font-bold mt-2 text-blue-200"
                                x-text="label"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    @include('partials.calendar-section')
    <!-- Candidate List Section -->
    <!-- Candidate List Section -->
    @include('partials.candidates-section')

    <!-- Party Marquee Section -->
    @include('partials.party-marquee')

    <!-- Contact & Location Section -->
    <section id="contact" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-100/50 border border-blue-200 text-blue-600 text-xs font-bold tracking-widest uppercase mb-4">Hubungi Kami</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">
                    Pusat Informasi & Lokasi
                </h2>
                <p class="text-lg text-slate-600 font-medium">
                    Jika ada kendala teknis atau pertanyaan seputar pemilu raya, silakan hubungi kami atau kunjungi sekretariat.
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Contact Info Card -->
                <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] p-5 sm:p-8 md:p-10 shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col justify-center h-full break-words">
                    <h3 class="text-2xl font-black text-slate-900 mb-6 text-center sm:text-left">Kontak Panitia</h3>

                    <div class="space-y-4 sm:space-y-6">
                        <!-- WhatsApp -->
                        @php
                            $waNumber = $settings['contact_person'] ?? '6285183750294';
                            $waLink = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $waNumber);
                        @endphp
                        <a href="{{ $waLink }}" target="_blank" class="flex items-center gap-4 sm:gap-5 group p-4 rounded-2xl bg-green-50/50 border border-green-100 hover:bg-green-50 hover:border-green-200 transition-all duration-300">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">WhatsApp Center</div>
                                <div class="text-base sm:text-lg font-black text-slate-900 group-hover:text-green-600 transition-colors truncate">+{{ $waNumber }}</div>
                                <div class="text-xs sm:text-sm text-slate-500 font-medium">Respon Cepat 24/7</div>
                            </div>
                        </a>

                        <!-- Address -->
                        <div class="flex items-start gap-4 sm:gap-5 p-4 rounded-2xl bg-blue-50/50 border border-blue-100">
                             <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 shrink-0 mt-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sekretariat KPUM</div>
                                <div class="text-sm sm:text-base font-bold text-slate-900 leading-snug mb-1">
                                    Universitas Nahdlatul Ulama Al Ghazali
                                </div>
                                <div class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
                                    {{ $settings['address'] ?? 'Jl. Kemerdekaan Barat No.17, Kesugihan Kidul, Kec. Kesugihan, Kabupaten Cilacap' }}
                                </div>
                            </div>
                        </div>

                        <!-- Email Card -->
                        @php
                            $emailKpum = $settings['email_kpum'] ?? 'kpumunugha@gmail.com';
                        @endphp
                        <a href="mailto:{{ $emailKpum }}" class="flex items-center gap-4 sm:gap-5 group p-4 rounded-2xl bg-red-50/50 border border-red-100 hover:bg-red-50 hover:border-red-200 transition-all duration-300">
                             <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Email Resmi</div>
                                <div class="text-base sm:text-lg font-black text-slate-900 group-hover:text-red-600 transition-colors truncate">
                                    {{ $emailKpum }}
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Google Maps -->
                <div class="bg-white p-2 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden min-h-[350px] sm:min-h-[400px] lg:h-auto w-full flex flex-col">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3362.385848888454!2d109.11083547422076!3d-7.625303675399003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e656bebb486d297%3A0x9acd9d808c1dda09!2sUniversitas%20Nahdlatul%20Ulama%20Al%20Ghazali!5e1!3m2!1sid!2sid!4v1771676536438!5m2!1sid!2sid"
                        class="w-full h-full flex-1 rounded-2xl"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-16 relative overflow-hidden">



        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            @if(isset($settings['app_logo']) && $settings['app_logo'])
                <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo" class="block mx-auto
                                        w-24 h-24
                                        object-contain
                                        bg-white
                                        rounded-full
                                        shadow-md
                                        ring-2 ring-white/60
                                        mb-4" />
            @else
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mx-auto mb-6 shadow-lg shadow-blue-500/30">
                    K</div>
            @endif
            <p class="text-slate-500 text-sm mb-8 font-medium">
                &copy; {{ date('Y') }} <br>Komisi Pemilihan Umum Mahasiswa.<br>
                Universitas Nahdlatul Ulama Al-Ghazali Cilacap.
            </p>
            <div class="flex justify-center flex-wrap gap-8 text-sm font-semibold text-slate-400">
                <a href="{{ route('privacy-policy') }}" class="hover:text-blue-600 transition-colors">Privacy Policy</a>
                <a href="{{ route('terms-of-service') }}" class="hover:text-blue-600 transition-colors">Terms of Service</a>
                <a href="{{ route('contact-support') }}" class="hover:text-blue-600 transition-colors">Contact Support</a>
                <a href="{{ route('documentation') }}" class="hover:text-blue-600 transition-colors">Documentation</a>
            </div>
        </div>
    </footer>

</x-layouts.guest>