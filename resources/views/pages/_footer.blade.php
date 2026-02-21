{{-- Shared Footer for Static Pages --}}
<footer class="bg-white py-16 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        @if(isset($settings['app_logo']) && $settings['app_logo'])
            <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo" class="block mx-auto
                w-24 h-24
                object-contain
                bg-white
                rounded-full
                shadow-md
                ring-2 ring-white/60
                mb-4" />
        @else
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mx-auto mb-6 shadow-lg shadow-blue-500/30">K</div>
        @endif
        <p class="text-slate-500 text-sm mb-8 font-medium">
            &copy; {{ date('Y') }} <br>Komisi Pemilihan Umum Mahasiswa.<br>
            Universitas Nahdlatul Ulama Al-Ghazali Cilacap.
        </p>
        <div class="flex justify-center flex-wrap gap-8 text-sm font-semibold text-slate-400">
            <a href="{{ route('privacy-policy') }}" class="hover:text-blue-600 transition-colors {{ request()->routeIs('privacy-policy') ? 'text-blue-600' : '' }}">Privacy Policy</a>
            <a href="{{ route('terms-of-service') }}" class="hover:text-blue-600 transition-colors {{ request()->routeIs('terms-of-service') ? 'text-blue-600' : '' }}">Terms of Service</a>
            <a href="{{ route('contact-support') }}" class="hover:text-blue-600 transition-colors {{ request()->routeIs('contact-support') ? 'text-blue-600' : '' }}">Contact Support</a>
            <a href="{{ route('documentation') }}" class="hover:text-blue-600 transition-colors {{ request()->routeIs('documentation') ? 'text-blue-600' : '' }}">Documentation</a>
        </div>
    </div>
</footer>

