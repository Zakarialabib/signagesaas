    <div>
        <div x-data="{
            isFullscreen: false,
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
            }
        }"
            @keyup.escape.window="if(isFullscreen) { document.exitFullscreen().then(() => { isFullscreen = false; }); }"
            class="min-h-screen bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100 flex flex-col items-center justify-center p-4 md:p-8 relative">

            <div class="absolute top-4 right-4 z-50">
                <button @click="toggleFullscreen"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <span x-show="!isFullscreen"><i class="fas fa-expand mr-2"></i>Toggle Fullscreen</span>
                    <span x-show="isFullscreen"><i class="fas fa-compress mr-2"></i>Exit Fullscreen</span>
                </button>
            </div>

            <h1 class="text-2xl md:text-3xl font-semibold mb-6 text-center">Widget Preview: {{ $category->label() }}</h1>

            <div id="widgetPreviewContainer"
                class="w-full max-w-screen-lg aspect-[16/9] bg-gray-300 dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden flex">
                {{-- The Livewire component should be forced to take full height of this container --}}
                {{-- Adding a wrapper div to help with h-full for the livewire component if its root isn't h-full by default --}}
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
                            <div
                                class="p-8 text-lg text-center text-gray-500 dark:text-gray-400 flex items-center justify-center h-full">
                                Widget for '{{ $category->label() }}' is not configured for preview or is coming soon.
                            </div>
                    @endswitch
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                <p>This is a preview page for individual widgets. Actual appearance may vary when embedded in a full
                    template.</p>
                <p>Current category: <strong>{{ $category->value }}</strong>. Press ESC to exit fullscreen mode.</p>
            </div>
        </div>
    </div>
