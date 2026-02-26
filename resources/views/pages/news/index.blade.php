<x-layouts.guest title="Berita & Pengumuman - KPUM UNUGHA">

    {{-- Shared Navbar --}}
    @include('pages._navbar')

    {{-- Hero Section --}}
    <section class="pt-32 pb-20 relative overflow-hidden bg-white">
        <!-- Floating Elements -->
        <div class="absolute top-20 right-[10%] w-64 h-64 bg-blue-100 rounded-full blur-[100px] opacity-50"></div>
        <div class="absolute bottom-10 left-[5%] w-80 h-80 bg-indigo-100 rounded-full blur-[120px] opacity-40"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-black uppercase tracking-widest mb-8">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                    </span>
                    Update Terkini
                </div>

                <h1 class="text-6xl sm:text-8xl font-black text-slate-900 leading-[0.85] tracking-tighter mb-10">
                    Grow Bold.<br>
                    Move Free.<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Play Hard.</span>
                </h1>

                <p class="text-xl text-slate-600 font-medium max-w-2xl leading-relaxed">
                    Satu ruang di mana mahasiswa menemukan informasi seputar pesta demokrasi, pergerakan, dan edukasi politik — semua dalam lingkungan yang suportif dan transparan.
                </p>

                <!-- Search/Newsletter Style -->
                <div class="mt-12 max-w-md">
                    <form action="#" class="relative group">
                        <input type="email" placeholder="Cari berita atau pengumuman..." class="w-full bg-slate-50 border-b-2 border-slate-200 py-6 px-1 pr-32 focus:outline-none focus:border-blue-600 transition-colors text-lg font-bold placeholder:text-slate-400 placeholder:font-medium">
                        <button class="absolute right-0 top-1/2 -translate-y-1/2 bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold hover:bg-blue-600 transition-all shadow-xl shadow-slate-200">
                            Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- News Grid Section --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($posts as $index => $item)
                @php
                    $bgColors = ['bg-[#D4E2FF]', 'bg-[#F0F5C4]', 'bg-[#CAB5D6]', 'bg-[#FFD4E2]', 'bg-[#C4F5E0]'];
                    $colorIndex = $index % count($bgColors);
                @endphp
                <div class="group flex flex-col bg-[#F8F9FA] rounded-[3rem] border border-slate-100 overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-4 h-full">
                    <!-- Top Section -->
                    <div class="p-10 pb-6 flex justify-between items-start">
                        <span class="px-5 py-2 rounded-full bg-slate-200/50 text-slate-600 text-[10px] font-black uppercase tracking-widest">
                            {{ $item->category }}
                        </span>

                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-900 border border-slate-200 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                            <svg class="w-6 h-6 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="px-10 flex-1">
                        <h3 class="text-3xl font-black text-slate-900 mb-6 group-hover:text-blue-600 transition-colors leading-tight">
                            <a href="{{ route('news.show', $item->slug) }}">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p class="text-slate-500 font-medium leading-relaxed">
                            {{ $item->excerpt }}
                        </p>
                    </div>

                    <!-- Illustration/Graphic Part -->
                    <div class="mt-10 p-4 pt-0 h-72">
                        <div class="w-full h-full rounded-[2.5rem] {{ $bgColors[$colorIndex] }} flex items-center justify-center relative overflow-hidden group-hover:scale-[1.02] transition-transform duration-500">
                            <!-- Simple Abstract Shape/Icon -->
                            <div class="opacity-80 group-hover:opacity-100 transition-opacity">
                                @if($index % 3 == 0)
                                    <svg class="w-32 h-32 text-blue-500/40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                                @elseif($index % 3 == 1)
                                    <svg class="w-32 h-32 text-green-500/40" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                                @else
                                    <svg class="w-32 h-32 text-purple-500/40" fill="currentColor" viewBox="0 0 24 24"><path d="M21 3.01H3c-1.1 0-2 .9-2 2V13c0 1.1.9 2 2 2h8l-4 4v1h10v-1l-4-4h8c1.1 0 2-.9 2-2v-7.99c0-1.11-.89-2-2-2zM21 13H3V5.01h18V13z"/></svg>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-slate-400 font-bold text-xl italic">Belum ada berita yang diterbitkan.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination (if needed) -->
            @if($posts->hasPages())
                <div class="mt-20">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- Footer --}}
    @include('pages._footer')

</x-layouts.guest>
