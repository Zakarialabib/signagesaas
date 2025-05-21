<header
    {{ $attributes->merge(['class' => 'w-full backdrop-blur-sm bg-background/90 border-b border-gray-200/50 dark:border-gray-800/50 sticky top-0 z-50 transition-all duration-300']) }}>
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <!-- Logo/Brand -->
        <a href="{{ url('/') }}" class="relative group flex items-center">
            <div
                class="absolute -inset-2 rounded-2xl bg-linear-to-r from-purple-500/20 to-pink-500/20 dark:from-purple-500/10 dark:to-pink-500/10 opacity-0 group-hover:opacity-100 transition-all duration-300 blur-xl">
            </div>
            <span
                class="relative text-2xl font-display font-bold bg-linear-to-r from-purple-500 to-pink-500 bg-clip-text text-transparent">
                {{ config('app.name', 'SignageSaaS') }}
            </span>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center space-x-1">
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 rounded-xl text-gray-700 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200">
                Dashboard
            </a>

            <!-- Devices Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                    class="px-4 py-2 rounded-xl text-gray-700 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200 flex items-center">
                    Devices
                    <svg class="w-4 h-4 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute z-10 mt-2 w-56 rounded-2xl bg-white dark:bg-gray-900 shadow-soft-xl dark:shadow-soft-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden">
                    <div class="py-1">
                        <a href="{{ route('devices.index') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Device List</span>
                            </div>
                        </a>
                        <a href="{{ route('devices.integration') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                                </svg>
                                <span>Device Integration</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Management Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                    class="px-4 py-2 rounded-xl text-gray-700 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200 flex items-center">
                    Content
                    <svg class="w-4 h-4 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute z-10 mt-2 w-56 rounded-2xl bg-white dark:bg-gray-900 shadow-soft-xl dark:shadow-soft-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden">
                    <div class="py-1">
                        <a href="{{ route('screens.index') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Screens</span>
                            </div>
                        </a>
                        <a href="{{ route('content.index') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span>Content Library</span>
                            </div>
                        </a>
                        <a href="{{ route('content.template.index') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            <span>Templates</span>
                        </a>
                        <a href="{{ route('content.templates.gallery') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            <span>Templates Gallery</span>
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ route('dashboard.analytics') }}"
                class="px-4 py-2 rounded-xl text-gray-700 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200">
                Analytics
            </a>
        </nav>

        <div class="flex items-center space-x-3">
            <!-- Theme Toggle & Language Switcher -->
            <div class="hidden md:flex items-center space-x-2">
                <x-theme-toggle
                    class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400" />
                <x-language-switcher
                    class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400" />
            </div>

            <!-- Notification Bell -->
            <button
                class="relative p-2 rounded-xl text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200">
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-pink-500 rounded-full"></span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>

            <!-- User profile dropdown -->
            <div class="relative hidden md:block" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-linear-to-br from-purple-500 to-pink-500 text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-900 transition-all duration-200 hover:shadow-glow-purple"
                    aria-label="User menu" aria-expanded="false" :aria-expanded="open.toString()">
                    <span class="text-sm font-medium">{{ Auth::user()->initials ?? 'U' }}</span>
                </button>

                <!-- Dropdown menu -->
                <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute right-0 z-10 mt-2 w-64 origin-top-right rounded-2xl bg-white dark:bg-gray-900 shadow-soft-xl dark:shadow-soft-2xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden"
                    role="menu" aria-orientation="vertical">

                    <!-- User info -->
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ Auth::user()->name ?? 'User Name' }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ Auth::user()->email ?? 'user@example.com' }}</div>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('settings.profile') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors"
                            role="menuitem">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Your Profile</span>
                            </div>
                        </a>
                        <a href="{{ route('settings.subscription') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors"
                            role="menuitem">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span>Subscription</span>
                            </div>
                        </a>
                        <a href="{{ route('settings.users') }}"
                            class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors"
                            role="menuitem">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>User Management</span>
                            </div>
                        </a>
                        <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
                        <form method="POST" action="#">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition-colors"
                                role="menuitem">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Sign out</span>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <button
                class="lg:hidden inline-flex items-center justify-center p-2 rounded-xl text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200 focus:outline-none"
                x-data @click="$dispatch('toggle-mobile-nav')" aria-label="Main menu">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile navigation menu -->
    <div x-data="{ mobileMenuOpen: false }" @toggle-mobile-nav.window="mobileMenuOpen = !mobileMenuOpen" x-show="mobileMenuOpen"
        x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden bg-white dark:bg-background border-t border-gray-200 dark:border-gray-800">
        <div class="px-4 py-3 space-y-1.5">
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-3 py-2.5 rounded-xl text-base font-medium text-gray-700 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200">
                <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <!-- Mobile Devices Section -->
            <div class="px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900">
                <div class="text-base font-medium text-gray-700 dark:text-gray-200 mb-2">Devices</div>
                <div class="space-y-1.5">
                    <a href="{{ route('devices.index') }}"
                        class="flex items-center pl-3 pr-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Device List
                    </a>
                    <a href="{{ route('devices.integration') }}"
                        class="flex items-center pl-3 pr-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                        </svg>
                        Device Integration
                    </a>
                </div>
            </div>

            <!-- Mobile Content Section -->
            <div class="px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900">
                <div class="text-base font-medium text-gray-700 dark:text-gray-200 mb-2">Content</div>
                <div class="space-y-1.5">
                    <a href="{{ route('screens.index') }}"
                        class="flex items-center pl-3 pr-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Screens
                    </a>
                    <a href="{{ route('content.index') }}"
                        class="flex items-center pl-3 pr-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Content Library
                    </a>
                    {{-- templates --}}
                    <a href="{{ route('content.template.index') }}"
                        class="flex items-center pl-3 pr-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Templates
                    </a>
                    <a href="{{ route('content.templates.gallery') }}"
                        class="flex items-center pl-3 pr-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Templates Gallery
                    </a>
                </div>
            </div>

            <a href="{{ route('dashboard.analytics') }}"
                class="flex items-center px-3 py-2.5 rounded-xl text-base font-medium text-gray-700 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200">
                <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Analytics
            </a>

            <div class="border-t border-gray-200 dark:border-gray-800 my-2"></div>

            <!-- Mobile Settings Section -->
            <div class="px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900">
                <div class="text-base font-medium text-gray-700 dark:text-gray-200 mb-2">Settings</div>
                <div class="space-y-1.5">
                    <a href="{{ route('settings.profile') }}"
                        class="flex items-center pl-3 pr-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Your Profile
                    </a>
                    <a href="{{ route('settings.subscription') }}"
                        class="flex items-center pl-3 pr-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Subscription
                    </a>
                    <a href="{{ route('settings.users') }}"
                        class="flex items-center pl-3 pr-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        User Management
                    </a>
                </div>
            </div>

            <!-- Mobile Theme & Language -->
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-900">
                <div class="text-sm font-medium text-gray-700 dark:text-gray-200">Appearance</div>
                <div class="flex items-center space-x-3">
                    <x-theme-toggle
                        class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400" />
                    <x-language-switcher
                        class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400" />
                </div>
            </div>

            <!-- Sign Out Button -->
            <form method="POST" action="#" class="mt-2">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-3 py-2.5 rounded-xl text-base font-medium text-gray-700 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200">
                    <svg class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
    </div>

    {{ $slot }}
</header>
