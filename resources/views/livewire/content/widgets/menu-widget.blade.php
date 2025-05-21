@extends('livewire.content.widgets.base-widget')

@section('content')
    <div class="space-y-8">
        @foreach($menu as $category)
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">{{ $category['name'] }}</h3>
                @if(!empty($category['description']))
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">{{ $category['description'] }}</p>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($category['items'] as $item)
                        <div class="flex items-start bg-white dark:bg-gray-800 rounded-lg shadow p-4 relative">
                            @if(!empty($item['image']))
                                <img src="/images/menu/{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded mr-4" loading="lazy">
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mr-2">{{ $item['name'] }}</h4>
                                    @if($item['special'])
                                        <span class="ml-2 inline-flex items-center rounded px-2 py-0.5 text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200" aria-label="Chef's Special">Chef's Special</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ $item['description'] }}</p>
                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    @if($showPrices)
                                        <span class="text-base font-semibold text-purple-600 dark:text-purple-400">{{ $currency }}{{ number_format($item['price'], 2) }}</span>
                                    @endif
                                    @if($showCalories)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item['calories'] }} kcal</span>
                                    @endif
                                    @if($showAllergens && !empty($item['allergens']))
                                        <span class="text-xs text-red-500 dark:text-red-400" aria-label="Allergens">
                                            <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            {{ implode(', ', $item['allergens']) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">Last updated {{ $lastUpdated }}</div>
@endsection

@section('settings')
    <div class="space-y-4">
        <div>
            <label for="menuType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Menu Type</label>
            <select id="menuType" wire:model="menuType" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                <option value="restaurant">Restaurant</option>
                <option value="cafeteria">Cafeteria</option>
                <option value="bar">Bar</option>
            </select>
        </div>
        <div class="flex items-center gap-4">
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="showPrices" class="rounded border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Prices</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="showCalories" class="rounded border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Calories</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="showAllergens" class="rounded border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Allergens</span>
            </label>
        </div>
        <div>
            <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
            <input type="text" id="currency" wire:model="currency" maxlength="3" class="mt-1 block w-24 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
        </div>
        <div>
            <label for="refreshInterval" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Refresh Interval (seconds)</label>
            <input type="number" id="refreshInterval" wire:model="refreshInterval" min="60" max="3600" step="60" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-purple-500 focus:ring-purple-500">
        </div>
    </div>
@endsection 