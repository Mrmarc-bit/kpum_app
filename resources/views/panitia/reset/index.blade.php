<x-layouts.panitia :title="$title">
    <div class="max-w-2xl mx-auto space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4 animate-pulse">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900">Danger Zone: Reset Election</h1>
            <p class="text-slate-500 mt-2 text-lg">Tindakan ini bersifat destruktif dan tidak dapat dibatalkan.</p>
        </div>

        <div class="bg-white rounded-3xl border border-red-200 shadow-xl shadow-red-500/10 overflow-hidden">
            <div class="p-8 space-y-6">
                <div class="p-4 bg-red-50 border border-red-100 rounded-xl flex gap-3 text-red-700">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-bold">Apa yang akan terjadi?</h4>
                        <ul class="list-disc list-inside text-sm space-y-1 mt-2 opacity-90">
                            <li>Seluruh data <strong>Suara (Votes)</strong> akan dihapus permanen.</li>
                            <li>Status pemilih (Sudah Memilih) akan di-reset menjadi <strong>Belum Memilih</strong>.
                            </li>
                            <li>Kandidat dan Data DPT akan <strong>tetap ada</strong> (tidak terhapus).</li>
                            <li>Sistem akan siap untuk pemilihan ulang dari awal.</li>
                        </ul>
                    </div>
                </div>

                <form action="{{ route('panitia.reset.store') }}" method="POST" class="space-y-6" data-confirm="PERINGATAN: Tindakan ini akan MENGHAPUS SEMUA DATA SUARA. Apakah Anda benar-benar yakin ingin melanjutkan?">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Aksi</label>
                            <p class="text-sm text-slate-500 mb-2">Ketik kalimat <span
                                    class="font-mono font-bold select-all bg-slate-100 px-1 rounded text-red-600">RESET
                                    ELECTION</span> di bawah ini:</p>
                            <input type="text" name="confirm_text" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-mono placeholder:text-slate-300"
                                placeholder="Ketikan disini...">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Password Administrator</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                                placeholder="Masukkan password Anda">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-500/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            RESET SEMUA DATA PEMILIHAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.panitia>