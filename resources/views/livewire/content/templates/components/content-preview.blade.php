{{-- Content Zone Component --}}
<div class="content-preview">
    @switch($content->type->value)
        @case('image')
            <img src="{{ $content->content_data['url'] }}" alt="{{ $content->name }}" class="w-full h-full object-contain">
            @break
        @case('video')
            <video 
                src="{{ $content->content_data['url'] }}" 
                class="w-full h-full object-contain" 
                controls
                autoplay 
                muted 
                loop>
            </video>
            @break
        @case('html')
            <div class="w-full h-full overflow-auto">
                {!! $content->content_data['html'] !!}
            </div>
            @break
        @case('url')
            <iframe 
                src="{{ $content->content_data['url'] }}" 
                class="w-full h-full" 
                frameborder="0"
                sandbox="allow-scripts allow-same-origin">
            </iframe>
            @break
        @case('weather')
            <div class="p-4">
                <div class="weather-widget" data-location="{{ $content->content_data['location'] }}">
                    <p class="text-lg">Weather for {{ $content->content_data['location'] }}</p>
                </div>
            </div>
            @break
        @case('social')
            <div class="p-4">
                <div class="social-feed">
                    <p class="text-lg">{{ ucfirst($content->content_data['platform']) }} feed for {{ $content->content_data['handle'] }}</p>
                </div>
            </div>
            @break
        @case('calendar')
            <iframe 
                src="{{ $content->content_data['calendar_url'] }}" 
                class="w-full h-full"
                frameborder="0">
            </iframe>
            @break
        @default
            <div class="flex items-center justify-center h-full">
                <p class="text-gray-500">Unknown content type</p>
            </div>
    @endswitch
</div>
