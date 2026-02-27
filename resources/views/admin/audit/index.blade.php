<x-layouts.admin :title="$title">
    <div class="space-y-6">
        <!-- Header -->
        <!-- Responsive Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Audit Logs</h1>
                <p class="text-slate-500 mt-1 font-medium">Rekam jejak aktivitas sistem dan pengguna.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <a href="{{ route('admin.audit.print') }}" target="_blank"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 border border-blue-600 rounded-xl text-white text-sm font-bold hover:bg-blue-700 transition-all shadow-md shadow-blue-500/20 active:scale-95 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Unduh PDF</span>
                </a>

                <form action="{{ route('admin.audit.destroy') }}" method="POST" data-confirm="PERINGATAN: Apakah Anda yakin ingin MENGHAPUS SEMUA log aktivitas? Tindakan ini tidak dapat dibatalkan." class="flex-1 sm:flex-none">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-600 border border-red-600 rounded-xl text-white text-sm font-bold hover:bg-red-700 transition-all shadow-md shadow-red-500/20 active:scale-95 gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Hapus Semua Log</span>
                    </button>
                </form>

                <button onclick="window.location.reload()"
                    class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all shadow-sm active:scale-95"
                    title="Refresh Data">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($blockedIps->count() > 0)
        <!-- Security Monitor: Blocked IPs -->
        <!-- Security Monitor: Blocked IPs -->
        <div class="bg-red-50 border border-red-200 rounded-[2rem] p-4 sm:p-8">
            <h3 class="text-red-800 font-extrabold text-lg mb-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span>Security Alert: {{ $blockedIps->count() }} IP Diblokir</span>
            </h3>
            <div class="overflow-x-auto bg-white rounded-2xl border border-red-100 shadow-sm scrollbar-hide">
                <table class="w-full text-left text-sm min-w-[600px]">
                    <thead class="bg-red-50 text-red-900 uppercase text-[10px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4">IP Address</th>
                            <th class="px-6 py-4">Alasan</th>
                            <th class="px-6 py-4 text-center">Percobaan</th>
                            <th class="px-6 py-4">Status Blokir</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50">
                        @foreach($blockedIps as $ip)
                            <tr class="hover:bg-red-50/30 transition-colors">
                                <td class="px-6 py-4 font-mono text-red-700 font-bold whitespace-nowrap">{{ $ip->ip_address }}</td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-600">{{ $ip->reason }}</td>
                                <td class="px-6 py-4 text-center font-black text-red-600">{{ $ip->attempts }}x</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md bg-red-100 text-red-700 text-[10px] font-bold">
                                        {{ $ip->is_permanent ? 'PERMANEN' : ($ip->blocked_until ? $ip->blocked_until->diffForHumans() : '-') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.security.unblock', $ip->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-600 hover:text-white text-xs font-bold transition-all shadow-sm active:scale-95">
                                            Buka Blokir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Logs Table -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto scrollbar-hide">
                <table class="w-full text-left border-collapse min-w-[700px] lg:min-w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase text-slate-500 font-bold tracking-widest">
                            <th class="px-6 py-5">Waktu</th>
                            <th class="px-6 py-5">User</th>
                            <th class="px-6 py-5">Aksi</th>
                            <th class="px-6 py-5">IP Address</th>
                            <th class="px-6 py-5">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500 font-mono text-[10px] sm:text-xs">
                                    {{ $log->created_at->format('d/m/Y') }}<br>
                                    <span class="text-slate-400">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 leading-tight">{{ Str::words($log->user_name ?? 'System', 2) }}</span>
                                        @if(isset($log->user_name))
                                            <span class="text-[10px] text-slate-400 font-medium">Administrator</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider
                                            {{ str_contains(strtolower($log->action), 'delete') || str_contains(strtolower($log->action), 'reset') ? 'bg-red-50 text-red-600 border border-red-100' :
                                            (str_contains(strtolower($log->action), 'create') || str_contains(strtolower($log->action), 'add') ? 'bg-green-50 text-green-600 border border-green-100' :
                                            (str_contains(strtolower($log->action), 'update') || str_contains(strtolower($log->action), 'edit') ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-slate-100 text-slate-600 border border-slate-200')) }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-[10px] sm:text-xs text-slate-400 whitespace-nowrap">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px] truncate group-hover:break-words whitespace-normal" title="{{ $log->details }}">
                                    {{ $log->details ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-slate-400 font-medium">Belum ada log aktivitas yang tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
