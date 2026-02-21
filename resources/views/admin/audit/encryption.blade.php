<x-layouts.admin title="Verifikasi Enkripsi Vote">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Audit Enkripsi Suara</h1>
                <p class="text-slate-500 mt-1">Verifikasi integritas dan tingkat enkripsi data voting.</p>
            </div>
        </div>

        <!-- Current Encryption Level -->
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-8 text-white shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black mb-2">Mode Enkripsi Aktif</h2>
                    <p class="text-indigo-100 text-sm mb-4">Level enkripsi yang sedang digunakan untuk vote baru.</p>
                    
                    @php
                        $currentLevel = \App\Services\VoteEncryptionService::getCurrentLevel();
                        $levelLabels = [
                            'standard' => 'Standard (AES-256)',
                            'high' => 'High (End-to-End)',
                            'blockchain' => 'Blockchain Mode'
                        ];
                    @endphp
                    
                    <div class="inline-flex items-center gap-3 px-6 py-3 bg-white/20 backdrop-blur-md rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="text-xl font-bold">{{ $levelLabels[$currentLevel] ?? 'Unknown' }}</span>
                    </div>
                </div>
            </div>
        </div>

        @php
            $votes = \App\Models\Vote::with('mahasiswa', 'kandidat')->latest()->limit(20)->get();
            $stats = [
                'total' => \App\Models\Vote::count(),
                'standard' => \App\Models\Vote::whereJsonContains('encryption_meta->level', 'standard')->count(),
                'high' => \App\Models\Vote::whereJsonContains('encryption_meta->level', 'high')->count(),
                'blockchain' => \App\Models\Vote::whereJsonContains('encryption_meta->level', 'blockchain')->count(),
            ];
            
            $blockchainIntegrity = null;
            if ($stats['blockchain'] > 0) {
                $blockchainIntegrity = \App\Services\VoteEncryptionService::verifyBlockchainIntegrity();
            }
        @endphp

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-slate-500 uppercase">Total Votes</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-800">{{ number_format($stats['total']) }}</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-slate-500 uppercase">Standard</span>
                    <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-800">{{ number_format($stats['standard']) }}</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-slate-500 uppercase">High E2E</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-800">{{ number_format($stats['high']) }}</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-slate-500 uppercase">Blockchain</span>
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-800">{{ number_format($stats['blockchain']) }}</p>
            </div>
        </div>

        <!-- Blockchain Integrity Check -->
        @if($blockchainIntegrity)
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl">
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Verifikasi Integritas Blockchain
            </h3>
            
            <div class="flex items-center gap-4">
                <div class="flex-1 p-4 rounded-xl {{ $blockchainIntegrity['valid'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex items-center gap-3">
                        @if($blockchainIntegrity['valid'])
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-bold text-green-800 text-lg">Chain Valid ✓</p>
                                <p class="text-sm text-green-600">All {{ $blockchainIntegrity['total_blocks'] }} blocks verified</p>
                            </div>
                        @else
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-bold text-red-800 text-lg">Chain Broken!</p>
                                <p class="text-sm text-red-600">{{ count($blockchainIntegrity['errors']) }} errors detected</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Vote List with Encryption Details -->
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl">
            <h3 class="text-xl font-bold text-slate-800 mb-6">Recent Encrypted Votes (20 Latest)</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wider pb-3">Vote ID</th>
                            <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wider pb-3">Mahasiswa</th>
                            <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wider pb-3">Encrypted Data</th>
                            <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wider pb-3">Encryption Level</th>
                            <th class="text-center text-xs font-bold text-slate-500 uppercase tracking-wider pb-3">Vote Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($votes as $vote)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4">
                                <span class="font-mono text-sm font-bold text-slate-700">#{{ $vote->id }}</span>
                            </td>
                            <td class="py-4">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $vote->mahasiswa->nama ?? 'Unknown' }}</p>
                                    <p class="text-xs text-slate-500">{{ $vote->mahasiswa->nim ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="font-mono text-xs bg-slate-100 px-3 py-2 rounded-lg text-slate-600 max-w-xs overflow-hidden text-ellipsis">
                                    {{ substr($vote->kandidat_id, 0, 40) }}...
                                </div>
                            </td>
                            <td class="py-4">
                                @php
                                    $level = $vote->encryption_meta['level'] ?? 'unknown';
                                    $colors = [
                                        'standard' => 'bg-green-100 text-green-700',
                                        'high' => 'bg-purple-100 text-purple-700',
                                        'blockchain' => 'bg-orange-100 text-orange-700'
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold {{ $colors[$level] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ strtoupper($level) }}
                                </span>
                            </td>
                            <td class="py-4 text-center">
                                <span class="text-sm text-slate-600">{{ $vote->created_at->diffForHumans() }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="font-medium">No votes found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
