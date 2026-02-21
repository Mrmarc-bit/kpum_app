<x-layouts.admin :title="$title">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Audit Logs</h1>
                <p class="text-slate-500 mt-1">Rekam jejak aktivitas sistem dan pengguna.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.audit.print') }}" target="_blank"
                    class="px-3 py-2 bg-blue-600 border border-blue-600 rounded-lg text-white text-sm hover:bg-blue-700 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Unduh PDF
                </a>

                <form action="{{ route('admin.audit.destroy') }}" method="POST" data-confirm="PERINGATAN: Apakah Anda yakin ingin MENGHAPUS SEMUA log aktivitas? Tindakan ini tidak dapat dibatalkan.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2 bg-red-600 border border-red-600 rounded-lg text-white text-sm hover:bg-red-700 transition-colors flex items-center gap-1 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus Semua Log
                    </button>
                </form>

                <button onclick="window.location.reload()"
                    class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-slate-600 text-sm hover:bg-slate-50 transition-colors">
                    Refresh
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
        <div class="bg-red-50 border border-red-200 rounded-3xl p-6 mb-6">
            <h3 class="text-red-800 font-bold text-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Security Alert: {{ $blockedIps->count() }} IP Diblokir
            </h3>
            <div class="overflow-x-auto bg-white rounded-xl border border-red-100">
                <table class="w-full text-left text-sm">
                    <thead class="bg-red-100/50 text-red-900 uppercase text-xs font-bold">
                        <tr>
                            <th class="px-4 py-3">IP Address</th>
                            <th class="px-4 py-3">Alasan</th>
                            <th class="px-4 py-3">Percobaan</th>
                            <th class="px-4 py-3">Diblokir Sampai</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-100">
                        @foreach($blockedIps as $ip)
                            <tr>
                                <td class="px-4 py-3 font-mono text-red-700 font-bold">{{ $ip->ip_address }}</td>
                                <td class="px-4 py-3 text-red-600">{{ $ip->reason }}</td>
                                <td class="px-4 py-3 text-center font-bold">{{ $ip->attempts }}x</td>
                                <td class="px-4 py-3 text-slate-500">
                                    {{ $ip->is_permanent ? 'PERMANEN' : ($ip->blocked_until ? $ip->blocked_until->diffForHumans() : '-') }}
                                </td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('admin.security.unblock', $ip->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-white border border-red-200 text-red-600 rounded-lg hover:bg-red-50 text-xs font-bold transition-colors">
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
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold tracking-wider">
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Aksi</th>
                            <th class="px-6 py-4">IP Address</th>
                            <th class="px-6 py-4">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @forelse($logs as $log)
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-slate-500 font-mono text-xs">
                                                    {{ $log->created_at->format('d M Y H:i:s') }}
                                                </td>
                                                <td class="px-6 py-4 font-medium text-slate-900">
                                                    {{ $log->user_name ?? 'System/Guest' }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span
                                                        class="px-2 py-1 rounded text-xs font-bold 
                                                            {{ str_contains(strtolower($log->action), 'delete') || str_contains(strtolower($log->action), 'reset') ? 'bg-red-100 text-red-700' :
                            (str_contains(strtolower($log->action), 'create') || str_contains(strtolower($log->action), 'add') ? 'bg-green-100 text-green-700' :
                                (str_contains(strtolower($log->action), 'update') || str_contains(strtolower($log->action), 'edit') ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600')) }}">
                                                        {{ $log->action }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                                    {{ $log->ip_address }}
                                                </td>
                                                <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="{{ $log->details }}">
                                                    {{ $log->details ?? '-' }}
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada log aktivitas.
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