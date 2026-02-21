<x-layouts.panitia :title="$title">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Audit Logs</h1>
                <p class="text-slate-500 text-sm mt-1">Rekam jejak aktivitas sistem dan pengguna.</p>
            </div>
            <div class="grid grid-cols-2 sm:flex gap-2 text-sm font-bold">
                <a href="{{ route('panitia.audit.print') }}" target="_blank"
                    class="px-4 py-2 bg-blue-600 border border-blue-600 rounded-xl text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Unduh PDF</span>
                </a>
                
                <form action="{{ route('panitia.audit.destroy') }}" method="POST" class="col-span-1" data-confirm="Apakah Anda yakin ingin membersihkan log aktivitas ini? Log Admin tidak akan terhapus.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-50 border border-red-200 rounded-xl text-red-600 hover:bg-red-100 transition-all flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Hapus Log</span>
                    </button>
                </form>

                <button onclick="window.location.reload()"
                    class="col-span-2 sm:col-span-1 px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Refresh</span>
                </button>
            </div>
        </div>

        <!-- Desktop Logs Table -->
        <div class="hidden md:block bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
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
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    {{ $log->user_name ?? 'System/Guest' }}
                                </td>
                                <td class="px-6 py-4">
                                     @php
                                        $action = strtolower($log->action);
                                        $colorClass = 'bg-slate-100 text-slate-600';
                                        if (str_contains($action, 'delete') || str_contains($action, 'reset') || str_contains($action, 'hapus')) {
                                            $colorClass = 'bg-red-50 text-red-700 border border-red-100';
                                        } elseif (str_contains($action, 'create') || str_contains($action, 'add') || str_contains($action, 'tambah')) {
                                            $colorClass = 'bg-green-50 text-green-700 border border-green-100';
                                        } elseif (str_contains($action, 'update') || str_contains($action, 'edit') || str_contains($action, 'ubah')) {
                                            $colorClass = 'bg-blue-50 text-blue-700 border border-blue-100';
                                        }
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $colorClass }}">
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

        <!-- Mobile Card View -->
        <div class="md:hidden flex flex-col gap-4">
             @forelse($logs as $log)
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative">
                     <div class="flex justify-between items-start mb-3">
                         <div>
                             <span class="text-[10px] font-mono font-bold text-slate-400 block mb-1">{{ $log->created_at->format('d M Y H:i') }}</span>
                             <h4 class="font-bold text-slate-800 text-sm uppercase">{{ $log->user_name ?? 'System/Guest' }}</h4>
                         </div>
                         @php
                            $action = strtolower($log->action);
                            $colorClass = 'bg-slate-100 text-slate-600';
                           if (str_contains($action, 'delete') || str_contains($action, 'reset') || str_contains($action, 'hapus')) {
                                $colorClass = 'bg-red-50 text-red-700 border border-red-100';
                            } elseif (str_contains($action, 'create') || str_contains($action, 'add') || str_contains($action, 'tambah')) {
                                $colorClass = 'bg-green-50 text-green-700 border border-green-100';
                            } elseif (str_contains($action, 'update') || str_contains($action, 'edit') || str_contains($action, 'ubah')) {
                                $colorClass = 'bg-blue-50 text-blue-700 border border-blue-100';
                            }
                        @endphp
                        <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $colorClass }}">
                            {{ $log->action }}
                        </span>
                     </div>
                     <div class="bg-slate-50 rounded-lg p-3 text-xs text-slate-600 border border-slate-100 font-mono break-all leading-relaxed">
                         {{ $log->details ?? 'Tidak ada detail' }}
                     </div>
                     <div class="mt-2 flex justify-end">
                         <span class="text-[10px] text-slate-400 font-mono">{{ $log->ip_address }}</span>
                     </div>
                </div>
             @empty
                <div class="text-center py-12 px-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-slate-500 font-medium text-sm">Belum ada log aktivitas.</p>
                </div>
             @endforelse

             @if($logs->hasPages())
                <div class="py-4">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.panitia>