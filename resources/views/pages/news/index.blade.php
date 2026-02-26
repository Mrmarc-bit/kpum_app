<x-layouts.guest title="Berita & Pengumuman - KPUM UNUGHA">

    {{-- Shared Navbar --}}
    @include('pages._navbar')

    {{-- Main Section --}}
    <section class="min-h-[100dvh] bg-white pt-28 pb-20 relative lg:pt-36">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10 relative z-10">

            <div class="flex flex-col lg:flex-row gap-12 lg:gap-16 xl:gap-20 items-start">

                {{-- Left Sidebar (Sticky Hero) --}}
                <div class="w-full lg:w-[35%] xl:w-[32%] lg:sticky lg:top-36 flex-shrink-0">
                    <h1 class="text-[3.5rem] md:text-[4.5rem] lg:text-[4rem] xl:text-[4.75rem] font-black text-[#111111] leading-[0.95] tracking-tighter mb-8 break-normal">
                        Grow Bold.<br>
                        Move Free.<br>
                        Play Hard.
                    </h1>

                    <p class="text-[16px] text-slate-500 font-medium max-w-[95%] lg:max-w-full leading-[1.6] mb-12">
                        A space where students discover information about the festival of democracy, movement, and political education — all in a supportive and transparent environment.
                    </p>

                    <!-- Search Form Style -->
                    <div class="w-full">
                        <form action="{{ route('news.index') }}" method="GET" class="relative flex items-end gap-3 w-full">
                            <div class="flex-1 border-b-2 border-slate-200 pb-2 relative group focus-within:border-[#111111] transition-colors">
                                <label class="text-[11px] uppercase tracking-widest text-slate-400 font-bold select-none absolute -top-5 left-0">Search articles</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Enter keyword..." class="w-full bg-transparent border-none p-0 pb-1 focus:outline-none focus:ring-0 text-base font-medium text-[#111111] placeholder:text-slate-300">
                            </div>
                            <button type="submit" class="bg-[#111111] text-white px-7 py-3 md:px-8 md:py-3.5 rounded-xl font-bold hover:bg-slate-800 transition-colors whitespace-nowrap hidden sm:block">
                                Search
                            </button>
                            <button type="button" class="bg-[#F4F5F6] text-[#111111] p-3 md:p-3.5 rounded-xl hover:bg-slate-200 transition-colors flex-shrink-0">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Right Content (Cards Grid) --}}
                <div class="w-full lg:w-[65%] xl:w-[68%]">
                    <div class="grid md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 xl:gap-8">
                        @forelse($posts as $index => $item)
                        @php
                            $bgColors = ['bg-[#C6CDFF]', 'bg-[#EAF0B0]', 'bg-[#A288A6]', 'bg-[#FFD4E2]', 'bg-[#C4F5E0]'];
                            $colorIndex = $index % count($bgColors);
                        @endphp
                        <!-- Card Item -->
                        <div class="group relative flex flex-col bg-[#F9FAFB] rounded-[2.5rem] overflow-hidden hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.05)] transition-all duration-500 hover:-translate-y-1 h-full border border-slate-100 min-h-[460px]">

                            <!-- Top Info & Text Content -->
                            <div class="px-7 pt-7 pb-5 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-5">
                                    <span class="px-4 py-2 rounded-full bg-[#E5E7EB] text-slate-700 text-[10px] font-black tracking-widest uppercase">
                                        {{ $item->category }}
                                    </span>
                                    <div class="w-9 h-9 bg-white rounded-full flex items-center justify-center text-slate-800 shadow-sm border border-slate-100 group-hover:bg-[#111] group-hover:border-[#111] group-hover:text-white transition-colors duration-300">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="7" y1="17" x2="17" y2="7"></line>
                                            <polyline points="7 7 17 7 17 17"></polyline>
                                        </svg>
                                    </div>
                                </div>

                                <h3 class="text-2xl xl:text-[1.75rem] font-black text-[#111111] mb-3 tracking-tight leading-[1.1] pr-2">
                                    <a href="{{ route('news.show', $item->slug) }}" class="before:absolute before:inset-0">
                                        {{ $item->title }}
                                    </a>
                                </h3>
                                <p class="text-slate-500 text-sm xl:text-[15px] leading-relaxed line-clamp-3 font-medium">
                                    {{ $item->excerpt }}
                                </p>
                            </div>

                            <!-- Decorative Footer Block -->
                            <div class="px-3 pb-3 mt-auto w-full">
                                <div class="h-[260px] md:h-[280px] w-full rounded-[2rem] {{ $bgColors[$colorIndex] }} flex items-center justify-center relative overflow-hidden group-hover:scale-[0.98] transition-transform duration-500">
                                    <!-- Illustrations based on index -->
                                    @if($index % 3 == 0)
                                        <!-- Box Blob SVG -->
                                        <svg class="w-52 h-52 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M 30 140 L 30 70 L 170 70 L 170 140" fill="none" stroke="#111" stroke-width="4" stroke-linecap="square"/>
                                            <path fill="#FF9E66" d="M100,80 l15,20 l25,-10 l-10,25 l25,15 l-25,10 l10,25 l-25,-10 l-15,20 l-15,-20 l-25,10 l10,-25 l-25,-10 l25,-15 l-10,-25 l25,10 z" />
                                            <path d="M 85 110 Q 90 100 95 110" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                            <path d="M 105 110 Q 110 100 115 110" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                        </svg>
                                    @elseif($index % 3 == 1)
                                        <!-- Plant Volley SVG -->
                                        <svg class="w-52 h-52 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path fill="#F28B9F" d="M100,160 C50,160 30,140 30,120 C30,110 40,90 50,90 C50,90 60,110 70,120 L80,70 C80,60 100,50 100,70 L110,110 L120,60 C120,50 140,50 140,60 L140,120 C140,120 160,100 170,100 C180,100 180,120 170,130 C150,150 130,160 100,160 Z"/>
                                            <path d="M 85 130 Q 90 120 95 130" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                            <path d="M 115 130 Q 120 120 125 130" fill="none" stroke="#111" stroke-width="4" stroke-linecap="round"/>
                                            <circle cx="120" cy="70" r="22" fill="#EAF0B0" stroke="#111" stroke-width="4"/>
                                            <path d="M 100 60 Q 120 50 140 80" fill="none" stroke="#111" stroke-width="3"/>
                                            <path d="M 105 85 Q 125 70 135 55" fill="none" stroke="#111" stroke-width="3"/>
                                        </svg>
                                    @else
                                        <!-- Racket SVG -->
                                        <svg class="w-52 h-52 drop-shadow-xl scale-100 group-hover:scale-110 transition-transform duration-700" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <path fill="#B294BB" d="M 120 40 Q 180 30 180 100 Q 180 170 110 170 Q 50 170 50 100 Q 50 50 120 40 Z" />
                                            <circle cx="90" cy="90" r="30" fill="none" stroke="#111" stroke-width="5"/>
                                            <path d="M 110 110 L 140 140" fill="none" stroke="#111" stroke-width="12" stroke-linecap="round"/>
                                            <circle cx="140" cy="80" r="14" fill="#F4F4F4" stroke="#111" stroke-width="4"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-20 text-center">
                            <p class="text-slate-400 font-medium text-xl">No articles published yet.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($posts->hasPages())
                        <div class="mt-16 sm:mt-20">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('pages._footer')

</x-layouts.guest>
