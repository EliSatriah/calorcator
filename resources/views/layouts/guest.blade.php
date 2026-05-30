<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Calorcator') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-x-hidden">
        
        <!-- Background Decoration (Same as Landing Page) -->
        <div class="fixed top-0 left-0 w-full h-[600px] bg-gradient-to-br from-white to-blue-50 -z-10" style="clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);"></div>
        <div class="fixed top-[-100px] right-[-100px] w-96 h-96 bg-brandOrange/10 rounded-full blur-3xl -z-10"></div>
        <div class="fixed top-[200px] left-[-100px] w-80 h-80 bg-brandBlue/5 rounded-full blur-3xl -z-10"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex flex-col items-center gap-2 mt-12 sm:mt-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Calorcator Logo" class="h-24 w-24 object-contain animate-float" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48Y2lyY2xlIGN4PSI1MCIgY3k9IjUwIiByPSI0MCIgZmlsbD0iIzAyM2U4YSIvPjwvc3ZnPg=='">
                    <span class="text-3xl font-extrabold text-brandBlue tracking-tight">Calorcator</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-white/90 backdrop-blur-md shadow-2xl border border-white overflow-hidden sm:rounded-3xl animate-fade-in-up mb-12 sm:mb-0">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
