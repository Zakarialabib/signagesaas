<!DOCTYPE html>
<html x-data="theme" :class="{ 'dark': isDarkMode }" lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full bg-gray-50 dark:bg-gray-900">

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

<body class="h-full">
    {{ $slot }}
</body>

</html>
