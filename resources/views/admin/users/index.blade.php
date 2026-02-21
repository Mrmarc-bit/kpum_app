<x-layouts.admin :title="$title">
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Pengguna</h1>
                <p class="text-slate-500 mt-2 font-medium text-sm sm:text-base">Kelola akses dan hak pengguna dalam sistem.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 hover:-translate-y-1 text-sm tracking-wide w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Pengguna
            </a>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">User Info</th>
                            <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">Role Check</th>
                            <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">Verifikasi</th>
                            <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px]">Bergabung</th>
                            <th class="px-8 py-5 font-bold text-slate-400 uppercase tracking-wider text-[10px] text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        @php
                                            $avatars = ['🦊', '🐱', '🐼', '🐨', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🦄', '🐲', '🦖', '👽', '🤖', '👾', '👻', '🤠', '😎', '🦸‍♂️'];
                                            $bgColors = ['bg-blue-100 border-blue-200', 'bg-emerald-100 border-emerald-200', 'bg-amber-100 border-amber-200', 'bg-purple-100 border-purple-200', 'bg-rose-100 border-rose-200', 'bg-indigo-100 border-indigo-200', 'bg-cyan-100 border-cyan-200'];
                                            $hash = crc32($user->email);
                                            $selectedAvatar = $avatars[$hash % count($avatars)];
                                            $selectedBg = $bgColors[$hash % count($bgColors)];
                                        @endphp
                                        <div class="w-10 h-10 rounded-full {{ $selectedBg }} border ring-2 ring-white shadow-sm flex-shrink-0 flex items-center justify-center text-lg select-none">
                                            {{ $selectedAvatar }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-500 font-medium">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    @php
                                        $roleStyles = [
                                            'admin' => 'bg-purple-50 text-purple-700 ring-purple-100',
                                            'panitia' => 'bg-blue-50 text-blue-700 ring-blue-100',
                                            'mahasiswa' => 'bg-slate-50 text-slate-600 ring-slate-100'
                                        ];
                                        $roleStyle = $roleStyles[$user->role] ?? 'bg-slate-50 text-slate-600 ring-slate-100';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold capitalize ring-1 ring-inset {{ $roleStyle }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wide text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full ring-1 ring-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Verified
                                        </span>
                                    @else
                                        <div class="flex flex-col gap-1.5">
                                            <span class="inline-flex w-fit items-center gap-1.5 text-[10px] font-bold uppercase tracking-wide text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full ring-1 ring-amber-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                            </span>
                                            
                                            <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                                                    <span>Verifikasi Sekarang</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                                    {{ $user->created_at->diffForHumans() }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        
                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <p class="font-medium text-slate-600">Tidak ada pengguna ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden flex flex-col gap-4">
            @forelse($users as $user)
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden">
                    <div class="flex items-start gap-4 mb-4">
                        @php
                            $avatars = ['🦊', '🐱', '🐼', '🐨', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🦄', '🐲', '🦖', '👽', '🤖', '👾', '👻', '🤠', '😎', '🦸‍♂️'];
                            $bgColors = ['bg-blue-100 border-blue-200', 'bg-emerald-100 border-emerald-200', 'bg-amber-100 border-amber-200', 'bg-purple-100 border-purple-200', 'bg-rose-100 border-rose-200', 'bg-indigo-100 border-indigo-200', 'bg-cyan-100 border-cyan-200'];
                            $hash = crc32($user->email);
                            $selectedAvatar = $avatars[$hash % count($avatars)];
                            $selectedBg = $bgColors[$hash % count($bgColors)];
                        @endphp
                        <div class="w-12 h-12 rounded-full {{ $selectedBg }} border ring-2 ring-white shadow-sm flex-shrink-0 flex items-center justify-center text-2xl select-none">
                            {{ $selectedAvatar }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-900 text-base truncate">{{ $user->name }}</h3>
                            <p class="text-xs text-slate-500 font-medium truncate">{{ $user->email }}</p>
                            
                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                @php
                                    $roleStyles = [
                                        'admin' => 'bg-purple-50 text-purple-700 ring-purple-100',
                                        'panitia' => 'bg-blue-50 text-blue-700 ring-blue-100',
                                        'mahasiswa' => 'bg-slate-50 text-slate-600 ring-slate-100'
                                    ];
                                    $roleStyle = $roleStyles[$user->role] ?? 'bg-slate-50 text-slate-600 ring-slate-100';
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold capitalize ring-1 ring-inset {{ $roleStyle }}">
                                    {{ $user->role }}
                                </span>

                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wide text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full ring-1 ring-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wide text-amber-600 bg-amber-50 px-2 py-1 rounded-full ring-1 ring-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bergabung: {{ $user->created_at->diffForHumans() }}</span>
                        
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 bg-slate-50 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 bg-slate-50 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    
                    @if(!$user->email_verified_at)
                         <div class="mt-4 pt-4 border-t border-slate-50">
                             <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-full text-xs font-bold text-blue-600 bg-blue-50 py-2 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2">
                                    <span>Verifikasi Sekarang</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                         </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 px-4 bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                    <p class="font-medium text-slate-600">Tidak ada pengguna ditemukan.</p>
                </div>
            @endforelse
            
            @if($users->hasPages())
                <div class="py-4">
                     {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>