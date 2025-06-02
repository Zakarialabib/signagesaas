<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" :class="{ 'dark': isDarkMode }"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Digital Signage Display">
    <title>{{ $title ?? 'Digital Signage' }}</title>
    @vite('resources/css/app.css')
    @stack('styles')
    @livewireStyles

    <script>
        // Dark mode handling
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        /* Optional: For a nicer scrollbar on widgets that overflow */
        .tv-widget-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .tv-widget-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .tv-widget-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(100, 116, 139, 0.7);
            /* slate-500 with opacity */
            border-radius: 3px;
        }

        .tv-widget-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(71, 85, 105, 0.9);
            /* slate-600 with opacity */
        }
    </style>

    @vite('resources/js/app.js')
    @livewireScriptConfig
    @stack('scripts')
</head>

<body class="h-full bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    {{ $slot }}
</body>

</html>
