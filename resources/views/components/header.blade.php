<header class="sticky top-0 z-50 bg-white dark:bg-gray-800 shadow-md" x-data="{ mobileMenuOpen: false, featuresOpen: false, useCaseOpen: false }" x-clock>
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
        <div class="flex lg:flex-1">
            <a href="/" class="-m-1.5 p-1.5">
                <span class="sr-only">{{ config('app.name') }}</span>
                <img class="h-8 w-auto" src="https://cdn.prod.website-files.com/65cc1c6eb8ec74c43d45cbad/65d4431bcdeb99e64340f27a_%E1%84%89%E1%85%A3%E1%84%91%E1%85%B3%E1%86%AF%20%E1%84%80%E1%85%A1%E1%84%8B%E1%85%A9%E1%84%92%E1%85%A7%E1%86%BC%20%233299fe.svg" alt="{{ config('app.name') }}">
            </a>
        </div>
        <div class="flex lg:hidden">
            <button type="button"
                class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700 dark:text-gray-200"
                @click="mobileMenuOpen = !mobileMenuOpen">
                <span class="sr-only">Open main menu</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
            {{-- Features Dropdown --}}
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative" x-clock>
                <button @click="open = !open" type="button" class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 focus:outline-none transition-colors duration-150" aria-expanded="open">
                    {{ __('Features') }}
                    <svg class="h-5 w-5 flex-none text-gray-400 group-hover:text-purple-500 transition-colors duration-150" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     @click.away="open = false"
                     class="absolute -left-8 top-full z-10 mt-3 w-screen max-w-md overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-gray-900/5 dark:bg-gray-800 dark:ring-gray-700">
                    <div class="p-4">
                        <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm leading-6 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <div class="flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-purple-100 dark:bg-gray-700 dark:group-hover:bg-purple-700/30 transition-colors duration-150">
                                <svg class="h-6 w-6 text-gray-600 group-hover:text-purple-600 dark:text-gray-400 dark:group-hover:text-purple-400 transition-colors duration-150" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12m-8.25 0V18M12 18.75V18m0 0H18M18 18v-1.25A2.25 2.25 0 0015.75 15H8.25A2.25 2.25 0 006 17.25V18m6-12V5.25A2.25 2.25 0 008.25 3H6a2.25 2.25 0 00-2.25 2.25v1.5M12 6V5.25A2.25 2.25 0 0114.25 3H16.5a2.25 2.25 0 012.25 2.25v1.5" /></svg>
                            </div>
                            <div class="flex-auto">
                                <a href="{{ url('/#widget-featured-menu-board') }}" @click.prevent="document.getElementById('widget-featured-menu-board').scrollIntoView({ behavior: 'smooth' }); open = false" class="block font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-150">
                                    {{ __('Digital Menu Board') }}
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ __('Dynamic and engaging menu displays.') }}</p>
                            </div>
                        </div>
                        <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm leading-6 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150">
                             <div class="flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-purple-100 dark:bg-gray-700 dark:group-hover:bg-purple-700/30 transition-colors duration-150">
                                <svg class="h-6 w-6 text-gray-600 group-hover:text-purple-600 dark:text-gray-400 dark:group-hover:text-purple-400 transition-colors duration-150" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            </div>
                            <div class="flex-auto">
                                <a href="{{ url('/#widget-retail-products-card') }}" @click.prevent="document.getElementById('widget-retail-products-card').scrollIntoView({ behavior: 'smooth' }); open = false" class="block font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-150">
                                    {{ __('Retail Product Showcase') }}
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ __('Highlight products and promotions.') }}</p>
                            </div>
                        </div>
                        <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm leading-6 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150">
                           <div class="flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-purple-100 dark:bg-gray-700 dark:group-hover:bg-purple-700/30 transition-colors duration-150">
                                <svg class="h-6 w-6 text-gray-600 group-hover:text-purple-600 dark:text-gray-400 dark:group-hover:text-purple-400 transition-colors duration-150" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="flex-auto">
                                <a href="{{ url('/#widget-calendar-events-card') }}" @click.prevent="document.getElementById('widget-calendar-events-card').scrollIntoView({ behavior: 'smooth' }); open = false" class="block font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-150">
                                    {{ __('Calendar & Events Widget') }}
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ __('Display schedules and upcoming events.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Use Case Dropdown --}}
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative" x-clock>
                <button @click="open = !open" type="button" class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 focus:outline-none transition-colors duration-150" aria-expanded="open">
                    {{ __('Use Case') }}
                    <svg class="h-5 w-5 flex-none text-gray-400 group-hover:text-purple-500 transition-colors duration-150" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     @click.away="open = false"
                     class="absolute -left-8 top-full z-10 mt-3 w-screen max-w-md overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-gray-900/5 dark:bg-gray-800 dark:ring-gray-700">
                    <div class="p-4">
                        <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm leading-6 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <div class="flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-purple-100 dark:bg-gray-700 dark:group-hover:bg-purple-700/30 transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 group-hover:text-purple-600 dark:text-gray-400 dark:group-hover:text-purple-400 transition-colors duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="flex-auto">
                                <a href="{{ url('/#solution-restaurants-cafes') }}" @click.prevent="document.getElementById('solution-restaurants-cafes').scrollIntoView({ behavior: 'smooth' }); open = false" class="block font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-150">
                                    {{ __('Restaurants & Cafés') }}
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ __('Digital menus and promotions.') }}</p>
                            </div>
                        </div>
                        <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm leading-6 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150">
                             <div class="flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-purple-100 dark:bg-gray-700 dark:group-hover:bg-purple-700/30 transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 group-hover:text-purple-600 dark:text-gray-400 dark:group-hover:text-purple-400 transition-colors duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            </div>
                            <div class="flex-auto">
                                <a href="{{ url('/#solution-retail-stores') }}" @click.prevent="document.getElementById('solution-retail-stores').scrollIntoView({ behavior: 'smooth' }); open = false" class="block font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-150">
                                    {{ __('Retail Stores') }}
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ __('Interactive product displays.') }}</p>
                            </div>
                        </div>
                        <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm leading-6 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <div class="flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-purple-100 dark:bg-gray-700 dark:group-hover:bg-purple-700/30 transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 group-hover:text-purple-600 dark:text-gray-400 dark:group-hover:text-purple-400 transition-colors duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            <div class="flex-auto">
                                <a href="{{ url('/#solution-corporate-offices') }}" @click.prevent="document.getElementById('solution-corporate-offices').scrollIntoView({ behavior: 'smooth' }); open = false" class="block font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-150">
                                    {{ __('Corporate Offices') }}
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ __('Internal communications and meetings.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <a href="#pricing" @click.prevent="document.getElementById('pricing').scrollIntoView({ behavior: 'smooth' })" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-150">{{ __('Pricing') }}</a>

            <a href="#contact" @click.prevent="document.getElementById('contact').scrollIntoView({ behavior: 'smooth' })" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-150">{{ __('Contact us') }}</a>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:items-center lg:gap-x-4">
            <x-theme-toggle
            class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400" />

            <x-language-switcher />

            @guest
                <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                    {{ __('Login') }}
                </a>
                {{-- if we are in tenant no need to show that --}}

                <a href="{{ route('register') }}"
                    class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    {{ __('Free Trial') }}
                </a>
                {{-- @endif --}}
                
            @else
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                    {{ __('Dashboard') }} <span aria-hidden="true">&rarr;</span>
                </a>
            @endguest
        </div>
    </nav>
    <!-- Mobile menu -->
    <div x-show="mobileMenuOpen" class="lg:hidden" x-cloak
        x-transition:enter="duration-200 ease-out"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="duration-100 ease-in"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">
        <div class="fixed inset-0 z-50"></div>
        <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white dark:bg-gray-900 px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10 dark:sm:ring-gray-700">
            <div class="flex items-center justify-between">
                <a href="#" class="-m-1.5 p-1.5">
                    <span class="sr-only">{{ config('app.name') }}</span>
                    <img class="h-8 w-auto" src="https://cdn.prod.website-files.com/65cc1c6eb8ec74c43d45cbad/65d4431bcdeb99e64340f27a_%E1%84%89%E1%85%A3%E1%84%91%E1%85%B3%E1%86%AF%20%E1%84%80%E1%85%A1%E1%84%8B%E1%85%A9%E1%84%92%E1%85%A7%E1%86%BC%20%233299fe.svg" alt="{{ config('app.name') }}">
                </a>
                <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700 dark:text-gray-200" @click="mobileMenuOpen = false">
                    <span class="sr-only">Close menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-6 flow-root">
                <div class="-my-6 divide-y divide-gray-500/10 dark:divide-gray-700">
                    <div class="space-y-2 py-6">
                        {{-- Mobile Menu Links --}}
                        <div x-data="{ open: false }" x-clock>
                            <button @click="open = !open" class="-mx-3 flex w-full items-center justify-between rounded-lg py-2 px-3 text-base font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">
                                {{ __('Features') }}
                                <svg class="h-5 w-5 flex-none" :class="{'rotate-180': open}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-2 space-y-2">
                                <a href="{{ url('/#widget-featured-menu-board') }}" @click.prevent="document.getElementById('widget-featured-menu-board').scrollIntoView({ behavior: 'smooth' }); mobileMenuOpen = false; open = false" class="block rounded-lg py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Digital Menu Board') }}</a>
                                <a href="{{ url('/#widget-retail-products-card') }}" @click.prevent="document.getElementById('widget-retail-products-card').scrollIntoView({ behavior: 'smooth' }); mobileMenuOpen = false; open = false" class="block rounded-lg py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Retail Product Showcase') }}</a>
                                <a href="{{ url('/#widget-calendar-events-card') }}" @click.prevent="document.getElementById('widget-calendar-events-card').scrollIntoView({ behavior: 'smooth' }); mobileMenuOpen = false; open = false" class="block rounded-lg py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Calendar & Events Widget') }}</a>
                            </div>
                        </div>
                        <div x-data="{ open: false }" x-clock>
                            <button @click="open = !open" class="-mx-3 flex w-full items-center justify-between rounded-lg py-2 px-3 text-base font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">
                                {{ __('Use Case') }}
                                <svg class="h-5 w-5 flex-none" :class="{'rotate-180': open}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-2 space-y-2">
                                <a href="{{ url('/#solution-restaurants-cafes') }}" @click.prevent="document.getElementById('solution-restaurants-cafes').scrollIntoView({ behavior: 'smooth' }); mobileMenuOpen = false; open = false" class="block rounded-lg py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Restaurants & Cafés') }}</a>
                                <a href="{{ url('/#solution-retail-stores') }}" @click.prevent="document.getElementById('solution-retail-stores').scrollIntoView({ behavior: 'smooth' }); mobileMenuOpen = false; open = false" class="block rounded-lg py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Retail Stores') }}</a>
                                <a href="{{ url('/#solution-corporate-offices') }}" @click.prevent="document.getElementById('solution-corporate-offices').scrollIntoView({ behavior: 'smooth' }); mobileMenuOpen = false; open = false" class="block rounded-lg py-2 pl-6 pr-3 text-sm font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Corporate Offices') }}</a>
                            </div>
                        </div>

                        <a href="#pricing" @click.prevent="document.getElementById('pricing').scrollIntoView({ behavior: 'smooth' }); mobileMenuOpen = false" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Pricing') }}</a>
                        
                        <a href="#contact" @click.prevent="document.getElementById('contact').scrollIntoView({ behavior: 'smooth' }); mobileMenuOpen = false" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Contact us') }}</a>
                    </div>
                    <div class="py-6">
                        <div class="mb-4">
                            <x-theme-toggle
                            class="text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400" />
                            
                            <x-language-switcher />
                        </div>
                        @guest
                            <a href="{{ route('login') }}" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Login') }}</a>
                            <a href="{{ route('register') }}" class="mt-2 -mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-white bg-indigo-600 hover:bg-indigo-500">{{ __('Free Trial') }}</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('Dashboard') }}</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
