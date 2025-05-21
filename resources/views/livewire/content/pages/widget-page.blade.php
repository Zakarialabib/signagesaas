<div>
    <div
        class="min-h-screen bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 flex flex-col items-center justify-center p-8">
        <h1 class="text-3xl font-semibold mb-6">Widget: {{ $category->label() }}</h1>
        <div class="w-full max-w-2xl flex flex-col items-center">
            @if ($isWeatherWidget)
                @livewire('content.widgets.weather-widget', [
                    'apiKey' => $weatherApiKey,
                    'location' => $weatherLocation,
                    'key' => 'single-widget-weather-' . $category->value,
                ])
            @elseif ($category->value === 'some_other_widget_category_value')
                {{-- @livewire('content.widgets.some-other-widget', ['config' => $otherConfig]) --}}
            @else
                <div class="text-lg text-gray-500 dark:text-gray-400">Widget for {{ $category->label() }} coming soon or
                    configuration missing.</div>
            @endif
        </div>
    </div>
</div>
