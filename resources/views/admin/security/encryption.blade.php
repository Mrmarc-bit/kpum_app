<x-layouts.admin title="Manajemen Enkripsi Vote">
    <div class="space-y-8">

        <!-- Header with Action -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">🔐 Manajemen Enkripsi Vote</h1>
                <p class="text-slate-500 mt-2 font-medium text-sm md:text-base">Kontrol tingkat keamanan dan enkripsi data voting mahasiswa.</p>
            </div>
            <a href="{{ route('admin.audit.encryption') }}"
               class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Audit Enkripsi
            </a>
        </div>

        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="bg-green-50 border-l-4 border-green-500 p-6 rounded-xl shadow-lg">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800 font-bold text-lg">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @php
            $currentLevel = \App\Services\VoteEncryptionService::getCurrentLevel();
        @endphp

        <!-- Change Encryption Level Form -->
        <div class="bg-white rounded-3xl p-6 md:p-10 shadow-xl border border-slate-100">
            <h2 class="text-xl md:text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                <svg class="w-6 md:w-8 h-6 md:h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Ubah Mode Enkripsi
            </h2>

            <form id="encryptionForm" method="POST" action="{{ route('admin.security.encryption.update') }}" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Standard Option -->
                    <div class="encryption-option cursor-pointer" onclick="selectEncryption('standard')">
                        <input type="radio" name="encryption_level" value="standard" id="radio_standard"
                               {{ $currentLevel === 'standard' ? 'checked' : '' }}
                               class="hidden">
                        <div id="card_standard" class="p-6 rounded-2xl border-2 transition-all hover:shadow-lg
                                    {{ $currentLevel === 'standard' ? 'border-green-500 bg-green-50 shadow-lg shadow-green-500/20' : 'border-slate-200 hover:border-green-300' }}">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-3xl">🔒</span>
                                <div id="check_standard" class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                            {{ $currentLevel === 'standard' ? 'bg-green-500 border-green-500' : 'border-slate-300' }}">
                                    <svg class="w-3 h-3 text-white {{ $currentLevel === 'standard' ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="font-bold text-slate-800 mb-2">Standard</h4>
                            <p class="text-xs text-slate-500">AES-256 encryption</p>
                        </div>
                    </div>

                    <!-- High Option -->
                    <div class="encryption-option cursor-pointer" onclick="selectEncryption('high')">
                        <input type="radio" name="encryption_level" value="high" id="radio_high"
                               {{ $currentLevel === 'high' ? 'checked' : '' }}
                               class="hidden">
                        <div id="card_high" class="p-6 rounded-2xl border-2 transition-all hover:shadow-lg
                                    {{ $currentLevel === 'high' ? 'border-purple-500 bg-purple-50 shadow-lg shadow-purple-500/20' : 'border-slate-200 hover:border-purple-300' }}">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-3xl">🔐</span>
                                <div id="check_high" class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                            {{ $currentLevel === 'high' ? 'bg-purple-500 border-purple-500' : 'border-slate-300' }}">
                                    <svg class="w-3 h-3 text-white {{ $currentLevel === 'high' ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="font-bold text-slate-800 mb-2">High E2E</h4>
                            <p class="text-xs text-slate-500">Double encryption</p>
                        </div>
                    </div>

                    <!-- Blockchain Option -->
                    <div class="encryption-option cursor-pointer" onclick="selectEncryption('blockchain')">
                        <input type="radio" name="encryption_level" value="blockchain" id="radio_blockchain"
                               {{ $currentLevel === 'blockchain' ? 'checked' : '' }}
                               class="hidden">
                        <div id="card_blockchain" class="p-6 rounded-2xl border-2 transition-all hover:shadow-lg
                                    {{ $currentLevel === 'blockchain' ? 'border-amber-500 bg-amber-50 shadow-lg shadow-amber-500/20' : 'border-slate-200 hover:border-amber-300' }}">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-3xl">⛓️</span>
                                <div id="check_blockchain" class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                            {{ $currentLevel === 'blockchain' ? 'bg-amber-500 border-amber-500' : 'border-slate-300' }}">
                                    <svg class="w-3 h-3 text-white {{ $currentLevel === 'blockchain' ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <h4 class="font-bold text-slate-800 mb-2">Blockchain</h4>
                            <p class="text-xs text-slate-500">Hash chain verify</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pt-6 border-t border-slate-200">
                    <p class="text-sm text-slate-500 order-2 md:order-1">
                        ⚠️ Perubahan hanya berlaku untuk <strong>vote baru</strong>. Vote lama tetap menggunakan enkripsi saat vote tersebut dibuat.
                    </p>
                    <button type="submit"
                            class="w-full md:w-auto order-1 md:order-2 inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-lg rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-xl shadow-indigo-500/30 hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
    function selectEncryption(level) {
        // Uncheck all
        const levels = ['standard', 'high', 'blockchain'];

        // Map logical names to color names
        const colorNames = {
            'standard': 'green',
            'high': 'purple',
            'blockchain': 'amber'
        };

        const colors = {
            'standard': { border: 'border-green-500', bg: 'bg-green-50', shadow: 'shadow-green-500/20', check: 'bg-green-500 border-green-500' },
            'high': { border: 'border-purple-500', bg: 'bg-purple-50', shadow: 'shadow-purple-500/20', check: 'bg-purple-500 border-purple-500' },
            'blockchain': { border: 'border-amber-500', bg: 'bg-amber-50', shadow: 'shadow-amber-500/20', check: 'bg-amber-500 border-amber-500' }
        };

        levels.forEach(l => {
            document.getElementById('radio_' + l).checked = false;
            const card = document.getElementById('card_' + l);
            const check = document.getElementById('check_' + l);
            const svg = check.querySelector('svg');

            // Reset styles using the correct color name for hover
            const hoverColor = colorNames[l];
            card.className = 'p-6 rounded-2xl border-2 transition-all hover:shadow-lg border-slate-200 hover:border-' + hoverColor + '-300 cursor-pointer';
            check.className = 'w-5 h-5 rounded-full border-2 flex items-center justify-center border-slate-300';
            if (svg) svg.classList.add('hidden');
        });

        // Check selected
        document.getElementById('radio_' + level).checked = true;
        const card = document.getElementById('card_' + level);
        const check = document.getElementById('check_' + level);
        const svg = check.querySelector('svg');

        card.className = 'p-6 rounded-2xl border-2 transition-all hover:shadow-lg cursor-pointer ' + colors[level].border + ' ' + colors[level].bg + ' shadow-lg ' + colors[level].shadow;
        check.className = 'w-5 h-5 rounded-full border-2 flex items-center justify-center ' + colors[level].check;
        if (svg) svg.classList.remove('hidden');
    }
    </script>
</x-layouts.admin>
