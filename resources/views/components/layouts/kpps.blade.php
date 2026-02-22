<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'KPPS' }} - {{ $settings['app_name'] ?? 'KPUM System' }}</title>

    @if(isset($settings['app_logo']) && $settings['app_logo'])
        <link rel="icon" href="{{ asset((string) $settings['app_logo']) }}" sizes="32x32">
        <link rel="icon" href="{{ asset((string) $settings['app_logo']) }}" sizes="192x192">
        <link rel="apple-touch-icon" href="{{ asset((string) $settings['app_logo']) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Turbo - SPA Navigation -->
    <script type="importmap">
        {
            "imports": {
                "@hotwired/turbo": "https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-esm.js"
            }
        }
    </script>
    <script type="module">
        import * as Turbo from "@hotwired/turbo"
        window.Turbo = Turbo;
    </script>

    <style>
        .swal2-popup {
            font-family: 'Inter', sans-serif !important;
            border-radius: 1rem !important;
        }

        .swal2-title {
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
        }

        .swal2-html-container {
            font-size: 0.875rem !important;
            color: #64748b !important;
        }
        
        /* Turbo Progress Bar - Modern & Sleek */
        .turbo-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
            background-size: 200% 100%;
            animation: gradient-shift 2s ease infinite;
            z-index: 9999;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }
        
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
    </style>
</head>

<body
    class="font-sans antialiased h-full flex overflow-hidden bg-slate-50 selection:bg-blue-100 selection:text-blue-900"
    x-data="{ sidebarOpen: false }">
    <!-- Ambient Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]">
        </div>
        <div
            class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-blue-100/50 blur-[100px] animate-[pulse_8s_ease-in-out_infinite]">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-100/50 blur-[100px] animate-[pulse_10s_ease-in-out_infinite]">
        </div>
    </div>

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm z-40 lg:hidden" x-transition.opacity
        @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white/80 backdrop-blur-xl border-r border-white/50 shadow-[4px_0_24px_rgba(0,0,0,0.02)] transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-16 flex items-center px-6 border-b border-slate-100/50 bg-white/50 backdrop-blur-md">
            <div class="flex items-center gap-3">
                @if(isset($settings['app_logo']) && $settings['app_logo'])
                    <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo"
                        class="w-8 h-8 rounded-lg object-contain bg-white shadow-sm border border-slate-100">
                @else
                    <div
                        class="bg-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        S</div>
                @endif
                <span
                    class="font-bold tracking-tight text-lg text-slate-900">{{ $settings['app_name'] ?? 'KPPS Panel' }}</span>
            </div>
        </div>

        <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
            <div class="px-3 mb-2 mt-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Utama</p>
            </div>

            <a href="{{ route('kpps.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('kpps.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('kpps.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
                Dashboard
            </a>

            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Operasional</p>
            </div>

            <a href="{{ route('kpps.scanner.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('kpps.scanner.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('kpps.scanner.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Scanner</span>
            </a>

             <a href="{{ route('kpps.profile.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('kpps.profile.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('kpps.profile.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Profil Saya</span>
            </a>
            
             <form method="POST" action="{{ route('logout') }}" class="mt-8 px-3" data-turbo="false">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors group">
                    <svg class="w-5 h-5 text-red-400 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Log Out
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative z-10 bg-transparent">
        <header
            class="h-16 bg-white/70 backdrop-blur-xl border-b border-white/60 shadow-sm flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-20">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-500 hover:text-slate-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
            <div class="flex-1 flex justify-between items-center">
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">{{ $title ?? 'Dashboard' }}</h2>
                <div class="flex items-center gap-4">

                    <!-- User Menu -->
                    <div class="flex items-center gap-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover shadow-sm ring-2 ring-white">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-white">
                                {{ substr(auth()->user()->name ?? 'K', 0, 1) }}
                            </div>
                        @endif
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-semibold text-slate-700 leading-none">
                                {{ auth()->user()->name ?? 'Petugas KPPS' }}
                            </p>
                            <p class="text-[10px] text-slate-500 font-medium leading-none mt-1">KPPS Access</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scroll-smooth">
            {{ $slot }}
        </main>
    </div>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        const sessionSuccess = <?php echo json_encode(session('success')); ?>;
        const sessionError = <?php echo json_encode(session('error')); ?>;

        if (sessionSuccess) {
            Toast.fire({
                icon: 'success',
                title: sessionSuccess
            });
        }

        if (sessionError) {
            Toast.fire({
                icon: 'error',
                title: sessionError
            });
        }
    </script>
</body>

</html>
