<x-layouts.guest title="Masuk - KPUM">
    <!-- Lottie Script - Standard Component -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <div
        class="min-h-screen bg-slate-100 flex items-center justify-center p-4 lg:p-8 font-sans selection:bg-indigo-100 selection:text-indigo-900">

        <!-- Main Card Wrapper -->
        <div class="bg-white w-full max-w-5xl rounded-[2.5rem] shadow-2xl flex overflow-hidden min-h-[600px] relative">

            <!-- LEFT COLUMN: Login Form -->
            <div
                class="w-full lg:w-1/2 p-8 sm:p-12 lg:px-16 lg:py-12 flex flex-col justify-center relative z-10 bg-white">

                <!-- Logo / Brand -->
                <div class="mb-10 flex items-center gap-4">
                    @if(isset($settings['app_logo']) && $settings['app_logo'])
                        <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo" class="w-12 h-12 object-contain">
                    @else
                        <div
                            class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-md">
                            K</div>
                    @endif
                    <div class="flex flex-col">
                        <span class="font-bold text-sm sm:text-base text-slate-800 leading-tight">Komisi Pemilihan Umum
                            Mahasiswa</span>
                        <span class="text-slate-400 text-[10px] sm:text-xs font-semibold mt-0.5 tracking-tight uppercase">Universitas
                            Nahdlatul Ulama Al Ghazali Cilacap</span>
                    </div>
                </div>

                <!-- Headline -->
                <div class="mb-8">
                    <h1 class="text-4xl sm:text-5xl font-black text-slate-900 leading-[1.1] mb-3 tracking-tight">
                        Halo,<br>Selamat Datang!
                    </h1>
                    <p class="text-slate-400 text-sm font-medium">
                        Silakan masuk menggunakan NIM mahasiswa Anda.
                    </p>
                </div>

                <x-auth-session-status
                    class="mb-6 font-medium text-sm text-green-600 bg-green-50 px-4 py-3 rounded-xl border border-green-100"
                    :status="session('status')" />

                @if (session('error'))
                    <div class="mb-6 font-medium text-sm text-red-600 bg-red-50 px-4 py-3 rounded-xl border border-red-100">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login.mahasiswa') }}" class="space-y-5" autocomplete="on">
                    @csrf

                    <!-- NIM -->
                    <div class="space-y-2">
                        <label for="nim" class="sr-only">NIM Mahasiswa</label>
                        <input id="nim" name="nim" type="text" required autofocus value="{{ old('nim', $savedNim ?? '') }}"
                            class="block w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder:text-slate-400 font-semibold shadow-sm"
                            placeholder="NIM Mahasiswa (Contoh: 22E010013)">
                        @error('nim')
                            <span class="text-red-500 text-xs ml-2 font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Access Code -->
                    <div class="space-y-2" x-data="{ 
                        accessCode: '{{ old('access_code') }}',
                        formatAccessCode(val) {
                            let v = val.toUpperCase().replace(/[^A-Z0-9]/g, '');
                            if (v.length > 0 && v[0] !== 'K') {
                                v = 'K' + v;
                            }
                            let formatted = '';
                            if (v.length > 0) {
                                formatted += v.substring(0, 1);
                                if (v.length > 1) {
                                    formatted += '-' + v.substring(1, 3);
                                    if (v.length > 3) {
                                        formatted += '-' + v.substring(3, 7);
                                    }
                                }
                            }
                            this.accessCode = formatted;
                        }
                    }">
                        <label for="access_code" class="sr-only">Kode Akses</label>
                        <input id="access_code" name="access_code" type="text" required
                            x-model="accessCode"
                            @input="formatAccessCode($event.target.value)"
                            maxlength="9"
                            autocomplete="off"
                            class="block w-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder:text-slate-400 font-semibold shadow-sm"
                            placeholder="Kode Akses (Contoh: K-MU-5501)">
                        <p class="text-[10px] text-slate-400 font-bold ml-2 uppercase tracking-wide">Lihat Kode Unik di Surat Anda</p>
                        @error('access_code')
                            <span class="text-red-500 text-xs ml-2 font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div x-data="{ showPassword: false }" class="space-y-2">
                        <label for="password" class="sr-only">Password</label>
                        <div class="relative group">
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required
                                autocomplete="current-password"
                                class="block w-full px-5 py-4 pr-12 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all text-sm placeholder:text-slate-400 font-semibold tracking-wide shadow-sm"
                                placeholder="Password (Tanggal Lahir: DDMMYYYY)">

                            <!-- Eye Icon Centered -->
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors cursor-pointer z-10 focus:outline-none">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" style="display: none;" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center pt-2">
                        <label class="flex items-center gap-3 cursor-pointer group select-none">
                            <input type="checkbox" name="remember" id="remember" style="accent-color: #6C63FF;" {{ old('remember') || isset($savedNim) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-slate-300 shadow-sm cursor-pointer accent-[#6C63FF]">
                            <span
                                class="text-sm text-slate-500 font-semibold group-hover:text-slate-800 transition-colors">
                                Ingat Saya
                            </span>
                        </label>
                    </div>

                    <button type="submit" style="background-color: #6C63FF;"
                        class="w-full py-4 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-base rounded-2xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-0.5 active:scale-95 mt-4 tracking-wide border-none outline-none">
                        Sign In
                    </button>
                </form>

                <div class="mt-8 text-center pt-2">
                    <p class="text-xs sm:text-sm text-slate-400 font-medium tracking-tight">
                        Belum punya akun?
                        <a href="https://wa.me/6285183750294"
                            class="text-[#6C63FF] font-bold hover:underline hover:text-indigo-700 transition-colors ml-1">
                            Hubungi Panitia
                        </a>
                    </p>
                </div>
            </div>

            <!-- RIGHT COLUMN: Visuals -->
            <div class="hidden lg:flex w-1/2 relative flex-col items-center justify-center p-12 overflow-hidden"
                style="background: linear-gradient(135deg, #6C63FF 0%, #8B5CF6 100%);">

                <!-- Background Decorations -->
                <div class="absolute top-10 left-10 w-24 h-24 bg-white/10 rounded-full blur-xl animate-pulse"></div>
                <div class="absolute bottom-20 right-10 w-40 h-40 bg-purple-400/20 rounded-full blur-2xl"></div>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-white/5 rounded-full blur-3xl">
                </div>

                <!-- Noise Overlay -->
                <div class="absolute inset-0 opacity-10 mix-blend-overlay pointer-events-none"
                    style="background-image: url('{{ asset('assets/images/noise.svg') }}');"></div>

                <!-- Lottie Slider & Rotating Text -->
                <div x-data="{
                        current: 0,
                        taglines: [
                            { h: 'Digitalize the Future of Democracy', p: 'Era baru pemilihan mahasiswa yang inklusif dan transparan.' },
                            { h: 'Inovasi Suara dalam Genggaman', p: 'Gunakan hak pilihmu dengan sistem voting tercanggih.' },
                            { h: 'Integritas Digital, Hasil Akurat', p: 'Menjamin setiap suara tersalurkan dengan aman dan terpercaya.' },
                            { h: 'Satu Klik, Revolusi Masa Depan', p: 'Jadilah agen perubahan untuk UNUGHA yang lebih hebat.' }
                        ],
                        displayTextH: '',
                        displayTextP: '',
                        
                        async typeText(text, target, speed = 40) {
                            this[target] = '';
                            if(!text) return;
                            for (let i = 0; i < text.length; i++) {
                                this[target] += text.charAt(i);
                                await new Promise(resolve => setTimeout(resolve, speed));
                            }
                        },

                        async updateTagline() {
                            const item = this.taglines[this.current];
                            await Promise.all([
                                this.typeText(item.h, 'displayTextH', 40),
                                item.p ? this.typeText(item.p, 'displayTextP', 25) : (this.displayTextP = '', Promise.resolve())
                            ]);
                        },

                        init() {
                            this.updateTagline();
                            setInterval(async () => {
                                this.current = (this.current + 1) % this.taglines.length;
                                await this.updateTagline();
                            }, 8000); 
                        }
                    }" class="relative z-20 w-full h-full flex flex-col">

                    <!-- Top Zone: Fixed Animation Center -->
                    <div class="flex-1 flex items-center justify-center">
                        <div class="relative flex items-center justify-center filter drop-shadow-2xl"
                            style="width: 450px; height: 450px;">
                            
                            <!-- Animation 1 -->
                            <div x-show="current === 0"
                                x-transition:enter="transition ease-out duration-1000"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-1000"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90"
                                class="absolute inset-0 flex items-center justify-center">
                                <lottie-player src="{{ asset('animation/animations/9507360.json') }}"
                                    background="transparent" speed="1"
                                    style="width: 100%; height: 100%;" loop autoplay></lottie-player>
                            </div>

                            <!-- Animation 2 -->
                            <div x-show="current === 1"
                                x-transition:enter="transition ease-out duration-1000"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-1000"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90"
                                class="absolute inset-0 flex items-center justify-center" style="display: none;">
                                <lottie-player src="{{ asset('animation/animations/tmp6u1prh3_.json') }}"
                                    background="transparent" speed="1"
                                    style="width: 100%; height: 100%;" loop autoplay></lottie-player>
                            </div>

                            <!-- Animation 3 -->
                            <div x-show="current === 2"
                                x-transition:enter="transition ease-out duration-1000"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-1000"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90"
                                class="absolute inset-0 flex items-center justify-center" style="display: none;">
                                <lottie-player src="{{ asset('animation/animations/tmpl7cedbgb.json') }}"
                                    background="transparent" speed="1"
                                    style="width: 100%; height: 100%;" loop autoplay></lottie-player>
                            </div>

                            <!-- Animation 4 -->
                            <div x-show="current === 3"
                                x-transition:enter="transition ease-out duration-1000"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-1000"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90"
                                class="absolute inset-0 flex items-center justify-center" style="display: none;">
                                <lottie-player src="{{ asset('animation/animations/9582514.json') }}"
                                    background="transparent" speed="1"
                                    style="width: 100%; height: 100%;" loop autoplay></lottie-player>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Zone: Fixed Height Text Area -->
                    <div class="h-40 flex flex-col items-center justify-start text-center text-white px-8">
                        <h2 class="font-black text-2xl tracking-tight leading-tight min-h-[1.5em]"
                            x-text="displayTextH"></h2>
                        <p class="text-indigo-100 text-sm mt-3 font-medium max-w-sm min-h-[1.5em]"
                            x-text="displayTextP"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>