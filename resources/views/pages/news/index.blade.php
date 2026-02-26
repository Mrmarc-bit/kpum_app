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

                <p class="text-xl text-slate-500 font-medium max-w-2xl leading-relaxed">
                    Satu ruang di mana mahasiswa menemukan informasi seputar pesta demokrasi, pergerakan, dan edukasi politik — semua dalam lingkungan yang suportif dan transparan.
                </p>

                <!-- Search/Newsletter Style -->
                <div class="mt-12 max-w-xl">
                    <form action="#" class="relative flex items-center bg-white rounded-2xl p-2 border-2 border-slate-100 focus-within:ring-4 focus-within:ring-blue-50 focus-within:border-blue-500 transition-all shadow-sm">
                        <div class="pl-4 pr-2 text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" placeholder="Cari berita atau pengumuman..." class="w-full bg-transparent border-none py-3 px-2 focus:outline-none focus:ring-0 text-slate-700 font-medium placeholder:text-slate-400">
                        <button class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 whitespace-nowrap">
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
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($posts as $index => $item)
                @php
                    $bgColors = ['bg-blue-100', 'bg-lime-100', 'bg-purple-100', 'bg-pink-100', 'bg-emerald-100'];
                    $colorIndex = $index % count($bgColors);
                @endphp
                <div class="group relative flex flex-col bg-slate-50 rounded-[2.5rem] overflow-hidden hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] transition-all duration-500 hover:-translate-y-2 h-full border border-slate-100">

                    <!-- Top Info -->
                    <div class="p-8 pb-4">
                        <div class="flex justify-between items-start mb-6">
                            <span class="px-4 py-1.5 rounded-full bg-[#E5E7EB] text-slate-700 text-xs font-black tracking-wide">
                                {{ $item->category }}
                            </span>
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-900 border-2 border-slate-100 group-hover:border-slate-900 transition-colors shadow-sm">
                                <svg class="w-4 h-4 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                        </div>

                        <h3 class="text-3xl font-black text-slate-900 mb-4 tracking-tight leading-[1.1]">
                            <a href="{{ route('news.show', $item->slug) }}" class="before:absolute before:inset-0">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-3 font-medium">
                            {{ $item->excerpt }}
                        </p>
                    </div>

                    <!-- Decorative Footer Block -->
                    <div class="px-3 pb-3 mt-auto">
                        <div class="h-[260px] w-full rounded-[2rem] {{ $bgColors[$colorIndex] }} flex items-center justify-center relative overflow-hidden group-hover:bg-opacity-90 transition-colors duration-500">

                            @if($index % 3 == 0)
                                <!-- Orangey spiky blob stretching on a bar -->
                                <svg class="w-48 h-48 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M 30 140 L 30 70 L 170 70 L 170 140" fill="none" stroke="#111" stroke-width="4" stroke-linecap="square"/>
                                    <path fill="#FF9E66" d="M100,80 l15,20 l25,-10 l-10,25 l25,15 l-25,10 l10,25 l-25,-10 l-15,20 l-15,-20 l-25,10 l10,-25 l-25,-10 l25,-15 l-10,-25 l25,10 z" />
                                    <!-- Cute Eyes -->
                                    <path d="M 85 110 Q 90 100 95 110" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                    <path d="M 105 110 Q 110 100 115 110" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                </svg>
                            @elseif($index % 3 == 1)
                                <!-- Pink multi-fingered plant playing volleyball -->
                                <svg class="w-48 h-48 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="#F28B9F" d="M100,160 C50,160 30,140 30,120 C30,110 40,90 50,90 C50,90 60,110 70,120 L80,70 C80,60 100,50 100,70 L110,110 L120,60 C120,50 140,50 140,60 L140,120 C140,120 160,100 170,100 C180,100 180,120 170,130 C150,150 130,160 100,160 Z"/>
                                    <!-- Cute Face -->
                                    <path d="M 85 130 Q 90 120 95 130" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                    <path d="M 115 130 Q 120 120 125 130" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                    <!-- Volleyball -->
                                    <circle cx="120" cy="70" r="22" fill="#EAF0B0" stroke="#111" stroke-width="4"/>
                                    <path d="M 100 60 Q 120 50 140 80" fill="none" stroke="#111" stroke-width="3"/>
                                    <path d="M 105 85 Q 125 70 135 55" fill="none" stroke="#111" stroke-width="3"/>
                                </svg>
                            @else
                                <!-- Abstract forms with a ping-pong style racket -->
                                <svg class="w-48 h-48 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="#B294BB" d="M 120 40 Q 180 30 180 100 Q 180 170 110 170 Q 50 170 50 100 Q 50 50 120 40 Z" />
                                    <!-- Racket -->
                                    <circle cx="90" cy="90" r="30" fill="none" stroke="#111" stroke-width="5"/>
                                    <path d="M 110 110 L 140 140" fill="none" stroke="#111" stroke-width="12" stroke-linecap="round"/>
                                    <!-- Ball/Blob -->
                                    <circle cx="140" cy="80" r="14" fill="#F4F4F4" stroke="#111" stroke-width="4"/>
                                </svg>
                            @endif
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
