<x-layouts.guest title="Cek Daftar Pemilih Tetap (DPT)">
    <!-- Floating Navbar (Similar to Welcome but simplified or same include) -->
    <!-- We can reuse the same layout if it supports navbar slots, but for now I'll just copy the simple navbar structure or better yet, assume guest layout might not have it and add a back button.
         Wait, guest layout usually wraps content. Let's look at it if needed.
         Actually, I'll add a simple header. -->

    <div class="min-h-screen relative flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 bg-slate-50 overflow-hidden">

        <!-- Background Elements -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]"></div>
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-50 via-transparent to-indigo-50 opacity-60"></div>
        </div>

        <div class="w-full max-w-lg relative">
            <!-- Back Button -->
            <div class="absolute -top-16 left-0">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-900 transition-colors font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-blue-900/10 border border-white/60 p-8 md:p-12 relative overflow-hidden" x-data="dptSearch()">

                <!-- Decorative Orb -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>

                <div class="text-center mb-10 relative z-10">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm ring-4 ring-blue-50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Cek Status DPT</h1>
                    <p class="text-slate-500">Masukkan NIM Anda untuk memeriksa status terdaftar sebagai pemilih tetap.</p>
                </div>

                <!-- Search Form -->
                <form @submit.prevent="search" class="space-y-6 relative z-10">
                    <div>
                        <label for="nim" class="sr-only">Nomor Induk Mahasiswa (NIM)</label>
                        <div class="relative">
                            <input type="text" x-model="nim" id="nim"
                                class="block w-full px-5 py-4 rounded-xl border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500 focus:bg-white transition-all font-mono text-center text-lg tracking-wider font-bold shadow-sm"
                                placeholder="Masukkan NIM (Contoh: 22010...)"
                                required autocomplete="off" minlength="5" maxlength="20">

                            <!-- Loader inside input -->
                            <div x-show="loading" class="absolute right-4 top-1/2 -translate-y-1/2" style="display: none;">
                                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        <p x-show="error" x-text="error" class="mt-2 text-sm text-red-600 text-center font-medium animate-pulse" style="display: none;"></p>
                    </div>

                    <button type="submit" :disabled="loading || !nim"
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-slate-900 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:-translate-y-0.5 duration-200">
                        <span x-text="loading ? 'Memeriksa...' : 'Cek Status Sekarang'"></span>
                    </button>
                </form>

                <!-- Result Card -->
                <div x-show="result" style="display: none;"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-8 pt-8 border-t border-slate-100">

                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4 animate-[bounce_1s_ease-in-out_1]">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">Terdaftar di DPT</h3>
                        <p class="text-slate-500 text-sm mb-6">Data mahasiswa ditemukan dalam sistem.</p>
                    </div>

                    <dl class="bg-slate-50 rounded-2xl p-6 border border-slate-100 space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-slate-200/60 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-slate-500">NIM</dt>
                            <dd class="text-sm font-mono font-bold text-slate-900" x-text="result?.nim"></dd>
                        </div>

                    </dl>

                    <div class="mt-8 text-center">
                         <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('login.mahasiswa') }}" class="inline-flex items-center text-blue-600 font-bold hover:underline">
                            Lanjut ke Halaman Login
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Not Found State -->
                <div x-show="notFound" style="display: none;"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-8 pt-8 border-t border-slate-100 text-center">

                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-slate-500 text-sm max-w-xs mx-auto">
                        NIM yang Anda masukkan tidak terdaftar dalam DPT. Silakan hubungi panitia jika Anda merasa ini adalah kesalahan.
                    </p>

                     <div class="mt-6">
                        <a href="https://wa.me/6285183750294" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900">
                            <svg class="mr-2 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Hubungi Panitia
                        </a>
                     </div>
                </div>

            </div>

            <p class="text-center text-slate-400 text-xs mt-8">
                &copy; {{ date('Y') }} KPUM UNUGHA. All rights reserved. <br>
                Sistem Terproteksi & Terenkripsi.
            </p>
        </div>
    </div>

    <!-- Alpine.js Search Logic -->
    <script>
        function dptSearch() {
            return {
                nim: '',
                loading: false,
                result: null,
                notFound: false,
                error: null,

                async search() {
                    if (!this.nim) return;

                    this.loading = true;
                    this.error = null;
                    this.result = null;
                    this.notFound = false;

                    try {
                        const response = await fetch("{{ route('check-dpt.search') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ nim: this.nim })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            if (data.status === 'found') {
                                this.result = data.data;
                            } else {
                                this.notFound = true;
                            }
                        } else {
                            if (response.status === 404) {
                                this.notFound = true;
                            } else if (response.status === 429) {
                                this.error = "Terlalu banyak permintaan. Silakan coba lagi nanti.";
                            } else {
                                this.error = data.message || "Terjadi kesalahan. Silakan coba lagi.";
                            }
                        }
                    } catch (err) {
                        this.error = "Gagal menghubungi server. Periksa koneksi internet Anda.";
                        console.error(err);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</x-layouts.guest>
