import './bootstrap'
import '../css/app.css'
import {
    Livewire,
    Alpine,
} from '../../vendor/livewire/livewire/dist/livewire.esm'
import './zones'

// Theme management utility functions
const ThemeManager = {
    THEME_KEY: 'theme',
    DARK_VALUE: 'dark',
    LIGHT_VALUE: 'light',

    // Get the current theme from localStorage or system preference
    getCurrentTheme() {
        const savedTheme = window.localStorage.getItem(this.THEME_KEY)
        if (savedTheme) {
            return savedTheme
        }

        // Default to light mode instead of system preference
        return this.LIGHT_VALUE
    },

    // Check if current theme is dark
    isDarkMode() {
        return this.getCurrentTheme() === this.DARK_VALUE
    },

    // Save theme setting to localStorage
    saveTheme(isDark) {
        const theme = isDark ? this.DARK_VALUE : this.LIGHT_VALUE
        window.localStorage.setItem(this.THEME_KEY, theme)

        // Apply theme to document
        if (isDark) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    },

    // Toggle between light and dark mode
    toggleTheme() {
        this.saveTheme(!this.isDarkMode())
        return this.isDarkMode()
    },

    // Initialize theme on page load
    initialize() {
        // Apply saved theme on load
        if (this.isDarkMode()) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    },
}

// Initialize theme system
ThemeManager.initialize()

// Main Alpine state for theme, RTL, sidebar, and scroll
Alpine.data('theme', () => {
    let lastScrollTop = 0

    const getRtl = () => {
        if (window.localStorage.getItem('rtl')) {
            return JSON.parse(window.localStorage.getItem('rtl'))
        }
        return false
    }

    const enableRtl = (isRtl) => {
        document.body.dir = isRtl ? 'rtl' : 'ltr'
    }

    // Set initial direction
    enableRtl(getRtl())

    const init = function () {
        window.addEventListener('scroll', () => {
            let st = window.pageYOffset || document.documentElement.scrollTop
            if (st > lastScrollTop) {
                this.scrollingDown = true
                this.scrollingUp = false
            } else {
                this.scrollingDown = false
                this.scrollingUp = true
                if (st === 0) {
                    this.scrollingDown = false
                    this.scrollingUp = false
                }
            }
            lastScrollTop = st <= 0 ? 0 : st
        })

        window.addEventListener('resize', () => {
            this.handleWindowResize()
        })
    }

    return {
        init,
        isDarkMode: ThemeManager.isDarkMode(),
        toggleTheme() {
            this.isDarkMode = ThemeManager.toggleTheme()
        },
        isRtl: getRtl(),
        toggleRtl() {
            this.isRtl = !this.isRtl
            enableRtl(this.isRtl)
            window.localStorage.setItem('rtl', this.isRtl)
        },
        isSidebarOpen: window.innerWidth > 1024,
        isSidebarHovered: false,
        handleSidebarHover(value) {
            if (window.innerWidth < 1024) return
            this.isSidebarHovered = value
        },
        handleWindowResize() {
            this.isSidebarOpen = window.innerWidth > 1024
        },
        scrollingDown: false,
        scrollingUp: false,
    }
})

// Alpine loading mask for page load state
Alpine.data('loadingMask', () => ({
    pageLoaded: false,
    init() {
        window.onload = () => {
            this.pageLoaded = true
        }
    },
}))

Livewire.start()
