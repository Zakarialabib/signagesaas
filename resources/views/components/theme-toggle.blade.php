<button @click="toggleTheme()"
    class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-white/60 dark:bg-gray-800/60 backdrop-blur-md shadow-lg shadow-indigo-200/30 dark:shadow-black/40 border border-white/30 dark:border-gray-700/40 hover:bg-white/80 dark:hover:bg-gray-700/80 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-400/60 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900"
    :aria-label="isDarkMode ? 'Switch to light mode' : 'Switch to dark mode'"
    :title="isDarkMode ? 'Switch to light mode' : 'Switch to dark mode'" type="button" role="switch"
    :aria-checked="isDarkMode ? 'true' : 'false'">
    <!-- Sun icon for light mode -->
    <svg x-cloak x-show="isDarkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 drop-shadow-glow transition-transform duration-300 scale-110" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <!-- Moon icon for dark mode -->
    <svg x-cloak x-show="!isDarkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400 dark:text-gray-200 transition-transform duration-300 scale-110" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
</button>
