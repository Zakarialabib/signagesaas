<?php

declare(strict_types=1);

namespace App\Livewire\Tenant\Content;

use Livewire\Component;

class WidgetTypeSelector extends Component
{
    public array $availableWidgets = [];

    public function mount(): void
    {
        // Define metadata for base widget types that tenants can choose from.
        // This structure should be compatible with the props of the <x-base-demos> component.
        $this->availableWidgets = [
            [
                'widgetTypeIdentifier' => 'MenuWidget',
                'title'                => 'Digital Menu Board',
                'iconSvgPath'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />', // Example icon
                'description'          => 'Create and manage dynamic digital menu boards for restaurants, cafes, and food services.',
                'features'             => ['Easy item updates', 'Price management', 'Category organization', 'Showcase specials'],
                'category'             => 'MENU', // Matches TemplateCategory enum if possible, or a general one
                'themeColor'           => 'green',
                'gradientToColor'      => 'emerald',
                'industry'             => 'Food & Beverage',
                'useCases'             => ['Restaurant menus', 'Cafe specials boards', 'Food truck listings'],
            ],
            [
                'widgetTypeIdentifier' => 'RetailProductWidget',
                'title'                => 'Retail Product Showcase',
                'iconSvgPath'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />', // Example icon
                'description'          => 'Showcase your products with dynamic pricing, images, and promotional details.',
                'features'             => ['Product listings', 'Sale prices', 'Image galleries', 'Promo badges'],
                'category'             => 'RETAIL',
                'themeColor'           => 'blue',
                'gradientToColor'      => 'cyan',
                'industry'             => 'Retail & E-commerce',
                'useCases'             => ['In-store product displays', 'Featured items showcase', 'Promotional screens'],
            ],
            [
                'widgetTypeIdentifier' => 'WeatherWidget',
                'title'                => 'Weather Display',
                'iconSvgPath'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z" />',
                'description'          => 'Display current weather conditions and forecasts for any location.',
                'features'             => ['Real-time updates', 'Location search', 'Forecasts', 'Customizable units'],
                'category'             => 'WEATHER',
                'themeColor'           => 'sky',
                'gradientToColor'      => 'blue',
                'industry'             => 'General Information',
                'useCases'             => ['Lobby displays', 'Information kiosks', 'Event venues'],
            ],
            [
                'widgetTypeIdentifier' => 'ClockWidget',
                'title'                => 'Clock Display',
                'iconSvgPath'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />',
                'description'          => 'Show the current time and date, with various customization options.',
                'features'             => ['Analog/Digital styles', 'Timezone support', 'Date display', 'Custom formats'],
                'category'             => 'CLOCK',
                'themeColor'           => 'indigo',
                'gradientToColor'      => 'purple',
                'industry'             => 'General Information',
                'useCases'             => ['Office displays', 'Waiting rooms', 'Event schedules'],
            ],
            [
                'widgetTypeIdentifier' => 'AnnouncementWidget',
                'title'                => 'Announcements',
                'iconSvgPath'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />',
                'description'          => 'Display important announcements, messages, or alerts.',
                'features'             => ['Custom text', 'Background/text colors', 'Scheduling options'],
                'category'             => 'ANNOUNCEMENT',
                'themeColor'           => 'amber',
                'gradientToColor'      => 'yellow',
                'industry'             => 'Corporate, Education, Public Spaces',
                'useCases'             => ['Company news', 'Event alerts', 'Welcome messages'],
            ],
            [
                'widgetTypeIdentifier' => 'RSSWidget',
                'title'                => 'RSS Feed',
                'iconSvgPath'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M12.75 19.5v-.75a7.5 7.5 0 00-7.5-7.5H4.5m0-6.75h.75c7.87 0 14.25 6.38 14.25 14.25v.75M6 18.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />',
                'description'          => 'Stream content from any RSS feed to keep displays updated automatically.',
                'features'             => ['Multiple feed support', 'Customizable layout', 'Auto-refresh', 'Filtering options'],
                'category'             => 'RSS_FEED',
                'themeColor'           => 'orange',
                'gradientToColor'      => 'red',
                'industry'             => 'News, Corporate, Information',
                'useCases'             => ['Displaying news headlines', 'Company blog updates', 'Industry-specific news'],
            ],
            [
                'widgetTypeIdentifier' => 'CalendarWidget',
                'title'                => 'Calendar & Events',
                'iconSvgPath'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />',
                'description'          => 'Showcase upcoming events, schedules, and important dates.',
                'features'             => ['Multiple calendar views', 'Event details display', 'Google Calendar/iCal sync', 'Customizable design'],
                'category'             => 'CALENDAR',
                'themeColor'           => 'purple',
                'gradientToColor'      => 'violet',
                'industry'             => 'Corporate, Education, Event Management',
                'useCases'             => ['Meeting room schedules', 'School event calendars', 'Public event listings'],
            ],
        ];
    }

    public function selectWidgetType(string $widgetTypeIdentifier)
    {
        // contentId is null because we are creating new content.
        // zoneId is null because this content is not yet assigned to a specific zone.
        $this->dispatch('openWidgetDataEditor', widgetType: $widgetTypeIdentifier, contentId: null, zoneId: null)
            ->to('App.Livewire.Content.Widgets.WidgetDataEditorModal');

        // Assuming this selector might be in a modal, you might want to close it.
        // $this->dispatch('close-modal', ['id' => 'widget-type-selector-modal']); // Example
    }

    public function render()
    {
        return view('livewire.tenant.content.widget-type-selector');
    }
}
