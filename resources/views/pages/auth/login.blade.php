<x-layouts.guest title="Masuk - KPUM">
    <div
        class="min-h-screen bg-slate-50 relative overflow-hidden flex selection:bg-indigo-100 selection:text-indigo-900">
        <!-- Background Patterns -->
        <div
            class="absolute inset-0 z-0 bg-[url('/assets/images/noise.svg')] opacity-20 mix-blend-overlay pointer-events-none">
        </div>
        <div
            class="absolute inset-0 z-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] pointer-events-none">
        </div>

        <!-- Animated Blobs -->
        <div
            class="fixed top-[-20%] left-[-10%] w-[800px] h-[800px] rounded-full bg-blue-200/20 blur-[130px] animate-[pulse_15s_ease-in-out_infinite] z-0">
        </div>
        <div
            class="fixed bottom-[-20%] right-[-10%] w-[600px] h-[600px] rounded-full bg-indigo-200/20 blur-[130px] animate-[pulse_12s_ease-in-out_infinite_reverse] z-0">
        </div>

        <!-- Left Column: Visuals -->
        <div class="hidden lg:flex w-1/2 relative z-10 items-center justify-center p-12">
            <div class="max-w-xl w-full">
                <div
                    class="bg-white/10 backdrop-blur-2xl rounded-[3rem] p-12 border border-white/30 shadow-2xl relative overflow-hidden group">
                    <!-- Shine -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-white/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        @if(isset($settings['app_logo']) && $settings['app_logo'])
                            <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo"
                                class="w-16 h-16 rounded-2xl object-contain bg-white shadow-lg mb-8 p-1">
                        @else
                            <div
                                class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-blue-600 font-bold text-2xl shadow-lg mb-8">
                                K</div>
                        @endif
                        <h2 class="text-4xl font-black text-slate-900 mb-6 leading-tight">Selamat Datang di <br><span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">{{ $settings['app_name'] ?? 'KPUM System' }}</span>.
                        </h2>
                        <p class="text-lg text-slate-600 leading-relaxed mb-10">
                            Platform pemilihan elektronik resmi. Suara Anda adalah kunci untuk masa depan kampus yang
                            lebih baik. Silakan masuk untuk menggunakan hak pilih.
                        </p>

                        <!-- Stats/Info Cards -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/40 p-4 rounded-2xl border border-white/40">
                                <div class="text-2xl font-bold text-slate-900 mb-1">100%</div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Aman & Rahasia
                                </div>
                            </div>
                            <div class="bg-white/40 p-4 rounded-2xl border border-white/40">
                                <div class="text-2xl font-bold text-slate-900 mb-1">Real-time</div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Penghitungan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-12 relative z-10">
            <div
                class="w-full max-w-md bg-white/70 backdrop-blur-3xl rounded-[2.5rem] p-8 sm:p-10 border border-white/60 shadow-xl shadow-slate-200/50 ring-1 ring-white/50 relative">

                <div class="text-center lg:text-left mb-10">
                    <div class="lg:hidden mx-auto w-12 h-12 flex items-center justify-center mb-6">
                        @if(isset($settings['app_logo']) && $settings['app_logo'])
                            <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo"
                                class="w-12 h-12 rounded-xl object-contain bg-white shadow-lg p-1">
                        @else
                            <div
                                class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                K</div>
                        @endif
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900">Masuk Akun</h3>
                    <p class="text-slate-500 mt-2">Masukkan kredensial akademik Anda.</p>
                </div>

                <x-auth-session-status class="mb-6 font-medium text-sm text-green-600" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-6">
                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input id="email" name="email" type="email" :value="old('email')" required autofocus
                                class="block w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 bg-white/60 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm placeholder:text-slate-400"
                                placeholder="example@gmail.com">
                            @error('email')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div x-data="{ showPassword: false }">
                            <label for="password"
                                class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                            <div class="relative">
                                <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required
                                    autocomplete="current-password"
                                    class="block w-full px-4 py-3.5 pr-12 rounded-xl border-2 border-slate-200 bg-white/60 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm placeholder:text-slate-400"
                                    placeholder="••••••••">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
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
                            @error('password')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-2">
                            <span class="ml-2 text-sm text-slate-600 font-medium">Ingat Saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            {{-- Forgot Password - Temporarily Disabled
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-semibold text-blue-600 hover:text-blue-500 hover:underline">
                                Lupa?
                            </a>
                            --}}
                        @endif
                    </div>

                    <button type="submit"
                        class="group w-full py-3.5 px-4 bg-slate-900 text-white font-bold rounded-xl shadow-lg shadow-blue-900/10 hover:shadow-blue-900/20 hover:bg-slate-800 transition-all transform hover:-translate-y-0.5 relative overflow-hidden">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Masuk ke Sistem
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </button>
                </form>

                <div class="mt-8 pt-8 border-t border-slate-200/60 text-center">
                    <p class="text-xs font-medium text-slate-400">
                        Secure Voting System <span class="mx-1">&bull;</span> KPUM 2026
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>