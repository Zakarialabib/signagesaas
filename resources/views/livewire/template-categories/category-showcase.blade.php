<div>
    <div class="py-12 sm:py-16 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Featured Categories -->
            <div class="mx-auto max-w-2xl text-center mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Featured Template Categories
                </h2>
                <p class="mt-4 text-lg leading-8 text-gray-600 dark:text-gray-300">
                    Professionally designed templates for your digital signage needs
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                @foreach ($featuredCategories as $category)
                    <a href="{{ route('template-category.show', $category->value) }}"
                        class="group relative rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-lg ring-1 ring-gray-900/5 dark:ring-gray-700 transition-all hover:scale-105 hover:shadow-xl hover:ring-purple-500/50">
                        <div
                            class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-purple-600/20 to-pink-600/20 opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        <div class="relative">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 mb-4">
                                <span class="text-2xl">{!! $category->getIcon() !!}</span>
                            </div>
                            <h3 class="text-lg font-semibold leading-7 text-gray-900 dark:text-white">
                                {{ $category->label() }}
                            </h3>
                            <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                {{ $category->getDescription() }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Industry Categories -->
            <div class="mx-auto max-w-2xl text-center mt-20 mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Industry-Specific Templates
                </h2>
                <p class="mt-4 text-lg leading-8 text-gray-600 dark:text-gray-300">
                    Tailored solutions for your business sector
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($industryCategories as $category)
                    <div
                        class="group relative rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-lg ring-1 ring-gray-900/5 dark:ring-gray-700 overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-br opacity-10 dark:opacity-20 
                        @switch($category)
                            @case(App\Enums\TemplateCategory::RESTAURANT) from-amber-500 to-orange-500 @break
                            @case(App\Enums\TemplateCategory::RETAIL) from-blue-500 to-cyan-500 @break
                            @case(App\Enums\TemplateCategory::HOSPITALITY) from-emerald-500 to-teal-500 @break
                            @case(App\Enums\TemplateCategory::BANKING) from-green-500 to-lime-500 @break
                            @case(App\Enums\TemplateCategory::HEALTHCARE) from-red-500 to-pink-500 @break
                            @case(App\Enums\TemplateCategory::EDUCATION) from-indigo-500 to-purple-500 @break
                            @case(App\Enums\TemplateCategory::GOVERNMENT) from-gray-500 to-blue-500 @break
                            @case(App\Enums\TemplateCategory::TRANSPORTATION) from-yellow-500 to-amber-500 @break
                            @default from-purple-500 to-pink-500
                        @endswitch
                    ">
                        </div>
                        <div class="relative">
                            <div class="flex items-center gap-x-4 mb-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-opacity-20 text-2xl
                                @switch($category)
                                    @case(App\Enums\TemplateCategory::RESTAURANT) bg-amber-500 text-amber-600 dark:text-amber-400 @break
                                    @case(App\Enums\TemplateCategory::RETAIL) bg-blue-500 text-blue-600 dark:text-blue-400 @break
                                    @case(App\Enums\TemplateCategory::HOSPITALITY) bg-emerald-500 text-emerald-600 dark:text-emerald-400 @break
                                    @case(App\Enums\TemplateCategory::BANKING) bg-green-500 text-green-600 dark:text-green-400 @break
                                    @case(App\Enums\TemplateCategory::HEALTHCARE) bg-red-500 text-red-600 dark:text-red-400 @break
                                    @case(App\Enums\TemplateCategory::EDUCATION) bg-indigo-500 text-indigo-600 dark:text-indigo-400 @break
                                    @case(App\Enums\TemplateCategory::GOVERNMENT) bg-gray-500 text-gray-600 dark:text-gray-400 @break
                                    @case(App\Enums\TemplateCategory::TRANSPORTATION) bg-yellow-500 text-yellow-600 dark:text-yellow-400 @break
                                    @default bg-purple-500 text-purple-600 dark:text-purple-400
                                @endswitch
                            ">
                                    {!! $category->getIcon() !!}
                                </div>
                                <h3 class="text-lg font-semibold leading-7 text-gray-900 dark:text-white">
                                    {{ $category->label() }}
                                </h3>
                            </div>
                            <p class="text-sm leading-6 text-gray-600 dark:text-gray-400 mb-6">
                                {{ $category->getDescription() }}
                            </p>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('template-category.show', $category->value) }}"
                                    class="text-sm font-semibold leading-6
                                    @switch($category)
                                        @case(App\Enums\TemplateCategory::RESTAURANT) text-amber-600 dark:text-amber-400 @break
                                        @case(App\Enums\TemplateCategory::RETAIL) text-blue-600 dark:text-blue-400 @break
                                        @case(App\Enums\TemplateCategory::HOSPITALITY) text-emerald-600 dark:text-emerald-400 @break
                                        @case(App\Enums\TemplateCategory::BANKING) text-green-600 dark:text-green-400 @break
                                        @case(App\Enums\TemplateCategory::HEALTHCARE) text-red-600 dark:text-red-400 @break
                                        @case(App\Enums\TemplateCategory::EDUCATION) text-indigo-600 dark:text-indigo-400 @break
                                        @case(App\Enums\TemplateCategory::GOVERNMENT) text-gray-600 dark:text-gray-400 @break
                                        @case(App\Enums\TemplateCategory::TRANSPORTATION) text-yellow-600 dark:text-yellow-400 @break
                                        @default text-purple-600 dark:text-purple-400
                                    @endswitch
                                ">
                                    View templates <span aria-hidden="true">→</span>
                                </a>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @switch($category)
                                    @case(App\Enums\TemplateCategory::RESTAURANT) bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200 @break
                                    @case(App\Enums\TemplateCategory::RETAIL) bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 @break
                                    @case(App\Enums\TemplateCategory::HOSPITALITY) bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200 @break
                                    @case(App\Enums\TemplateCategory::BANKING) bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200 @break
                                    @case(App\Enums\TemplateCategory::HEALTHCARE) bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 @break
                                    @case(App\Enums\TemplateCategory::EDUCATION) bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-200 @break
                                    @case(App\Enums\TemplateCategory::GOVERNMENT) bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-200 @break
                                    @case(App\Enums\TemplateCategory::TRANSPORTATION) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200 @break
                                    @default bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200
                                @endswitch
                            ">
                                    {{ str_replace('_', ' ', $category->value) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Other Categories -->
            <div class="mx-auto max-w-2xl text-center mt-20 mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Additional Template Categories
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                @foreach ($otherCategories as $category)
                    <div
                        class="group relative rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-lg ring-1 ring-gray-900/5 dark:ring-gray-700">
                        <div class="flex items-center gap-x-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-2xl">
                                {!! $category->getIcon() !!}
                            </div>
                            <h3 class="text-lg font-semibold leading-7 text-gray-900 dark:text-white">
                                {{ $category->label() }}
                            </h3>
                        </div>
                        <p class="mt-4 text-sm leading-6 text-gray-600 dark:text-gray-400">
                            {{ $category->getDescription() }}
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('template-category.show', $category->value) }}"
                                class="inline-flex items-center text-sm font-semibold text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400">
                                Explore templates <span
                                    class="ml-1 group-hover:translate-x-1 transition-transform">→</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
