<div>
    <div class="min-h-screen p-4 grid grid-cols-12 gap-4 auto-rows-min">
        <!-- Dashboard Header -->
        <header class="col-span-12 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Digital Signage Display</h1>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Last updated: <span class="font-medium">{{ now()->format('g:i A') }}</span>
            </div>
        </header>

        <!-- Weather Widget -->
        @if (isset($activeWidgets['weather']))
            <div class="col-span-4 row-span-2">
                @livewire('content.widgets.weather-widget', [
                    'apiKey' => $activeWidgets['weather']['settings']['api_key'],
                    'location' => $activeWidgets['weather']['settings']['location'],
                    'key' => 'dashboard-weather',
                ])
            </div>
        @endif

        <!-- Announcement Widget -->
        @if (isset($activeWidgets['announcements']))
            <div class="col-span-8">
                @livewire('content.widgets.announcement-widget', [
                    'settings' => $activeWidgets['announcements']['settings'],
                    'key' => 'dashboard-announcements',
                ])
            </div>
        @endif

        <!-- News Widget -->
        @if (isset($activeWidgets['news']))
            <div class="col-span-4">
                @livewire('content.widgets.news-widget', [
                    'settings' => $activeWidgets['news']['settings'],
                    'key' => 'dashboard-news',
                ])
            </div>
        @endif

        <!-- Social Media Widget -->
        @if (isset($activeWidgets['social']))
            <div class="col-span-4">
                @livewire('content.widgets.social-widget', [
                    'settings' => $activeWidgets['social']['settings'],
                    'key' => 'dashboard-social',
                ])
            </div>
        @endif
    </div>
</div>
