<div>
    <x-modal wire:model="showModal" maxWidth="2xl">
        <x-slot name="title">
            Edit Widget Data
        </x-slot>
    
        <x-slot name="content">
            <form wire:submit="save">
                <div class="space-y-4">
                    <div>
                        <label for="contentName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content Name</label>
                        <input type="text" wire:model="contentName" id="contentName"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 @error('contentName') border-red-500 @enderror"
                               placeholder="Enter a name for this content item">
                        @error('contentName') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
    
                    @if($widgetType == 'MenuWidget')
                        <div wire:key="menu-widget-editor" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Menu Categories</h3>
                                <button type="button" wire:click="addCategory"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <x-heroicon-s-plus class="h-4 w-4 mr-1" />
                                    Add Category
                                </button>
                            </div>
    
                            @if(empty($widgetData['categories']))
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">No categories added yet. Click "Add Category" to start building your menu.</p>
                            @endif
    
                            @foreach($widgetData['categories'] ?? [] as $catIndex => $category)
                                <div wire:key="category-{{ $catIndex }}" class="mt-4 p-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/30">
                                    <div class="flex justify-between items-center">
                                        <input type="text" wire:model="widgetData.categories.{{ $catIndex }}.name"
                                               placeholder="Category Name (e.g., Appetizers, Main Courses)"
                                               class="block w-full text-md font-semibold rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                        <button type="button" wire:click="removeCategory({{ $catIndex }})"
                                                class="ml-3 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-500/30 dark:text-red-300 dark:hover:bg-red-500/50">
                                            <x-heroicon-s-trash class="h-4 w-4" />
                                        </button>
                                    </div>
    
                                    <h4 class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-200">Items in this category:</h4>
                                    @foreach($category['items'] ?? [] as $itemIndex => $item)
                                        <div wire:key="category-{{ $catIndex }}-item-{{ $itemIndex }}" class="mt-2 p-3 border border-gray-200 dark:border-gray-500 rounded-md bg-white dark:bg-gray-600/50 space-y-2">
                                            <input type="text" wire:model="widgetData.categories.{{ $catIndex }}.items.{{ $itemIndex }}.name"
                                                   placeholder="Item Name (e.g., Caesar Salad)"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-500 dark:border-gray-400 dark:text-gray-50">
                                            <div class="grid grid-cols-2 gap-2">
                                                <input type="text" wire:model="widgetData.categories.{{ $catIndex }}.items.{{ $itemIndex }}.price"
                                                       placeholder="Price (e.g., 9.99)"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-500 dark:border-gray-400 dark:text-gray-50">
                                                <input type="text" wire:model="widgetData.categories.{{ $catIndex }}.items.{{ $itemIndex }}.calories"
                                                       placeholder="Calories (e.g., 350kcal)"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-500 dark:border-gray-400 dark:text-gray-50">
                                            </div>
                                            <textarea wire:model="widgetData.categories.{{ $catIndex }}.items.{{ $itemIndex }}.description"
                                                      placeholder="Description (e.g., Fresh romaine lettuce, parmesan cheese, croutons, and Caesar dressing)"
                                                      rows="2"
                                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-500 dark:border-gray-400 dark:text-gray-50"></textarea>
                                            <div class="text-right">
                                                <button type="button" wire:click="removeItem({{ $catIndex }}, {{ $itemIndex }})"
                                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 dark:text-red-400 dark:hover:text-red-300">
                                                    Remove Item
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                    <button type="button" wire:click="addItem({{ $catIndex }})"
                                            class="mt-3 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <x-heroicon-s-plus-circle class="h-4 w-4 mr-1" />
                                        Add Item to {{ $category['name'] ?: 'Category' }}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @elseif($widgetType == 'RetailProductWidget')
                        <div wire:key="retail-product-widget-editor" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Retail Product Details</h3>
                            </div>
    
                            <!-- Main Title -->
                            <div>
                                <label for="retailWidgetTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Widget Title</label>
                                <input type="text" wire:model="widgetData.title" id="retailWidgetTitle"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100"
                                       placeholder="e.g., Featured Products">
                            </div>
    
                            <!-- Products -->
                            <div class="mt-6">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-md font-medium text-gray-800 dark:text-gray-200">Products</h4>
                                    <button type="button" wire:click="addProduct"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <x-heroicon-s-plus class="h-4 w-4 mr-1" />
                                        Add Product
                                    </button>
                                </div>
    
                                @if(empty($widgetData['products']))
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">No products added yet. Click "Add Product" to start.</p>
                                @endif
    
                                @foreach($widgetData['products'] ?? [] as $prodIndex => $product)
                                    <div wire:key="product-{{ $prodIndex }}" class="mt-4 p-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/30 space-y-3">
                                        <div class="flex justify-between items-center">
                                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Product #{{ $prodIndex + 1 }}</h5>
                                            <button type="button" wire:click="removeProduct({{ $prodIndex }})"
                                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-500/30 dark:text-red-300 dark:hover:bg-red-500/50">
                                                <x-heroicon-s-trash class="h-4 w-4" />
                                            </button>
                                        </div>
                                        
                                        <input type="text" wire:model="widgetData.products.{{ $prodIndex }}.name" placeholder="Product Name"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-500 dark:border-gray-400 dark:text-gray-50">
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <input type="text" wire:model="widgetData.products.{{ $prodIndex }}.price" placeholder="Price (e.g., 29.99)"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-500 dark:border-gray-400 dark:text-gray-50">
                                            <input type="text" wire:model="widgetData.products.{{ $prodIndex }}.sale_price" placeholder="Sale Price (optional)"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-500 dark:border-gray-400 dark:text-gray-50">
                                        </div>
                                        
                                        <input type="text" wire:model="widgetData.products.{{ $prodIndex }}.image" placeholder="Image URL/Path (e.g., /images/product.jpg)"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-500 dark:border-gray-400 dark:text-gray-50">
                                        
                                        <textarea wire:model="widgetData.products.{{ $prodIndex }}.description" placeholder="Product Description" rows="3"
                                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-500 dark:border-gray-400 dark:text-gray-50"></textarea>
                                        
                                        <input type="text" wire:model="widgetData.products.{{ $prodIndex }}.promotion_badge" placeholder="Promotion Badge (e.g., 20% OFF)"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-500 dark:border-gray-400 dark:text-gray-50">
                                    </div>
                                @endforeach
                            </div>
    
                            <!-- Footer Promo Text -->
                            <div class="mt-6">
                                <label for="retailWidgetFooter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Footer Promo Text</label>
                                <input type="text" wire:model="widgetData.footer_promo_text" id="retailWidgetFooter"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100"
                                       placeholder="e.g., All offers valid this week only!">
                            </div>
                        </div>
                    @elseif($widgetType)
                        {{-- Placeholder for other widget types --}}
                        <div class="mt-4 p-4 border rounded-md bg-gray-50 dark:bg-gray-700">
                            <p class="text-gray-700 dark:text-gray-300">Widget Type: <strong>{{ $widgetType }}</strong></p>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Specific data editor for this widget type is not yet implemented.
                                You can edit the raw JSON data below if needed.
                            </p>
                            <textarea wire:model="widgetData" rows="10"
                                      class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100"
                                      placeholder="Enter JSON data for this widget"></textarea>
                            @error('widgetData') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    @else
                         <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">No widget type selected or recognized.</p>
                    @endif
    
                </div>
            </form>
        </x-slot>
    
        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <button wire:click="closeModal" type="button"
                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                    Cancel
                </button>
                <button wire:click="save" type="button"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Save Content
                </button>
            </div>
        </x-slot>
    </x-modal>
</div>
