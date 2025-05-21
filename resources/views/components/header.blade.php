<header class="relative z-50">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
        <div class="flex lg:flex-1">
            <a href="#" class="-m-1.5 p-1.5">
                <span class="sr-only">{{ config('app.name') }}</span>
                <!-- Replace with your logo -->
                <span class="text-2xl font-bold text-indigo-600">SignageSaaS</span>
            </a>
        </div>
        <div class="flex lg:hidden">
            <button type="button"
                class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700 dark:text-gray-200"
                x-data @click="$dispatch('toggle-navigation')">
                <span class="sr-only">Open main menu</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
            <a href="#features"
                class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">{{ __('features') }}</a>
            <a href="#pricing"
                class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">{{ __('pricing') }}</a>
            <a href="#about"
                class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">{{ __('about') }}</a>
            <a href="#contact"
                class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">{{ __('contact') }}</a>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:gap-x-4">
            <x-language-switcher />
            @auth
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                    {{ __('dashboard') }} <span aria-hidden="true">&rarr;</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                    {{ __('login') }}
                </a>
                <a href="{{ route('register') }}"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    {{ __('Start trial') }}
                </a>
            @endauth
        </div>
    </nav>
</header>