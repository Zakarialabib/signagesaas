<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Attributes\Locked;
use Carbon\Carbon;
use App\Tenant\Models\Content;
use Exception;

 // If events might be loaded from a Content model in the future

final class CalendarWidget extends BaseWidget
{
    #[Locked]
    public array $events = [];

    #[Locked]
    public string $lastUpdated;

    #[Locked]
    public string $currentMonth;

    #[Locked]
    public array $calendar = [];

    public int $refreshInterval = 300; // 5 minutes
    public string $view = 'month'; // month, week, day
    public array $calendarSources = ['google', 'outlook']; // Calendar sources to fetch from
    public array $apiKeys = [];

    public ?string $contentId = null; // For loading dynamic events from a Content model

    // Configurable settings
    public string $startOfWeek = 'monday'; // 'sunday' or 'monday'
    public int $eventDisplayLimit = 3; // Max events to show per day in month view before a "+X more" link
    public bool $showRemindersOption = true; // Visual toggle in settings
    public string $defaultDate; // YYYY-MM-DD, for initial display month/year
    public string $displayTimeZone = 'UTC'; // Timezone for displaying event times

    // Internal state for calendar navigation
    public int $currentYear;
    public int $currentDay; // For daily or weekly views

    public string $widgetTitle = 'Company Events Calendar';

    #[Locked]
    public array $availableViews = [
        'month-view' => [
            'name'      => 'Month View',
            'view_path' => 'livewire.content.widgets.calendar-templates.month-view',
        ],
        'week-view' => [
            'name'      => 'Week View',
            'view_path' => 'livewire.content.widgets.calendar-templates.week-view',
        ],
        'list-view' => [
            'name'      => 'Upcoming List',
            'view_path' => 'livewire.content.widgets.calendar-templates.list-view',
        ],
    ];
    public string $activeView = 'month-view'; // Default view key

    public function mount(
        array $settings = [],
        string $title = 'Calendar Widget', // BaseWidget title
        string $category = 'CALENDAR', // BaseWidget category
        string $icon = 'heroicon-o-calendar-days', // BaseWidget icon
        array $initialData = [],      // Fallback data for events
        ?string $contentId = null
    ): void {
        parent::mount($settings, $title, $category, $icon);

        $this->contentId = $contentId;
        $this->defaultDate = $settings['default_date'] ?? Carbon::now()->toDateString();
        $this->displayTimeZone = $settings['display_time_zone'] ?? config('app.timezone', 'UTC');

        $initialCarbonDate = Carbon::parse($this->defaultDate, $this->displayTimeZone);
        $this->currentYear = $initialCarbonDate->year;
        $this->currentMonth = (string) $initialCarbonDate->month;
        $this->currentDay = $initialCarbonDate->day;

        $this->activeView = $settings['default_view'] ?? 'month-view';

        if ($this->contentId) {
            $contentModel = Content::find($this->contentId);

            if ($contentModel && isset($contentModel->content_data['widget_type']) && $contentModel->content_data['widget_type'] === 'CalendarWidget') {
                $widgetDataSource = $contentModel->content_data['data'] ?? [];
                $this->events = $widgetDataSource['events'] ?? [];
                $this->widgetTitle = $widgetDataSource['title'] ?? $contentModel->name; // Use content name as fallback
            } else {
                $this->loadPlaceholderData();
                $this->error ??= "Content ID {$this->contentId} not found or not a CalendarWidget. Using placeholders.";
            }
        } elseif ( ! empty($initialData['events'])) {
            $this->events = $initialData['events'] ?? [];
            $this->widgetTitle = $initialData['title'] ?? 'Upcoming Events';

            if (isset($initialData['active_view']) && array_key_exists($initialData['active_view'], $this->availableViews)) {
                $this->activeView = $initialData['active_view'];
            }
        } else {
            $this->loadPlaceholderData();
        }

        $this->applySettings($settings);
        $this->processEventsForView();
    }

