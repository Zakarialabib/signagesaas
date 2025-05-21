<x-dynamic-component
    :component="$card->icon"
    class="h-8 w-8 text-indigo-500 dark:text-indigo-400"
/>

<div class="mt-4">
    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
        {{ $card->title }}
    </h3>
    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
        {{ $card->description }}
    </p>
</div>

@if($card->features)
    <div class="mt-4">
        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Features</h4>
        <ul class="mt-2 list-disc list-inside text-sm text-gray-500 dark:text-gray-400 space-y-1">
            @foreach($card->features as $feature)
                <li>{{ $feature }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($card->tips)
    <div class="mt-4">
        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Tips</h4>
        <ul class="mt-2 list-disc list-inside text-sm text-gray-500 dark:text-gray-400 space-y-1">
            @foreach($card->tips as $tip)
                <li>{{ $tip }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($card->imagePath || $card->videoPath)
    <div class="mt-4">
        @if($card->imagePath)
            <img src="{{ asset($card->imagePath) }}" 
                 alt="{{ $card->title }}" 
                 class="w-full h-auto rounded-lg shadow-sm">
        @endif
        
        @if($card->videoPath)
            <video 
                class="w-full h-auto rounded-lg shadow-sm mt-4"
                controls
                poster="{{ asset(str_replace('.mp4', '-poster.jpg', $card->videoPath)) }}">
                <source src="{{ asset($card->videoPath) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @endif
    </div>
@endif

@if($card->bestPractices)
    <div class="mt-4 bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
        <h4 class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Best Practices</h4>
        <ul class="mt-2 list-disc list-inside text-sm text-indigo-600 dark:text-indigo-300 space-y-1">
            @foreach($card->bestPractices as $practice)
                <li>{{ $practice }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($card->relatedSteps)
    <div class="mt-4 flex flex-wrap gap-2">
        @foreach($card->relatedSteps as $step)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                {{ str_replace('_', ' ', Str::title($step)) }}
            </span>
        @endforeach
    </div>
@endif

@if($card->documentationUrl)
    <div class="mt-6">
        <a href="{{ $card->documentationUrl }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
            Learn More
            <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </a>
    </div>
@endif
