{{-- resources/views/components/base-demos.blade.php --}}
@props([
    'title',
    'iconSvgPath',
    'description',
    'features' => [],
    'category',
    'themeColor',
    'gradientToColor' => null,
    'industry' => null,
    'useCases' => [],
    'isBestSeller' => false,
    'isNew' => false,
])

@php
    $actualGradientToColor = $gradientToColor ?? $themeColor;
    $hasMultipleUseCases = count($useCases) > 0;
@endphp

<div
    class="group relative flex flex-col h-full rounded-3xl bg-white/5 p-6 shadow-2xl backdrop-blur-sm transition-all hover:scale-[1.02] hover:shadow-{{ $themeColor }}-500/20">
    <!-- Badges -->
    <div class="absolute -right-2 -top-2 flex flex-col space-y-1">
        @if ($isBestSeller)
            <span
                class="inline-flex items-center rounded-full bg-amber-500/90 px-2 py-1 text-xs font-medium text-white shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-1 h-3 w-3">
                    <path fill-rule="evenodd"
                        d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                        clip-rule="evenodd" />
                </svg>
                Best Seller
            </span>
        @endif
        @if ($isNew)
            <span
                class="inline-flex items-center rounded-full bg-green-500/90 px-2 py-1 text-xs font-medium text-white shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-1 h-3 w-3">
                    <path fill-rule="evenodd"
                        d="M7.312 6.424a5.5 5.5 0 0111.176 0A20.905 20.905 0 0118 10c0 2.724-.641 5.3-1.782 7.6a.75.75 0 01-1.393-.563c1.157-2.924 1.675-5.927 1.675-9.037 0-1.71-.41-3.32-1.134-4.739a3.5 3.5 0 00-6.604 0A9.965 9.965 0 006 10c0 3.11.518 6.113 1.675 9.037a.75.75 0 01-1.393.563A20.902 20.902 0 012 10c0-2.725.641-5.3 1.782-7.6.114-.22.33-.423.636-.53a3.5 3.5 0 011.892 0zM13 10a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0113 10zm-6 0a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 017 10z"
                        clip-rule="evenodd" />
                </svg>
                New Release
            </span>
        @endif
    </div>

    <div
        class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-{{ $themeColor }}-600/30 to-{{ $actualGradientToColor }}-600/30 opacity-0 transition-opacity duration-500 group-hover:opacity-100">
    </div>

    <div class="relative flex flex-col flex-grow">
        {{-- Header with Industry Tag --}}
        <div class="flex items-start justify-between gap-x-4">
            <div class="flex items-center gap-x-4">
                <div
                    class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-{{ $themeColor }}-600/10 text-{{ $themeColor }}-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-7 w-7">
                        {!! $iconSvgPath !!}
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold leading-7 text-white">{{ $title }}</h3>
                    @if ($industry)
                        <span
                            class="inline-flex items-center rounded-full bg-{{ $themeColor }}-800/70 px-2 py-0.5 text-xs font-medium text-{{ $themeColor }}-300 mt-1">
                            {{ $industry }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Description --}}
        <p class="mt-4 text-base text-gray-300">
            {!! $description !!}
        </p>

        {{-- Additional Use Cases (Collapsible) --}}
        @if ($hasMultipleUseCases)
            <div x-data="{ expanded: false }" class="mt-3">
                <button @click="expanded = !expanded"
                    class="flex items-center text-xs text-{{ $themeColor }}-400 hover:text-{{ $themeColor }}-300">
                    <span>More industry uses</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="ml-1 h-3 w-3 transition-transform" :class="{ 'rotate-180': expanded }">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="expanded" x-collapse class="mt-2 space-y-2 text-xs text-gray-400">
                    @foreach ($useCases as $useCase)
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="mr-1.5 mt-0.5 h-3 w-3 text-{{ $themeColor }}-400 flex-shrink-0">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>{!! $useCase !!}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Features --}}
        @if (!empty($features))
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($features as $feature)
                    <div
                        class="flex items-center rounded-full bg-gray-800/50 px-3 py-1 text-xs font-medium text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="mr-1 h-3 w-3 text-{{ $themeColor }}-400">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $feature }}
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Spacer to push CTA to bottom --}}
        <div class="flex-grow"></div>

        {{-- CTA Link --}}
        <a href="{{ route('tenant.tv.widget', ['category' => $category]) }}"
            class="mt-8 inline-flex items-center gap-x-1.5 text-sm font-semibold text-white hover:text-{{ $themeColor }}-300 transition-colors self-start group-hover:underline">
            View {{ $title }} Demo
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="h-4 w-4 transition-transform group-hover:translate-x-0.5">
                <path fill-rule="evenodd"
                    d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z"
                    clip-rule="evenodd" />
            </svg>
        </a>
    </div>
</div>