    protected function applySettings(array $settings): void
    {
        $this->startOfWeek = $settings['start_of_week'] ?? $this->startOfWeek;
        $this->eventDisplayLimit = (int) ($settings['event_display_limit'] ?? $this->eventDisplayLimit);
        $this->showRemindersOption = $settings['show_reminders_option'] ?? $this->showRemindersOption;
        $this->refreshInterval = (int) ($settings['refresh_interval'] ?? $this->refreshInterval);
        $this->displayTimeZone = $settings['display_time_zone'] ?? $this->displayTimeZone;

        if ($this->title === 'Calendar Widget' && isset($settings['title'])) {
            $this->title = $settings['title']; // BaseWidget's title
        }

        if (isset($settings['widget_title'])) {
            $this->widgetTitle = $settings['widget_title'];
        } elseif (empty($this->widgetTitle) || $this->widgetTitle === 'Upcoming Events') {
            $this->widgetTitle = 'Company Events Calendar';
        }

        if (isset($settings['active_view']) && array_key_exists($settings['active_view'], $this->availableViews)) {
            $this->activeView = $settings['active_view'];
        }

        if (isset($settings['default_date'])) {
            $newDefaultDate = Carbon::parse($settings['default_date'], $this->displayTimeZone);
            $this->currentYear = $newDefaultDate->year;
            $this->currentMonth = (string) $newDefaultDate->month;
            $this->currentDay = $newDefaultDate->day;
        }
        $this->processEventsForView();
    }

