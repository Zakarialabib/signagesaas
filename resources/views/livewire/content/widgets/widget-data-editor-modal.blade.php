<div>
    <x-modal wire:model="showModal" maxWidth="2xl">
        <x-slot name="title">
            @if($contentId && $currentView === 'edit')
                Edit Widget Content
            @elseif(!$contentId && $currentView === 'edit')
                Create New Widget Content
            @else
                Select Widget Content for Zone
            @endif
        </x-slot>
    
        <x-slot name="content">
            @if($zoneId) {{-- Show tabs only when launched for a zone --}}
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button wire:click="switchToEditView" 
                            class="{{ $currentView === 'edit' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                        {{ $contentId && $currentView === 'edit' ? 'Edit Current' : 'Create New' }}
                    </button>
                    <button wire:click="switchToSelectView"
                            class="{{ $currentView === 'select' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                        Select Existing
                    </button>
                </nav>
            </div>
            @endif

            {{-- Edit/Create View --}}
            <div x-data="{ show: @entangle('currentView').live === 'edit' }" x-show="show" x-transition>
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
                    @elseif($widgetType == 'WeatherWidget')
                        <div wire:key="weather-widget-editor" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Weather Widget Settings</h3>
                            <div>
                                <label for="weatherTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Widget Title</label>
                                <input type="text" wire:model.defer="widgetData.data.title" id="weatherTitle" placeholder="e.g., Local Weather" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>
                            <div>
                                <label for="weatherLocation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                <input type="text" wire:model.defer="widgetData.data.location" id="weatherLocation" placeholder="e.g., London, UK" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>
                            <div>
                                <label for="weatherApiKey" class="block text-sm font-medium text-gray-700 dark:text-gray-300">OpenWeatherMap API Key</label>
                                <input type="text" wire:model.defer="widgetData.data.apiKey" id="weatherApiKey" placeholder="Enter your API key" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Note: API key handling might be centralized in the future.</p>
                            </div>
                        </div>
                
                    @elseif($widgetType == 'ClockWidget')
                        <div wire:key="clock-widget-editor" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Clock Widget Settings</h3>
                            <div>
                                <label for="clockTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Widget Title</label>
                                <input type="text" wire:model.defer="widgetData.data.title" id="clockTitle" placeholder="e.g., Current Time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>
                            <div>
                                <label for="clockTimezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timezone</label>
                                <input type="text" wire:model.defer="widgetData.data.timezone" id="clockTimezone" placeholder="e.g., Europe/London, America/New_York" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                 <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Full list at <a href="https://www.php.net/manual/en/timezones.php" target="_blank" class="text-indigo-500 hover:underline">PHP Timezones</a>.</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="clockFormat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time Format</label>
                                    <input type="text" wire:model.defer="widgetData.data.format" id="clockFormat" placeholder="H:i:s or h:i:s A" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                </div>
                                <div>
                                    <label for="clockDateFormat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date Format</label>
                                    <input type="text" wire:model.defer="widgetData.data.dateFormat" id="clockDateFormat" placeholder="l, F jS, Y" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.defer="widgetData.data.showSeconds" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Seconds</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.defer="widgetData.data.showDate" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Date</span>
                                </label>
                            </div>
                        </div>
                
                    @elseif($widgetType == 'AnnouncementWidget')
                        <div wire:key="announcement-widget-editor" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Announcement Widget Settings</h3>
                            <div>
                                <label for="announcementTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                <input type="text" wire:model.defer="widgetData.data.title" id="announcementTitle" placeholder="Enter announcement title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>
                            <div>
                                <label for="announcementMessage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                                <textarea wire:model.defer="widgetData.data.message" id="announcementMessage" rows="4" placeholder="Enter announcement message" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100"></textarea>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                 <div>
                                     <label for="announcementBgColor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Background Color</label>
                                     <input type="color" wire:model.defer="widgetData.data.backgroundColor" id="announcementBgColor" class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500">
                                 </div>
                                 <div>
                                     <label for="announcementTextColor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Text Color</label>
                                     <input type="color" wire:model.defer="widgetData.data.textColor" id="announcementTextColor" class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500">
                                 </div>
                                 <div>
                                     <label for="announcementTitleColor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title Color</label>
                                     <input type="color" wire:model.defer="widgetData.data.titleColor" id="announcementTitleColor" class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500">
                                 </div>
                            </div>
                        </div>
                
                    @elseif($widgetType == 'RssFeedWidget')
                        <div wire:key="rssfeed-widget-editor" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">RSS Feed Widget Settings</h3>
                            <div>
                                <label for="rssTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Widget Title</label>
                                <input type="text" wire:model.defer="widgetData.data.title" id="rssTitle" placeholder="e.g., Latest News" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>
                            <div>
                                <label for="rssFeedUrl" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Feed URL</label>
                                <input type="url" wire:model.defer="widgetData.data.feedUrl" id="rssFeedUrl" placeholder="https://www.example.com/feed.xml" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>
                            <div>
                                <label for="rssItemCount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Items to Display</label>
                                <input type="number" wire:model.defer="widgetData.data.itemCount" id="rssItemCount" min="1" max="20" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>
                        </div>
                
                    @elseif($widgetType == 'CalendarWidget')
                        <div wire:key="calendar-widget-editor" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Calendar Widget Settings</h3>
                            <div>
                                <label for="calendarTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Widget Title</label>
                                <input type="text" wire:model.defer="widgetData.data.title" id="calendarTitle" placeholder="e.g., Upcoming Events" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>
                            <div>
                                <label for="calendarUrl" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Calendar URL (iCal format)</label>
                                <input type="url" wire:model.defer="widgetData.data.calendarUrl" id="calendarUrl" placeholder="https://www.example.com/calendar.ics" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-100">
                            </div>
                            {{-- Add other calendar-specific settings here if needed, e.g., number of events, view type (month/week/day) --}}
                        </div>
    
                    @elseif($widgetType)
                        {{-- Placeholder for other widget types --}}
                        <div class="mt-4 p-4 border rounded-md bg-gray-50 dark:bg-gray-700">
                            <p class="text-gray-700 dark:text-gray-300">Widget Type: <strong>{{ $widgetType }}</strong></p>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Specific data editor for this widget type is not yet implemented.
                                You can edit the raw JSON data below if needed.
                            </p>
                            <textarea wire:model="widgetData.data" rows="10"
                                      class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100"
                                      placeholder="Enter JSON data for this widget's 'data' key"></textarea>
                            @error('widgetData.data') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    @else
                         <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">No widget type selected or recognized.</p>
                    @endif
    
                </div>
            </form>
            </div>

            {{-- Select Existing View --}}
            <div x-data="{ show: @entangle('currentView').live === 'select' }" x-show="show" x-transition>
                <input type="text" wire:model.live.debounce.300ms="searchTerm" 
                       placeholder="Search existing {{ $widgetType }} content by name..." 
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 mb-4">
                
                @if(!empty($existingContents) && count($existingContents) > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto rounded-md border dark:border-gray-600">
                        @foreach($existingContents as $existingContent)
                            <li wire:key="existing-{{ $existingContent->id }}" 
                                class="py-3 px-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer"
                                wire:click="selectExistingContent({{ $existingContent->id }})">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $existingContent->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Type: {{ $existingContent->content_data['widget_type'] ?? 'Unknown' }} | 
                                        Updated: {{ $existingContent->updated_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <button type="button" 
                                        class="ml-3 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Select
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 dark:text-gray-400 py-4 text-center">
                        @if(empty($searchTerm))
                            No existing content found for type '{{ $widgetType }}'. You can create new content in the tab above.
                        @else
                            No existing content found for '{{ $searchTerm }}' of type '{{ $widgetType }}'.
                        @endif
                    </p>
                @endif
            </div>
        </x-slot>
    
        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <button wire:click="closeModal" type="button"
                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                    Cancel
                </button>
                @if($currentView === 'edit')
                    <button wire:click="save" type="button"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ $contentId ? 'Save Changes' : 'Create Content' }}
                    </button>
                @endif
            </div>
        </x-slot>
    </x-modal>
</div>
