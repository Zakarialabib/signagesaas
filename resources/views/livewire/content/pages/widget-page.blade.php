<div x-data="{
    isFullscreen: false,
    activeTab: '{{ $category->value }}',
    toggleFullscreen() {
        let previewElement = document.getElementById('widgetPreviewContainer');
        if (!document.fullscreenElement) {
            previewElement.requestFullscreen().then(() => {
                this.isFullscreen = true;
                document.addEventListener('fullscreenchange', this.handleEscape, { once: true });
            }).catch(err => {
                alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
            });
        } else {
            document.exitFullscreen().then(() => {
                this.isFullscreen = false;
            });
        }
    },
    handleEscape() {
        if (!document.fullscreenElement) {
            this.isFullscreen = false;
        }
    },
    setActiveTab(tab) {
        this.activeTab = tab;
        window.location.href = `/widget-preview?category=${tab}`;
    }
}"
@keyup.escape.window="if(isFullscreen) { document.exitFullscreen().then(() => { isFullscreen = false; }); }"
class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 text-gray-900 dark:from-gray-900 dark:to-gray-800 dark:text-gray-100 flex flex-col items-center p-4 md:p-8 relative">

    <!-- Branding Header -->
    <div class="w-full max-w-6xl mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-purple-600 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Digital Signage Widgets</h1>
            </div>

            <div class="flex items-center space-x-4">
                <button @click="toggleFullscreen"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-purple-400 flex items-center">
                    <span x-show="!isFullscreen" class="flex items-center">
                        <i class="fas fa-expand mr-2"></i>
                        <span class="hidden sm:inline">Fullscreen</span>
                    </span>
                    <span x-show="isFullscreen" class="flex items-center">
                        <i class="fas fa-compress mr-2"></i>
                        <span class="hidden sm:inline">Exit</span>
                    </span>
                </button>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="p-2 bg-white dark:bg-gray-700 rounded-full shadow-md text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400">
                        <i class="fas fa-question-circle"></i>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 overflow-hidden">
                        <div class="p-4 border-b dark:border-gray-700">
                            <h3 class="font-semibold text-gray-800 dark:text-white">Widget Preview Help</h3>
                        </div>
                        <div class="p-4 text-sm text-gray-600 dark:text-gray-300">
                            <p class="mb-2">This is a preview of our digital signage widgets.</p>
                            <p class="mb-2">Use the tabs below to switch between different widget types.</p>
                            <p class="mb-2">Click the fullscreen button to view the widget in fullscreen mode.</p>
                            <p>Press ESC to exit fullscreen mode.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Interactive preview of our digital signage widgets. Select a widget type from the tabs below.
        </p>
    </div>

    <!-- Quick Navigation Tabs -->
    <div class="w-full max-w-6xl mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-1">
            <div class="flex overflow-x-auto pb-2">
                @foreach(App\Enums\TemplateCategory::cases() as $tabCategory)
                    <button @click="setActiveTab('{{ $tabCategory->value }}')"
                        class="flex-shrink-0 px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center whitespace-nowrap"
                        :class="{
                            'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200': activeTab === '{{ $tabCategory->value }}',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700': activeTab !== '{{ $tabCategory->value }}'
                        }">
                        <i class="{{ $tabCategory->getIcon() }} mr-2"></i>
                        <span>{{ $tabCategory->label() }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Device Frame -->
    <div class="w-full max-w-6xl relative">
        <!-- Device Frame Background -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
            <div class="w-full h-full max-w-4xl mx-auto">
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full">
                    <rect x="0" y="0" width="100" height="100" rx="8" fill="none" stroke="url(#frameGradient)" stroke-width="2" />
                    <defs>
                        <linearGradient id="frameGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="rgba(147, 51, 234, 0.2)" />
                            <stop offset="100%" stop-color="rgba(79, 70, 229, 0.2)" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>

        <!-- Widget Preview Container -->
        <div id="widgetPreviewContainer"
            class="relative z-10 w-full max-w-4xl mx-auto aspect-[16/9] bg-gray-100 dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden flex border-2 border-gray-200 dark:border-gray-700">
            <div class="w-full h-full flex flex-col">
                @switch($category)
                    @case(App\Enums\TemplateCategory::WEATHER)
                        @livewire('content.widgets.weather-widget', [
                            'apiKey' => $weatherApiKey,
                            'location' => $weatherLocation,
                            'refreshInterval' => 600,
                            'key' => 'single-widget-weather-' . $category->value,
                        ])
                    @break

                    @case(App\Enums\TemplateCategory::ANNOUNCEMENT)
                        @livewire('content.widgets.announcement-widget', [
                            'title' => $defaultAnnouncementTitle,
                            'message' => $defaultAnnouncementMessage,
                            'backgroundColor' => '#E0F2FE',
                            'textColor' => '#0C4A6E',
                            'titleColor' => '#075985',
                            'key' => 'single-widget-announcement-' . $category->value,
                        ])
                    @break

                    @case(App\Enums\TemplateCategory::CLOCK)
                        @livewire('content.widgets.clock-widget', [
                            'timezone' => 'Europe/London',
                            'showSeconds' => true,
                            'format' => 'H:i:s',
                            'showDate' => true,
                            'dateFormat' => 'l, F j, Y',
                            'key' => 'single-widget-clock-' . $category->value,
                        ])
                    @break

                    @case(App\Enums\TemplateCategory::CUSTOM)
                        @livewire('content.widgets.custom-text-widget', [
                            'text' => $defaultCustomText,
                            'fontSize' => '2.5rem',
                            'textColor' => '#FFFFFF',
                            'backgroundColor' => '#1E3A8A',
                            'textAlign' => 'center',
                            'padding' => '20px',
                            'key' => 'single-widget-custom-text-' . $category->value,
                        ])
                    @break

                    @case(App\Enums\TemplateCategory::RSS_FEED)
                        @livewire('content.widgets.rss-feed-widget', [
                            'feedUrl' => $rssFeedUrl,
                            'itemCount' => $rssItemCount,
                            'refreshInterval' => 900,
                            'key' => 'single-widget-rss-feed-' . $category->value,
                        ])
                    @break

                    @case(App\Enums\TemplateCategory::MENU)
                        @livewire('content.widgets.menu-widget', [
                            'initialData' => [
                                'title' => 'Restaurant Specials Preview',
                                'categories' => $menuData,
                            ],
                            'settings' => [
                                'refresh_interval' => 300,
                                'active_view' => 'modernDark',
                                'widget_title' => 'Today\'s Menu (Preview)',
                                'show_prices' => true,
                                'show_calories' => true,
                                'show_allergens' => true,
                                'currency' => '$',
                            ],
                            'title' => 'Menu Widget Preview',
                            'key' => 'single-widget-menu-' . $category->value,
                        ])
                    @break

                    @case(App\Enums\TemplateCategory::RETAIL)
                        @livewire('content.widgets.retail-widget', [
                            'initialData' => [
                                'title' => 'Latest Product Showcase',
                                'products' => $productData,
                            ],
                            'settings' => [
                                'refresh_interval' => 600,
                                'active_view' => 'modern-grid',
                                'widget_title' => 'Featured Collection (Preview)',
                                'show_price' => true,
                                'show_rating' => true,
                                'show_add_to_cart_button' => true,
                                'currency' => '$',
                                'grid_columns' => 3,
                                'default_sort' => 'popularity',
                            ],
                            'title' => 'Retail Widget Preview',
                            'key' => 'single-widget-retail-' . $category->value,
                        ])
                    @break

                    @case(App\Enums\TemplateCategory::CALENDAR)
                        @livewire('content.widgets.calendar-widget', [
                            'initialData' => [
                                'title' => 'Upcoming Company Schedule',
                                'events' => $calendarEventData,
                            ],
                            'settings' => [
                                'refresh_interval' => 300,
                                'active_view' => 'month-view',
                                'widget_title' => 'Events Preview',
                                'start_of_week' => 'monday',
                                'event_display_limit' => 3,
                                'display_time_zone' => config('app.timezone', 'UTC'),
                                'default_date' => now()->toDateString(),
                            ],
                            'title' => 'Calendar Widget Preview',
                            'key' => 'single-widget-calendar-' . $category->value,
                        ])
                    @break

                    @default
                        <div class="p-8 text-lg text-center text-gray-500 dark:text-gray-400 flex items-center justify-center h-full">
                            <div class="max-w-md mx-auto">
                                <i class="fas fa-info-circle text-4xl mb-4 text-gray-400"></i>
                                <h3 class="text-xl font-semibold mb-2">Widget Preview</h3>
                                <p class="mb-4">Widget for '{{ $category->label() }}' is not configured for preview or is coming soon.</p>
                                <button @click="setActiveTab('menu')"
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                                    Try Menu Widget
                                </button>
                            </div>
                        </div>
                @endswitch
            </div>
        </div>
    </div>

    <!-- Widget Information -->
    <div class="w-full max-w-6xl mt-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Widget Information</h2>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">
                        Previewing: <span class="font-medium">{{ $category->label() }}</span>
                    </p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-3">About This Widget</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        @switch($category)
                            @case(App\Enums\TemplateCategory::WEATHER)
                                Displays current weather information with customizable location and refresh interval.
                            @break
                            @case(App\Enums\TemplateCategory::ANNOUNCEMENT)
                                Shows important announcements with customizable colors and text.
                            @break
                            @case(App\Enums\TemplateCategory::CLOCK)
                                Digital clock with timezone support and customizable date/time formats.
                            @break
                            @case(App\Enums\TemplateCategory::CUSTOM)
                                Fully customizable text widget for any message or information.
                            @break
                            @case(App\Enums\TemplateCategory::RSS_FEED)
                                Displays RSS feed items with customizable item count and refresh interval.
                            @break
                            @case(App\Enums\TemplateCategory::MENU)
                                Restaurant menu display with multiple views and customizable options.
                            @break
                            @case(App\Enums\TemplateCategory::RETAIL)
                                Product showcase with grid layout and customizable display options.
                            @break
                            @case(App\Enums\TemplateCategory::CALENDAR)
                                Event calendar with multiple views and customizable display options.
                            @break
                            @default
                                This widget type is not yet documented.
                        @endswitch
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-3">Configuration Options</h3>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-300">
                        @switch($category)
                            @case(App\Enums\TemplateCategory::WEATHER)
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>API Key Configuration</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Location Selection</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Refresh Interval</span>
                                </li>
                            @break
                            @case(App\Enums\TemplateCategory::ANNOUNCEMENT)
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Custom Colors</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Title & Message</span>
                                </li>
                            @break
                            @case(App\Enums\TemplateCategory::CLOCK)
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Timezone Selection</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Date/Time Formats</span>
                                </li>
                            @break
                            @case(App\Enums\TemplateCategory::CUSTOM)
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Custom Text</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Styling Options</span>
                                </li>
                            @break
                            @case(App\Enums\TemplateCategory::RSS_FEED)
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Feed URL</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Item Count</span>
                                </li>
                            @break
                            @case(App\Enums\TemplateCategory::MENU)
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Multiple Views</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Display Options</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Currency Settings</span>
                                </li>
                            @break
                            @case(App\Enums\TemplateCategory::RETAIL)
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Grid Layout</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Product Display</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Sorting Options</span>
                                </li>
                            @break
                            @case(App\Enums\TemplateCategory::CALENDAR)
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Multiple Views</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Event Display</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>Time Zone Support</span>
                                </li>
                            @break
                            @default
                                <li>No configuration options available</li>
                        @endswitch
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="w-full max-w-6xl mt-8 py-6 border-t border-gray-200 dark:border-gray-700">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-500 dark:text-gray-400 text-sm mb-4 md:mb-0">
                © {{ date('Y') }} Digital Signage Solutions. All rights reserved.
            </div>
            <div class="flex space-x-4">
                <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                    <i class="fab fa-github"></i>
                </a>
                <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                    <i class="fab fa-linkedin"></i>
                </a>
            </div>
        </div>
    </div>
</div>