    protected function loadData(): void
    {
        if (empty($this->calendarSources)) {
            throw new Exception('Calendar widget: No calendar sources selected.');
        }

        // Replace with your actual calendar API integration
        // Example implementation would fetch from multiple calendar sources:
        /*
        try {
            $events = [];
            foreach ($this->calendarSources as $source) {
                if (empty($this->apiKeys[$source])) {
                    continue;
                }

                switch ($source) {
                    case 'google':
                        // Google Calendar API integration
                        $client = new \Google_Client();
                        $client->setApplicationName('Digital Signage');
                        $client->setScopes([\Google_Service_Calendar::CALENDAR_READONLY]);
                        $client->setAuthConfig($this->apiKeys[$source]);

                        $service = new \Google_Service_Calendar($client);
                        $calendarId = 'primary';
                        $optParams = [
                            'maxResults' => 10,
                            'orderBy' => 'startTime',
                            'singleEvents' => true,
                            'timeMin' => date('c'),
                        ];

                        $results = $service->events->listEvents($calendarId, $optParams);
                        foreach ($results->getItems() as $event) {
                            $events[] = [
                                'title' => $event->getSummary(),
                                'start' => $event->getStart()->getDateTime(),
                                'end' => $event->getEnd()->getDateTime(),
                                'location' => $event->getLocation(),
                                'source' => 'google',
                            ];
                        }
                        break;

                    case 'outlook':
                        // Microsoft Graph API integration for Outlook calendar
                        break;
                }
            }
            $this->events = $events;
        } catch (\Exception $e) {
            throw new \Exception('Error fetching calendar events: ' . $e->getMessage());
        }
        */

        // Placeholder / Demo data
        $today = now();
        $this->events = [
            [
                'title'     => 'Daily Standup',
                'start'     => $today->copy()->setTime(9, 0)->format('Y-m-d H:i:s'),
                'end'       => $today->copy()->setTime(9, 15)->format('Y-m-d H:i:s'),
                'location'  => 'Virtual - Zoom',
                'source'    => 'google',
                'recurring' => 'daily',
            ],
            [
                'title'    => 'Team Sync: Project Phoenix',
                'start'    => $today->copy()->addHours(2)->format('Y-m-d H:i:s'),
                'end'      => $today->copy()->addHours(3)->format('Y-m-d H:i:s'),
                'location' => 'Conference Room B',
                'source'   => 'outlook',
            ],
            [
                'title'    => 'Client Demo - New Features',
                'start'    => $today->copy()->addDays(1)->setTime(14, 0)->format('Y-m-d H:i:s'),
                'end'      => $today->copy()->addDays(1)->setTime(15, 30)->format('Y-m-d H:i:s'),
                'location' => 'Client HQ / Online',
                'source'   => 'google',
            ],
            [
                'title'    => 'Marketing Strategy Session',
                'start'    => $today->copy()->addDays(2)->setTime(10, 0)->format('Y-m-d H:i:s'),
                'end'      => $today->copy()->addDays(2)->setTime(12, 0)->format('Y-m-d H:i:s'),
                'location' => 'Marketing Dept.',
                'source'   => 'outlook',
            ],
            [
                'title'    => 'Company All-Hands',
                'start'    => $today->copy()->addDays(3)->setTime(16, 0)->format('Y-m-d H:i:s'),
                'end'      => $today->copy()->addDays(3)->setTime(17, 0)->format('Y-m-d H:i:s'),
                'location' => 'Auditorium / Livestream',
                'source'   => 'google',
            ],
            [
                'title'    => 'Yoga & Wellness Break',
                'start'    => $today->copy()->addDays(4)->setTime(12, 30)->format('Y-m-d H:i:s'),
                'end'      => $today->copy()->addDays(4)->setTime(13, 0)->format('Y-m-d H:i:s'),
                'location' => 'Wellness Room',
                'source'   => 'other', // Example of a non-Google/Outlook source
            ],
            [
                'title'    => 'Tech Conference 2024',
                'start'    => $today->copy()->addDays(7)->setTime(9, 0)->format('Y-m-d H:i:s'),
                'end'      => $today->copy()->addDays(9)->setTime(17, 0)->format('Y-m-d H:i:s'), // Multi-day event
                'location' => 'Convention Center',
                'source'   => 'google',
            ],
            [
                'title'    => 'Past Event Example',
                'start'    => $today->copy()->subDays(5)->setTime(10, 0)->format('Y-m-d H:i:s'),
                'end'      => $today->copy()->subDays(5)->setTime(11, 0)->format('Y-m-d H:i:s'),
                'location' => 'Archive Room',
                'source'   => 'outlook',
            ],
        ];

        // Sort events by start time for upcoming events list
        usort($this->events, function ($a, $b) {
            return strtotime($a['start']) - strtotime($b['start']);
        });

        // Generate calendar grid for the current month
        $this->currentMonth = $today->format('F Y');
        $this->generateCalendarGrid($today);

        $this->lastUpdated = $today->diffForHumans();
    }

    protected function generateCalendarGrid(Carbon $referenceDate): void
    {
        $date = $referenceDate->copy()->startOfMonth();
        $daysInMonth = $date->daysInMonth;
        $startOfMonth = $date->copy()->startOfMonth();
        // $endOfMonth = $date->copy()->endOfMonth(); // Not strictly needed for grid generation logic below

        $calendar = [];
        $week = [];
        $todayDate = Carbon::today()->format('Y-m-d');

        // Add empty days for the start of the month
        // Carbon dayOfWeek returns 0 for Sunday, 1 for Monday... 6 for Saturday.
        // We want Sunday as the first day of the week (index 0 in our $week array).
        $daysToPadAtStart = $startOfMonth->dayOfWeek; // If Sunday is 0, this is correct.

        // If your Carbon setup has Monday as 1 and Sunday as 7, adjust: ($startOfMonth->dayOfWeek == 7 ? 0 : $startOfMonth->dayOfWeek)
        for ($i = 0; $i < $daysToPadAtStart; $i++) {
            $week[] = null;
        }

        // Add all days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = $startOfMonth->copy()->addDays($day - 1);
            $currentDateString = $currentDate->format('Y-m-d');

            $dayEvents = array_filter($this->events, function ($event) use ($currentDateString) {
                $eventStartDate = Carbon::parse($event['start'])->format('Y-m-d');
                $eventEndDate = Carbon::parse($event['end'])->format('Y-m-d');

                // Check if the event occurs on, starts on, or spans across the current date
                return ($currentDateString >= $eventStartDate && $currentDateString <= $eventEndDate);
            });

            $week[] = [
                'day'     => $day,
                'date'    => $currentDateString,
                'isToday' => $currentDateString === $todayDate,
                'events'  => array_values($dayEvents), // Re-index array
            ];

            if (count($week) === 7) {
                $calendar[] = $week;
                $week = [];
            }
        }

