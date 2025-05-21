/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/Livewire/**/*.php', // For Livewire applications
        './app/View/Components/**/*.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                transparent: 'transparent',
                current: 'currentColor',

                // Your Core Palettes - Use these as your primary colors
                purple: {
                    50: '#faf5ff',
                    100: '#f3e8ff',
                    200: '#e9d5ff',
                    300: '#d8b4fe',
                    400: '#c084fc',
                    500: '#a855f7', // Recommended as your primary default
                    600: '#9333ea',
                    700: '#7e22ce',
                    800: '#6b21a8',
                    900: '#581c87',
                    950: '#3b0764',
                },
                pink: {
                    50: '#fdf2f8',
                    100: '#fce7f3',
                    200: '#fbcfe8',
                    300: '#f9a8d4',
                    400: '#f472b6',
                    500: '#ec4899', // Recommended as your accent default
                    600: '#db2777',
                    700: '#be185d',
                    800: '#9d174d',
                    900: '#831843',
                    950: '#500724',
                },

                // Curated Gray/Neutral Palette (Consider renaming 'gray' to 'neutral' or 'slate'
                // if you want to differentiate it from default Tailwind grays,
                // but replacing 'gray' here is common for full customization)
                gray: {
                    50: '#f9fafb', // Lightest gray
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    750: '#2a2a3a', // Your custom shade
                    800: '#1f2937',
                    850: '#151525', // Your custom shade
                    900: '#111827',
                    950: '#0a0a12', // Your custom shade / Deepest background
                },

                // Semantic/Status Colors (Optional but good practice)
                // Map these to shades from your core palettes or define separately
                success: {
                    50: '#f0fdf4', // Example: based on green
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                danger: {
                    50: '#fef2f2', // Example: based on red
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                    950: '#450a0a',
                },
                warning: {
                    50: '#fffbeb', // Example: based on yellow
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                    950: '#451a03',
                },
                info: {
                    50: '#eff6ff', // Example: based on blue
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                    950: '#172554',
                },

                // Semantic role colors (Good for backgrounds, text, borders)
                // These reference your curated palette shades for consistency
                background: {
                    DEFAULT: 'var(--color-background-default)', // Use CSS variables for flexibility
                    secondary: 'var(--color-background-secondary)',
                    // Add more specific backgrounds if needed: card, input, modal, etc.
                    // card: 'var(--color-background-card)',
                },
                foreground: {
                    // Use 'foreground' instead of 'text' to avoid conflict with text utilities
                    DEFAULT: 'var(--color-foreground-default)', // Main text color
                    secondary: 'var(--color-foreground-secondary)', // Secondary text color
                    // Add tertiary, muted, etc.
                },
                border: {
                    DEFAULT: 'var(--color-border-default)', // Default border color
                    // Add subtle, divider, etc.
                },
                ring: {
                    DEFAULT: 'var(--color-ring-default)', // Default ring color (e.g., for focus states)
                },
                // Add other roles: accent, primary, destructive, etc. mapped to your palettes
                primary: {
                    // Example mapping primary roles to your purple palette
                    DEFAULT: 'var(--color-primary-DEFAULT)', // Typically purple.500 or 600
                    foreground: 'var(--color-primary-foreground)', // Text on primary background (e.g., white or gray.50)
                },
                accent: {
                    // Example mapping accent roles to your pink palette
                    DEFAULT: 'var(--color-accent-DEFAULT)', // Typically pink.500
                    foreground: 'var(--color-accent-foreground)', // Text on accent background
                },
            },

            // Add more spacing values if needed
            spacing: {
                128: '32rem',
                144: '36rem',
                // Add custom spacing like '0.5': '0.125rem' if needed below default increments
            },

            // Add custom z-index values for modals, navs, tooltips etc.
            zIndex: {
                60: '60',
                70: '70',
                80: '80',
                90: '90',
                100: '100',
                modal: '1000', // Example semantic names
                dropdown: '1010',
                sticky: '1020',
                fixed: '1030',
                overlay: '1040',
            },

            fontFamily: {
                // Ensure fallbacks are good. Consider adding more specific fallbacks.
                // If using variable fonts, ensure the font file is loaded correctly.
                sans: [
                    'Inter var',
                    { fontFeatureSettings: '"cv11", "salt"' },
                    'system-ui',
                    'sans-serif',
                ],
                display: ['Lexend', 'system-ui', 'sans-serif'],
                // Add other font families: serif, mono etc.
            },

            // Your custom shadows, good additions
            boxShadow: {
                'soft-xl':
                    '0 10px 25px -3px rgba(0, 0, 0, 0.12), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'soft-2xl': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
                'inner-light': 'inset 0 1px 2px 0 rgba(255, 255, 255, 0.05)',
                'glow-purple': '0 0 15px rgba(168, 85, 247, 0.35)',
                'glow-pink': '0 0 15px rgba(236, 72, 153, 0.35)',
                'inner-glow': 'inset 0 2px 15px 0 rgba(168, 85, 247, 0.15)',
            },

            // Only add *new* border radii here. Tailwind already includes xl, 2xl, 3xl.
            borderRadius: {
                // 'xl': '0.75rem', // Redundant, already default
                // '2xl': '1rem', // Redundant, already default
                // '3xl': '1.5rem', // Redundant, already default
                '4xl': '2rem', // Keep your custom addition
            },

            backdropBlur: {
                xs: '2px', // Keep your custom addition
                // Add sm, md, lg etc. if needed beyond default steps
            },

            // Your custom animations, good additions
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                float: 'float 3s ease-in-out infinite',
            },

            dropShadow: {
                glow: '0 0 8px rgba(168, 85, 247, 0.35)',
                'glow-success': '0 0 8px rgba(34, 197, 94, 0.35)',
                'glow-info': '0 0 8px rgba(59, 130, 246, 0.35)',
                'glow-warning': '0 0 8px rgba(245, 158, 11, 0.35)',
            },

            // Your custom keyframes, good additions
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            },

            // Extend default transition properties if needed
            transitionTimingFunction: {
                'ease-in-out-back': 'cubic-bezier(0.68, -0.55, 0.27, 1.55)',
            },
            transitionDuration: {
                400: '400ms',
                600: '600ms',
                700: '700ms',
                800: '800ms',
                1000: '1000ms', // Redundant, already default
            },
            transitionDelay: {
                400: '400ms',
                600: '600ms',
            },

            // Typography plugin configuration
            // Set default styling for the prose class
            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        // Use CSS variables for better dark mode integration
                        '--tw-prose-body': theme('colors.foreground.DEFAULT'), // Text color
                        '--tw-prose-headings': theme('colors.white'), // Headings color (or theme('colors.foreground.DEFAULT'))
                        '--tw-prose-lead': theme('colors.foreground.secondary'), // Lead paragraph color
                        '--tw-prose-links': theme('colors.purple.400'), // Link color
                        '--tw-prose-bold': theme('colors.foreground.DEFAULT'), // Bold text color
                        '--tw-prose-counters': theme('colors.gray.400'), // List counter color
                        '--tw-prose-bullets': theme('colors.gray.600'), // List bullet color
                        '--tw-prose-hr': theme('colors.gray.700'), // Horizontal rule color
                        '--tw-prose-quotes': theme('colors.gray.200'), // Blockquote text color
                        '--tw-prose-quote-borders': theme('colors.purple.500'), // Blockquote border color
                        '--tw-prose-captions': theme('colors.gray.400'), // Caption text color
                        '--tw-prose-code': theme('colors.white'), // Inline code color
                        '--tw-prose-pre-code': theme('colors.gray.200'), // Code block text color
                        '--tw-prose-pre-bg': theme('colors.gray.800'), // Code block background
                        '--tw-prose-th-borders': theme('colors.gray.600'), // Table header border color
                        '--tw-prose-td-borders': theme('colors.gray.700'), // Table data border color

                        // Customize specific elements if needed (optional, variables cover most)
                        // a: {
                        //     textDecoration: 'underline',
                        //     '&:hover': {
                        //         color: theme('colors.purple.300'), // Darker on hover
                        //     },
                        // },
                        code: {
                            // Style for inline code
                            backgroundColor: theme('colors.gray.800'),
                            padding: '0.2em 0.4em',
                            borderRadius: '0.25rem',
                        },
                    },
                },
                // Add a 'dark' variant for typography if you need different styles
                // beyond what the CSS variables handle via root styles.
                // E.g., different link hover color in dark mode if not using variables.
                // dark: {
                //     css: {
                //         '--tw-prose-links': theme('colors.purple.300'), // Lighter in dark mode
                //         '&:hover': {
                //              color: theme('colors.purple.200'),
                //         },
                //     }
                // }
            }),

            // Only add *new* background images here. radial/conic are defaults.
            backgroundImage: {
                // 'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))', // Redundant
                // 'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))', // Redundant
                'gradient-purple-pink':
                    'linear-gradient(to right, var(--tw-gradient-stops))', // Keep your custom
                // Add other custom gradients like 'gradient-to-t', 'gradient-to-r' etc.
                // 'gradient-to-right': 'linear-gradient(to right, var(--tw-gradient-stops))', // Also default, example
            },
        },
    },

    // Add useful plugins here
    plugins: [
        // Official plugins
        require('@tailwindcss/forms')({
            strategy: 'class', // Use class strategy for easier customization
        }),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        // require('@tailwindcss/container-queries'), // Useful for container-based styles

        // Community plugins (Optional, add as needed)
        // require('tailwindcss-animate'), // Provides utilities for animated transitions
        // require('tailwindcss-radix')({ // For styling components built with Radix UI
        //     variant: 'all' // or ['state', 'size', ...]
        // }),
        // require('tailwindcss-safe-area') // For handling notches and safe areas on mobile
        // require('tailwindcss-logical-properties') // For LTR/RTL support
    ],

    // Optionally, safelist classes that might be dynamically generated and
    // not detected by the content scan. Use sparingly as it increases CSS size.
    safelist: [
        {
            pattern:
                /^(bg|text|border|ring)-(gray|info|background|primary|accent|success|danger|warning)-(50|100|200|300|400|500|600|700|750|800|850|900|950)?$/,
        },
        {
            pattern:
                /^(bg|text|border|ring)-(background|foreground|primary|accent|success|danger|warning|info)(|-(DEFAULT|foreground))$/,
        },
    ],
}
