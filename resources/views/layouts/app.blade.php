<!DOCTYPE html>
<html x-data="theme" :class="{ 'dark': isDarkMode }" lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="nofollow">

    <title>{{ config('app.name', 'SignageSaaS') }}</title>

    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <meta name="theme-color" content="#4f46e5">
    <link rel="manifest" href="manifest.json" />
    <link rel="apple-touch-icon" href="/images/icon-192x192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    @vite('resources/css/app.css')
    @stack('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @livewireStyles
    @vite('resources/js/app.js')
    @livewireScriptConfig
    @stack('scripts')
</head>

<body
    class="min-h-screen flex flex-col font-sans antialiased text-gray-900 bg-gray-50 dark:text-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <x-impersonation-banner />
    <x-alert />
    <x-header-admin />

    <main class="flex-1 container mx-auto px-4 py-8">
        <!-- Onboarding Widget -->
        {{-- <livewire:dashboard.onboarding-widget /> --}}

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="w-full bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-4 mt-8">
        <div class="container mx-auto px-4 text-center text-sm text-gray-600 dark:text-gray-400">
            &copy; {{ date('Y') }} {{ config('app.name', 'SignageSaaS') }}. All rights reserved.
        </div>
    </footer>
</body>

</html>
