<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'KPUM UNUGHA') }} | E-Voting Universitas Nahdlatul Ulama Al Ghazali</title>
    <meta name="description" content="Sistem Informasi e-Voting KPUM UNUGHA Cilacap. Platform pemilihan mahasiswa yang transparan, aman, dan berintegritas untuk Universitas Nahdlatul Ulama Al Ghazali Cilacap.">
    <meta name="keywords" content="KPUM UNUGHA, UNUGHA Cilacap, E-Voting UNUGHA, Pemilihan Mahasiswa, Universitas Nahdlatul Ulama Al Ghazali, KPUM, Pemilu Raya UNUGHA">
    <meta name="author" content="KPUM UNUGHA">
    <meta name="robots" content="index, follow">

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

<body class="font-sans antialiased text-foreground bg-background min-h-screen flex flex-col">
    {{ $slot }}

    {{-- @fluxScripts --}}
</body>

</html>