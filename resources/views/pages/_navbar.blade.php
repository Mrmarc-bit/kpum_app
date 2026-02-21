{{-- Shared Navbar for Static Pages --}}
<nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-100/80 shadow-sm shadow-slate-900/5">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                {{-- Logo: sama persis dengan welcome page --}}
                @if(isset($settings['app_logo']) && $settings['app_logo'])
                    <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo"
                        class="w-9 h-9 rounded-full object-contain bg-white shadow-sm ring-2 ring-white/50 group-hover:scale-105 transition-transform">
                @else
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-black shadow-lg shadow-blue-500/30 ring-2 ring-white/50 group-hover:scale-105 transition-transform">
                        K
                    </div>
                @endif
                <span class="font-bold text-slate-900">{{ $settings['app_name'] ?? 'KPUM System' }}</span>
            </a>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</nav>
