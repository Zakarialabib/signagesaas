<div>
    <div class="bg-white dark:bg-gray-900">
        <!-- Hero Section -->
        <div
            class="relative isolate overflow-hidden bg-gradient-to-b from-purple-100/20 dark:from-purple-900/20 to-white dark:to-gray-900">
            <div class="mx-auto max-w-7xl px-6 pb-24 pt-10 sm:pb-32 lg:flex lg:px-8 lg:py-40">
                <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl lg:flex-shrink-0">
                    <div class="flex items-center gap-x-4 mb-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400">
                            {!! $category->getIcon() !!}
                        </div>
                        <span class="text-sm font-medium text-purple-600 dark:text-purple-400">
                            {{ $category->label() }} Template
                        </span>
                    </div>
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl">
                        {{ $template->name }}
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                        {{ $template->description }}
                    </p>
                    <div class="mt-10 flex items-center gap-x-6">
                        <a href="#"
                            class="rounded-md bg-purple-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600">
                            Use This Template
                        </a>
                        <a href="{{ route('template-category.show', $category->value) }}"
                            class="text-sm font-semibold leading-6 text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400">
                            View all {{ $category->label() }} templates <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
                <div
                    class="mx-auto mt-16 flex max-w-2xl sm:mt-24 lg:ml-10 lg:mt-0 lg:mr-0 lg:max-w-none lg:flex-none xl:ml-32">
                    <div class="max-w-3xl flex-none sm:max-w-5xl lg:max-w-none">
                        <div
                            class="-m-2 rounded-xl bg-gray-900/5 dark:bg-gray-100/5 p-2 ring-1 ring-inset ring-gray-900/10 dark:ring-gray-100/10 lg:-m-4 lg:rounded-2xl lg:p-4">
                            <img src="{{ $template->preview_image ?? 'https://via.placeholder.com/1216x684.png?text=Preview' }}"
                                alt="{{ $template->name }} screenshot"
                                class="w-[76rem] rounded-md shadow-2xl ring-1 ring-gray-900/10 dark:ring-gray-100/10">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Template Features -->
        @if (
            !empty($template->features) &&
                (is_array($template->features) || is_object($template->features)) &&
                count($template->features) > 0)
            <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <h2 class="text-base font-semibold leading-7 text-purple-600 dark:text-purple-400">Template Features
                    </h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        Everything you need for your digital signage
                    </p>
                </div>
                <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                    <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                        @foreach ($template->features as $feature)
                            <div class="flex flex-col">
                                <dt class="text-base font-semibold leading-7 text-gray-900 dark:text-white">
                                    <div
                                        class="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-purple-600 text-white">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>
                                    {{ $feature['name'] ?? 'Feature' }}
                                </dt>
                                <dd
                                    class="mt-1 flex flex-auto flex-col text-base leading-7 text-gray-600 dark:text-gray-400">
                                    <p class="flex-auto">{{ $feature['description'] ?? 'Description not available.' }}
                                    </p>
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>
        @endif

        <!-- Live Preview Section -->
        <div class="bg-gray-50 dark:bg-gray-800 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <h2 class="text-base font-semibold leading-7 text-purple-600 dark:text-purple-400">See it in action
                    </h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        Live Template Preview
                    </p>
                </div>
                <div class="mt-12">
                    <div
                        class="mx-auto max-w-5xl rounded-3xl bg-white dark:bg-gray-900 shadow-xl ring-1 ring-gray-900/5 overflow-hidden">
                        <!-- Template preview iframe or component would go here -->
                        <div class="aspect-video bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                            <div class="text-center p-8">
                                <div class="text-5xl mb-4">{!! $category->getIcon() !!}</div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $template->name }}</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">Live preview placeholder. Integrate
                                    actual template display here.</p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">(e.g., using an iframe or
                                    dynamic component loading)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Templates -->
        @if ($relatedTemplates && $relatedTemplates->count() > 0)
            <div class="py-24 sm:py-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl lg:text-center">
                        <h2 class="text-base font-semibold leading-7 text-purple-600 dark:text-purple-400">More options
                        </h2>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                            Related Templates
                        </p>
                    </div>
                    <div
                        class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                        @foreach ($relatedTemplates as $relatedTemplate)
                            <article class="flex flex-col items-start justify-between">
                                <div class="relative w-full">
                                    <img src="{{ $relatedTemplate->thumbnail ?? 'https://via.placeholder.com/400x225.png?text=Thumbnail' }}"
                                        alt="{{ $relatedTemplate->name }}"
                                        class="aspect-[16/9] w-full rounded-2xl bg-gray-100 dark:bg-gray-800 object-cover sm:aspect-[2/1] lg:aspect-[3/2]">
                                    <div
                                        class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-gray-900/10 dark:ring-gray-100/10">
                                    </div>
                                </div>
                                <div class="max-w-xl">
                                    <div class="mt-8 flex items-center gap-x-4 text-xs">
                                        @if ($relatedTemplate->created_at)
                                            <time datetime="{{ $relatedTemplate->created_at->format('Y-m-d') }}"
                                                class="text-gray-500 dark:text-gray-400">
                                                {{ $relatedTemplate->created_at->format('M d, Y') }}
                                            </time>
                                        @endif
                                        <a href="{{ route('template-category.show', $relatedTemplate->category) }}"
                                            class="relative z-10 rounded-full bg-gray-50 dark:bg-gray-800 px-3 py-1.5 font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            {{ App\Enums\TemplateCategory::from($relatedTemplate->category)->label() }}
                                        </a>
                                    </div>
                                    <div class="group relative">
                                        <h3
                                            class="mt-3 text-lg font-semibold leading-6 text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400">
                                            <a href="{{ route('templates.preview', $relatedTemplate) }}">
                                                <span class="absolute inset-0"></span>
                                                {{ $relatedTemplate->name }}
                                            </a>
                                        </h3>
                                        <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                            {{ $relatedTemplate->description }}
                                        </p>
                                    </div>
                                    <div class="relative mt-8 flex items-center gap-x-4">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-lg">
                                            {!! App\Enums\TemplateCategory::from($relatedTemplate->category)->getIcon() !!}
                                        </div>
                                        <div class="text-sm leading-6">
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                <a
                                                    href="{{ route('template-category.show', $relatedTemplate->category) }}">
                                                    <span class="absolute inset-0"></span>
                                                    {{ App\Enums\TemplateCategory::from($relatedTemplate->category)->label() }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
