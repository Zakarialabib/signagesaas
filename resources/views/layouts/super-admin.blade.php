<!DOCTYPE html>
<html x-data="theme" :class="{ 'dark': isDarkMode }" lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="nofollow">

    <title>{{ config('app.name', 'SignageSaaS') }} - SuperAdmin</title>

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
    class="h-full font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
    <div class="min-h-full flex">
        <aside
            class="hidden lg:flex lg:shrink-0 lg:flex-col w-64 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex flex-col grow overflow-y-auto pt-5 pb-4">
                <div class="flex items-center justify-center px-4 mb-6">
                    <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">SignageSaaS Admin</span>
                </div>
                <nav class="px-2 space-y-1">
                    <x-super-admin.nav-link :href="route('superadmin.tenants')" :active="request()->routeIs('superadmin.tenants*')" :icon="'<svg class=\'w-6 h-6 group-hover:scale-110 transition-transform text-gray-500 dark:text-gray-400\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z\'/></svg>'">Tenants</x-super-admin.nav-link>
                    <x-super-admin.nav-link :href="route('superadmin.plans')" :active="request()->routeIs('superadmin.plans*')" :icon="'<svg class=\'w-6 h-6 group-hover:scale-110 transition-transform text-gray-500 dark:text-gray-400\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z\'/></svg>'">Plans & Pricing</x-super-admin.nav-link>
                    <x-super-admin.nav-link :href="route('superadmin.audit-logs')" :active="request()->routeIs('superadmin.audit-logs*')" :icon="'<svg class=\'w-6 h-6 group-hover:scale-110 transition-transform text-gray-500 dark:text-gray-400\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z\'/></svg>'">Audit Logs</x-super-admin.nav-link>
                    <x-super-admin.nav-link :href="route('superadmin.settings')" :active="request()->routeIs('superadmin.settings*')" :icon="'<svg class=\'w-6 h-6 group-hover:scale-110 transition-transform text-gray-500 dark:text-gray-400\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'/></svg>'">Settings</x-super-admin.nav-link>
                </nav>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0 flex flex-col">
            <!-- Header -->
            <header class="sticky top-0 z-10 bg-white dark:bg-gray-800 shadow transition-colors duration-300">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6">
                    <button @click="open = true" class="lg:hidden text-gray-500 focus:outline-none mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="flex items-center ml-auto">
                        <x-theme-toggle />
                        <div class="ml-4 relative">
                            <button @click="profileOpen = !profileOpen" class="flex items-center focus:outline-none">
                                <div
                                    class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </button>
                            <div x-show="profileOpen" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-20"
                                style="display: none;">
                                <div class="py-1">
                                    @livewire('super-admin.auth.logout')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 md:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
