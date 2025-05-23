<div>
    <div
        class="min-h-screen bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100 flex flex-col items-center justify-center p-4 md:p-8">
        <h1 class="text-2xl md:text-3xl font-semibold mb-6 text-center">Widget Preview: {{ $category->label() }}</h1>
        <div class="w-full max-w-3xl aspect-[16/9] bg-gray-300 dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            {{-- Dynamically render the widget based on category --}}
            @switch($category)
                @case(App\Enums\TemplateCategory::WEATHER)
                    @livewire('content.widgets.weather-widget', [
                        'apiKey' => $weatherApiKey,
                        'location' => $weatherLocation,
                        'refreshInterval' => 600, // Refresh every 10 minutes
                        'key' => 'single-widget-weather-' . $category->value,
                    ])
                    @break
                @case(App\Enums\TemplateCategory::ANNOUNCEMENT)
                    @livewire('content.widgets.announcement-widget', [
                        'title' => $defaultAnnouncementTitle,
                        'message' => $defaultAnnouncementMessage,
                        'backgroundColor' => '#E0F2FE', // Light blue
                        'textColor' => '#0C4A6E', // Dark blue
                        'titleColor' => '#075985', // Darker blue for title
                        'key' => 'single-widget-announcement-' . $category->value
                    ])
                    @break
                @case(App\Enums\TemplateCategory::CLOCK)
                    @livewire('content.widgets.clock-widget', [
                        'timezone' => 'Europe/London', // Example: Make this configurable
                        'showSeconds' => true,
                        'format' => 'H:i:s', // HH:MM:SS (24-hour) or 'h:i:s A' (12-hour with AM/PM)
                        'showDate' => true,
                        'dateFormat' => 'l, F j, Y', // e.g., Monday, January 1, 2024
                        'key' => 'single-widget-clock-' . $category->value
                    ])
                    @break
                @case(App\Enums\TemplateCategory::CUSTOM)
                     @livewire('content.widgets.custom-text-widget', [
                        'text' => $defaultCustomText,
                        'fontSize' => '2.5rem', // Example size
                        'textColor' => '#FFFFFF',
                        'backgroundColor' => '#1E3A8A', // Dark blue
                        'textAlign' => 'center', // 'left', 'center', 'right', 'justify'
                        'padding' => '20px',
                        'key' => 'single-widget-custom-text-' . $category->value,
                     ])
                    @break
                @case(App\Enums\TemplateCategory::RSS_FEED)
                     @livewire('content.widgets.rss-feed-widget', [
                        'feedUrl' => $rssFeedUrl,
                        'itemCount' => $rssItemCount,
                        'refreshInterval' => 900, // Refresh every 15 minutes
                        'key' => 'single-widget-rss-feed-' . $category->value,
                     ])
                    @break
                {{-- Add more cases for other widgets as you create them --}}
                @default
                    <div class="p-8 text-lg text-center text-gray-500 dark:text-gray-400 flex items-center justify-center h-full">
                        Widget for '{{ $category->label() }}' is not configured for preview or is coming soon.
                    </div>
            @endswitch
        </div>
        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            <p>This is a preview page for individual widgets. Actual appearance may vary when embedded in a full template.</p>
            <p>Current category: <strong>{{ $category->value }}</strong></p>
        </div>
    </div>
</div>
