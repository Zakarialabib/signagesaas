<div class="relative group">
    <div class="relative overflow-hidden rounded-lg {{ $this->getSizeClasses() }} bg-gray-100 dark:bg-gray-800">
        @switch($content->type->value)
            @case('image')
                <img src="{{ $content->content_data['url'] ?? '' }}" 
                    alt="{{ $content->name }}" 
                    class="w-full h-full object-cover">
                @break
            @case('video')
                <div class="w-full h-full flex items-center justify-center bg-black">
                    <div class="absolute inset-0">
                        <video class="w-full h-full object-cover" muted>
                            <source src="{{ $content->content_data['url'] ?? '' }}" type="video/mp4">
                        </video>
                    </div>
                    <x-heroicon-s-play class="h-8 w-8 text-white opacity-75 z-10" />
                </div>
                @break
            @case('html')
                <div class="w-full h-full flex items-center justify-center">
                    <x-heroicon-s-code-bracket class="h-8 w-8 text-gray-400" />
                </div>
                @break
            @case('url')
                <div class="w-full h-full flex items-center justify-center">
                    <x-heroicon-s-globe-alt class="h-8 w-8 text-gray-400" />
                </div>
                @break
            @default
                <div class="w-full h-full flex items-center justify-center">
                    <x-heroicon-s-document class="h-8 w-8 text-gray-400" />
                </div>
        @endswitch

        @if($overlay)
            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                <span class="text-white text-sm font-medium">{{ $overlay }}</span>
            </div>
        @endif
    </div>

    @if($showDetails)
        <div class="mt-2">
            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $content->name }}</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $content->type->label() }}</p>
        </div>
    @endif
</div>
