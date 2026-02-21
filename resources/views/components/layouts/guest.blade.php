<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'KPUM') }}</title>

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

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased text-foreground bg-background min-h-screen flex flex-col">
    {{ $slot }}

    {{-- @fluxScripts --}}
</body>

</html>