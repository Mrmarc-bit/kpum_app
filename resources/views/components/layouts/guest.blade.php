<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'KPUM UNUGHA') }} | E-Voting & Pemilwa UNUGHA 2026 Cilacap</title>
    <meta name="description" content="Situs Resmi KPUM UNUGHA 2026 - Platform E-Voting Pemilihan Raya (Pemira) / Pemilwa Universitas Nahdlatul Ulama Al Ghazali Cilacap. Transparan, aman, dan terpercaya.">
    <meta name="keywords" content="kpum, e-voting, e vitubg, pemilu, kpum unugha, unugha, pemilihan raya, pemilwa, pemira unugha 2026, kpum cilacap, pemira unugha, universitas nahdlatul ulama al ghazali cilacap">
    <meta name="author" content="KPUM UNUGHA">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="{{ config('app.url') }}{{ Request::is('/') ? '' : '/' . Request::path() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'KPUM UNUGHA' }} - E-Voting Platform">
    <meta property="og:description" content="Berikan suara Anda untuk masa depan kampus UNUGHA yang lebih baik melalui platform e-voting resmi KPUM UNUGHA.">
    @if(setting('app_logo'))
        <meta property="og:image" content="{{ asset(setting('app_logo')) }}">
    @endif

    @if(setting('app_logo'))
        <link rel="icon" href="{{ asset(setting('app_logo')) }}" sizes="32x32">
        <link rel="icon" href="{{ asset(setting('app_logo')) }}" sizes="192x192">
        <link rel="apple-touch-icon" href="{{ asset(setting('app_logo')) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

<body class="font-sans antialiased text-foreground bg-background min-h-screen flex flex-col overflow-x-hidden">
    {{ $slot }}

    {{-- @fluxScripts --}}
</body>

</html>
