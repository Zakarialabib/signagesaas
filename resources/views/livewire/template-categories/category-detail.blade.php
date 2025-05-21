<div>
    <div class="py-12 sm:py-16 bg-white dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Category Header -->
            <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none">
                <div class="flex items-center gap-x-4 mb-6">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 text-2xl">
                        {!! $category->getIcon() !!}
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        {{ $category->label() }} Templates
                    </h1>
                </div>
                <p class="mt-2 text-lg leading-8 text-gray-600 dark:text-gray-400">
                    {{ $category->getDescription() }}
                </p>
            </div>

            <!-- Template Gallery -->
            <div class="mt-12 grid grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($templates as $template)
                    <div class="group relative">
                        <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-2xl bg-gray-100 dark:bg-gray-800">
                            <img src="{{ $template->preview_image ?? 'https://via.placeholder.com/400x300.png?text=Preview' }}"
                                alt="{{ $template->name }} preview"
                                class="object-cover object-center group-hover:opacity-90 transition-opacity">
                            <div class="flex items-end p-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('templates.preview', $template) }}"
                                    class="rounded-full bg-white dark:bg-gray-900 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                    Preview Template
                                </a>
                            </div>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">
                            <a href="{{ route('templates.preview', $template) }}">
                                <span class="absolute inset-0"></span>
                                {{ $template->name }}
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $template->description }}
                        </p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @if (is_array($template->tags) || is_object($template->tags))
                                @foreach ($template->tags as $tag)
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-300">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="flex flex-col items-center">
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 text-3xl mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h14.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">No Templates Yet</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">There are currently no templates available
                                in this category. Check back soon!</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Related Categories -->
            @if (!empty($relatedCategories))
                <div class="mt-20">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Related Categories
                    </h2>
                    <div class="mt-6 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($relatedCategories as $relatedCategory)
                            <a href="{{ route('template-category.show', $relatedCategory->value) }}"
                                class="group flex items-center gap-x-4 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xl">
                                    {!! $relatedCategory->getIcon() !!}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $relatedCategory->label() }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ Illuminate\Support\Str::limit($relatedCategory->getDescription(), 60) }}
                                    </p>
                                </div>
                                <svg class="ml-auto h-5 w-5 text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors"
                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
