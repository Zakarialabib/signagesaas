<div>
    <form wire:submit="save" class="space-y-6">
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div>
            <label for="listTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">List Title</label>
            <input type="text" wire:model="listTitle" id="listTitle"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            @error('listTitle')
                <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Items</h3>

        @if ($errors->has('items') || $errors->has('items.*'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                role="alert">
                Please correct the errors in the items below.
            </div>
        @endif

        <div class="space-y-4">
            @foreach ($items as $index => $item)
                <div wire:key="item-{{ $index }}"
                    class="p-4 border border-gray-200 dark:border-gray-700 rounded-md space-y-3">
                    <div class="flex justify-between items-center">
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200">Item #{{ $index + 1 }}</h4>
                        <button type="button" wire:click="removeItem({{ $index }})"
                            class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-semibold">
                            Remove Item
                        </button>
                    </div>

                    <div>
                        <label for="items.{{ $index }}.name"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400">Name*</label>
                        <input type="text" wire:model="items.{{ $index }}.name"
                            id="items.{{ $index }}.name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        @error("items.{$index}.name")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="items.{{ $index }}.price"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400">Price</label>
                        <input type="text" wire:model="items.{{ $index }}.price"
                            id="items.{{ $index }}.price"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        @error("items.{$index}.price")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="items.{{ $index }}.description"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400">Description</label>
                        <textarea wire:model="items.{{ $index }}.description" id="items.{{ $index }}.description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></textarea>
                        @error("items.{$index}.description")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="items.{{ $index }}.category"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400">Category</label>
                        <input type="text" wire:model="items.{{ $index }}.category"
                            id="items.{{ $index }}.category"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        @error("items.{$index}.category")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="items.{{ $index }}.image_url"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400">Image URL</label>
                        <input type="url" wire:model="items.{{ $index }}.image_url"
                            id="items.{{ $index }}.image_url"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                            placeholder="https://example.com/image.jpg">
                        @error("items.{$index}.image_url")
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center pt-4">
            <button type="button" wire:click="addItem"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Add Another Item
            </button>

            <button type="submit"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save Product List
            </button>
        </div>
    </form>
</div>