        // Add empty days for the end of the month
        while (count($week) > 0 && count($week) < 7) {
            $week[] = null;
        }

        if ( ! empty($week)) { // Add the last week if it's not full but has days
            $calendar[] = $week;
        }

        $this->calendar = $calendar;
    }

    protected function loadPlaceholderData(): void
    {
        $today = Carbon::now($this->displayTimeZone);
        $this->events = [
            [
                'id'          => 'evt_001',
                'title'       => 'Team All-Hands Meeting',
                'start'       => $today->copy()->setHour(10)->setMinute(0)->setSecond(0)->toDateTimeString(),
                'end'         => $today->copy()->setHour(11)->setMinute(30)->setSecond(0)->toDateTimeString(),
                'description' => 'Quarterly all-hands meeting to discuss company progress and future goals. All team members are encouraged to attend.',
                'location'    => 'Main Conference Hall / Zoom Link',
                'category'    => 'Meeting',
                'color'       => 'blue',
                'attendees'   => ['John Doe', 'Jane Smith', 'Alice Brown'],
                'isFullDay'   => false,
            ],
            [
                'id'          => 'evt_002',
                'title'       => 'Project Phoenix Deadline',
                'start'       => $today->copy()->addDays(2)->startOfDay()->toDateTimeString(),
                'end'         => $today->copy()->addDays(2)->endOfDay()->toDateTimeString(),
                'description' => 'Final submission deadline for Project Phoenix. Ensure all deliverables are uploaded to the project portal.',
                'location'    => 'Online Portal',
                'category'    => 'Deadline',
                'color'       => 'red',
                'isFullDay'   => true,
            ],
            [
                'id'          => 'evt_003',
                'title'       => 'Yoga & Wellness Session',
                'start'       => $today->copy()->addDays(3)->setHour(17)->setMinute(0)->toDateTimeString(),
                'end'         => $today->copy()->addDays(3)->setHour(18)->setMinute(0)->toDateTimeString(),
                'description' => 'Relax and rejuvenate with our weekly yoga session. Mats provided.',
                'location'    => 'Gym Room B',
                'category'    => 'Wellness',
                'color'       => 'green',
                'isFullDay'   => false,
            ],
            [
                'id'          => 'evt_004',
                'title'       => 'Client Workshop: Digital Strategy',
                'start'       => $today->copy()->addDays(5)->setHour(14)->setMinute(0)->toDateTimeString(),
                'end'         => $today->copy()->addDays(5)->setHour(16)->setMinute(30)->toDateTimeString(),
                'description' => 'Interactive workshop with Acme Corp to define their new digital strategy.',
                'location'    => 'Client Offices & Virtual',
                'category'    => 'Workshop',
                'color'       => 'purple',
                'isFullDay'   => false,
            ],
            [
                'id'          => 'evt_005',
                'title'       => 'Company Anniversary Celebration',
                'start'       => $today->copy()->addWeeks(2)->setHour(19)->setMinute(0)->toDateTimeString(),
                'end'         => $today->copy()->addWeeks(2)->setHour(23)->setMinute(0)->toDateTimeString(),
                'description' => 'Join us for an evening of celebration for our 10th company anniversary! Food, drinks, and entertainment.',
                'location'    => 'The Grand Ballroom',
                'category'    => 'Social Event',
                'color'       => 'amber',
                'isFullDay'   => false,
            ],
            [
                'id'          => 'evt_006',
                'title'       => 'Maintenance Window',
                'start'       => $today->copy()->addMonth()->startOfMonth()->addDays(5)->setHour(2)->setMinute(0)->toDateTimeString(),
                'end'         => $today->copy()->addMonth()->startOfMonth()->addDays(5)->setHour(6)->setMinute(0)->toDateTimeString(),
                'description' => 'Scheduled server maintenance. Brief outages may occur.',
                'location'    => 'Data Center',
                'category'    => 'Technical',
                'color'       => 'gray',
                'isFullDay'   => false,
            ],
            [
                'id'          => 'evt_007',
                'title'       => 'Marketing Campaign Launch',
                'start'       => $today->copy()->subDays(3)->startOfDay()->toDateTimeString(), // An event from the past
                'end'         => $today->copy()->subDays(3)->endOfDay()->toDateTimeString(),
                'description' => 'Launch of the new Spring marketing campaign across all channels.',
                'location'    => 'N/A',
                'category'    => 'Marketing',
                'color'       => 'pink',
                'isFullDay'   => true,
            ],
        ];
        $this->widgetTitle = 'Company Events Calendar';
    }

    protected function processEventsForView(): void
    {
        // This is where you might filter or re-format events based on $this->activeView,
        // $this->currentYear, $this->currentMonth, etc.
        // For example, for a list view, you might sort upcoming events.
        // For a month view, you'd group them by day.
        // For now, the raw events array is passed.
        // The actual filtering/display logic will be primarily in the specific Blade templates.
    }

    // Methods for calendar navigation (to be called from Blade templates via wire:click)
    public function goToNextMonth(): void
    {
        $newDate = Carbon::create((int) $this->currentYear, (int) $this->currentMonth, 1, 0, 0, 0, $this->displayTimeZone)->addMonth();
        $this->currentYear = $newDate->year;
        $this->currentMonth = (string) $newDate->month;
        $this->processEventsForView();
    }

    public function goToPreviousMonth(): void
    {
        $newDate = Carbon::create((int) $this->currentYear, (int) $this->currentMonth, 1, 0, 0, 0, $this->displayTimeZone)->subMonth();
        $this->currentYear = $newDate->year;
        $this->currentMonth = (string) $newDate->month;
        $this->processEventsForView();
    }

    public function goToDate(string $date): void // YYYY-MM-DD
    {
        try {
            $newDate = Carbon::parse($date, $this->displayTimeZone);
            $this->currentYear = $newDate->year;
            $this->currentMonth = (string) $newDate->month;
            $this->currentDay = $newDate->day;
            $this->processEventsForView();
        } catch (Exception $e) {
            // Log error or handle invalid date string
            $this->error = 'Invalid date format provided for navigation.';
        }
    }

    public function setView(string $viewKey): void
    {
        if (array_key_exists($viewKey, $this->availableViews)) {
            $this->activeView = $viewKey;
            $this->processEventsForView(); // Re-process events if view changes behavior
        }
    }

    protected function getViewData(): array
    {
        return [
            'widgetId'    => $this->getId(),
            'title'       => $this->title, // BaseWidget title
            'widgetTitle' => $this->widgetTitle,
            'events'      => $this->events,
            'error'       => $this->error,
            'isLoading'   => $this->isLoading,
            // Calendar specific settings & state
            'startOfWeek'         => $this->startOfWeek,
            'eventDisplayLimit'   => $this->eventDisplayLimit,
            'showRemindersOption' => $this->showRemindersOption,
            'currentYear'         => (int) $this->currentYear,
            'currentMonth'        => (int) $this->currentMonth,
            'currentDay'          => (int) $this->currentDay,
            'displayTimeZone'     => $this->displayTimeZone,
            // For the main wrapper view
            'availableViews' => $this->availableViews,
            'activeView'     => $this->activeView,
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.calendar-widget', $this->getViewData());
    }
}
