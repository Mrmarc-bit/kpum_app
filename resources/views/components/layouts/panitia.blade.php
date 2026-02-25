<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Panitia' }} - {{ $settings['app_name'] ?? 'KPUM System' }}</title>

    @if(isset($settings['app_logo']) && $settings['app_logo'])
        <link rel="icon" href="{{ asset((string) $settings['app_logo']) }}" sizes="32x32">
        <link rel="icon" href="{{ asset((string) $settings['app_logo']) }}" sizes="192x192">
        <link rel="apple-touch-icon" href="{{ asset((string) $settings['app_logo']) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
        console.log('✅ Turbo loaded:', Turbo);
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
        
        /* Turbo Progress Bar */
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
    class="font-sans antialiased h-full flex overflow-hidden bg-slate-50 selection:bg-purple-100 selection:text-purple-900"
    x-data="{ sidebarOpen: false }">
    <!-- Ambient Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]">
        </div>
        <div
            class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-purple-100/50 blur-[100px] animate-[pulse_8s_ease-in-out_infinite]">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-pink-100/50 blur-[100px] animate-[pulse_10s_ease-in-out_infinite]">
        </div>
    </div>

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm z-40 lg:hidden" x-transition.opacity
        @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white/80 backdrop-blur-xl border-r border-white/50 shadow-[4px_0_24px_rgba(0,0,0,0.02)] transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 flex flex-col"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-16 shrink-0 flex items-center px-6 border-b border-slate-100/50 bg-white/50 backdrop-blur-md">
            <div class="flex items-center gap-3">
                @if(isset($settings['app_logo']) && $settings['app_logo'])
                    <img src="{{ asset((string) $settings['app_logo']) }}" alt="Logo"
                        class="w-8 h-8 rounded-lg object-contain bg-white shadow-sm border border-slate-100">
                @else
                    <div
                        class="bg-purple-600 w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        P</div>
                @endif
                <span
                    class="font-bold tracking-tight text-lg text-slate-900">{{ $settings['app_name'] ?? 'Panitia' }}</span>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto pb-24">
            <div class="px-3 mb-2 mt-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Utama</p>
            </div>

            <a href="{{ route('panitia.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.dashboard') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.dashboard') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
                Dashboard
            </a>

            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Manajemen Data</p>
            </div>

            <div
                x-data="{ open: {{ request()->routeIs('panitia.kandidat.*') || request()->routeIs('panitia.calon_dpm.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="whitespace-nowrap">Manajemen Kandidat</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''"
                        class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2" class="mt-1 space-y-1 pl-11">
                    <a href="{{ route('panitia.kandidat.index') }}"
                        class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('panitia.kandidat.*') ? 'text-purple-700 bg-purple-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }} transition-colors">
                        Paslon Presma
                    </a>
                    <a href="{{ route('panitia.calon_dpm.index') }}"
                        class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('panitia.calon_dpm.*') ? 'text-purple-700 bg-purple-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }} transition-colors">
                        Calon DPM
                    </a>
                </div>
            </div>

            <a href="{{ route('panitia.parties.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.parties.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.parties.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 21v-8a2 2 0 012-2h14a2 2 0 012 2v8M3 21h18M3 21l2-8m14 8l-2-8m-2-3a3 3 0 10-6 0 3 3 0 006 0z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Manajemen Partai</span>
            </a>

            <a href="{{ route('panitia.dpt.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.dpt.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.dpt.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Manajemen DPT</span>
            </a>

            <a href="{{ route('panitia.analytics.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.analytics.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.analytics.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Realtime Analytics</span>
            </a>
            <a href="{{ route('panitia.timeline.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.timeline.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.timeline.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Timeline Kegiatan</span>
            </a>
            
            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alat Pemilu</p>
            </div>

            <a href="{{ route('panitia.scanner.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.scanner.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.scanner.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Scanner</span>
            </a>

            <a href="{{ route('panitia.letters.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.letters.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.letters.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12">
                    </path>
                </svg>
                <span class="whitespace-nowrap">Unduh Surat</span>
            </a>
            
            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Arsip & Laporan</p>
            </div>

            <a href="{{ route('panitia.reports.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.reports.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.reports.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="whitespace-nowrap">Pusat Laporan</span>
            </a>

            <a href="{{ route('panitia.assets.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.assets.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.assets.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <span class="whitespace-nowrap">Manajemen Asset</span>
            </a>

            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Konfigurasi</p>
            </div>

            <a href="{{ route('panitia.settings.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.settings.index') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.settings.index') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Pengaturan Sistem
            </a>
            <div x-data="{ open: {{ request()->routeIs('panitia.settings.letters.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="whitespace-nowrap">Pengaturan Surat</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''"
                        class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2" class="mt-1 space-y-1 pl-11">
                    <a href="{{ route('panitia.settings.letters.proof') }}"
                        class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('panitia.settings.letters.proof') ? 'text-purple-700 bg-purple-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }} transition-colors">
                        Surat Bukti Pilih
                    </a>
                    <a href="{{ route('panitia.settings.letters.notification') }}"
                        class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('panitia.settings.letters.notification') ? 'text-purple-700 bg-purple-50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }} transition-colors">
                        Surat Pemberitahuan
                    </a>
                </div>
            </div>

            <div class="px-3 mb-2 mt-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Monitoring</p>
            </div>

            <a href="{{ route('panitia.audit.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('panitia.audit.*') ? 'bg-purple-50 text-purple-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 group' }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('panitia.audit.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                Audit Logs
            </a>

            <a href="{{ route('panitia.reset.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-600 hover:bg-red-50 hover:text-red-600 transition-colors group">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-red-400 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Reset Election
            </a>
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
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-3 hover:bg-slate-50 rounded-full py-1 pr-2 pl-1 transition-colors focus:outline-none focus:ring-0">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover shadow-sm ring-2 ring-white">
                            @else
                                <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-white">
                                    {{ substr(auth()->user()->name ?? 'P', 0, 1) }}
                                </div>
                            @endif
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-slate-700 leading-none">
                                    {{ auth()->user()->name ?? 'Panitia' }}
                                </p>
                                <p class="text-[10px] text-slate-500 font-medium leading-none mt-1">Panitia Pemilihan
                                </p>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''"
                                class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white/90 backdrop-blur-xl border border-white/50 rounded-2xl shadow-lg ring-1 ring-slate-900/5 origin-top-right py-1 z-50 focus:outline-none"
                            style="display: none;">

                            <div class="px-4 py-3 border-b border-slate-100/50">
                                <p class="text-sm font-semibold text-slate-900 truncate">
                                    {{ auth()->user()->name ?? 'Panitia' }}
                                </p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email ??
                                    'panitia@kpum.com'
                                    }}</p>
                            </div>

                            <div class="p-1">
                                <a href="{{ route('panitia.profile.index') }}"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-xl transition-colors text-left mb-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Profil Saya
                                </a>
                                <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-colors text-left">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
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
        // Use window.Toast to prevent "Identifier has already been declared" error with Turbo
        window.Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Wrap session logic in IIFE to avoid global variable conflicts
        (function() {
            var sessionSuccess = <?php echo json_encode(session('success')); ?>;
            var sessionError = <?php echo json_encode(session('error')); ?>;

            if (sessionSuccess) {
                window.Toast.fire({
                    icon: 'success',
                    title: sessionSuccess
                });
            }

            if (sessionError) {
                window.Toast.fire({
                    icon: 'error',
                    title: sessionError
                });
            }
        })();

        // Global Form Confirm Interceptor
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const message = form.getAttribute('data-confirm');
            
            if (message) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#9333ea', // Purple for Panitia
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    backdrop: `rgba(0,0,0,0.4)`,
                    customClass: {
                        popup: 'rounded-2xl shadow-xl border border-slate-100',
                        title: 'text-lg font-bold text-slate-800',
                        htmlContainer: 'text-sm text-slate-500',
                        confirmButton: 'px-4 py-2 rounded-xl text-sm font-bold shadow-lg shadow-purple-500/30',
                        cancelButton: 'px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-50 hover:text-red-600 border-none shadow-none text-slate-500 bg-transparent'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.removeAttribute('data-confirm'); // Prevent infinite loop
                        // Fix for forms that might get detached from DOM by AJAX auto-refreshes
                        if (!document.body.contains(form)) {
                            form.style.display = 'none';
                            document.body.appendChild(form);
                        }
                        form.submit();
                    }
                });
            }
        });
    </script>
</body>

</html>