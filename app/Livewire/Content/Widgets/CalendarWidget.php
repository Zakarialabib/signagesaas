<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Attributes\Locked;
use Carbon\Carbon;

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

    protected function loadData(): void
    {
        if (empty($this->calendarSources)) {
            throw new \Exception('Calendar widget: No calendar sources selected.');
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
        $this->events = [
            [
                'title' => 'Team Meeting',
                'start' => now()->addHours(2)->format('Y-m-d H:i:s'),
                'end' => now()->addHours(3)->format('Y-m-d H:i:s'),
                'location' => 'Conference Room A',
                'source' => 'google'
            ],
            [
                'title' => 'Project Review',
                'start' => now()->addDays(1)->format('Y-m-d H:i:s'),
                'end' => now()->addDays(1)->addHours(2)->format('Y-m-d H:i:s'),
                'location' => 'Virtual Meeting',
                'source' => 'outlook'
            ],
            [
                'title' => 'Client Presentation',
                'start' => now()->addDays(2)->format('Y-m-d H:i:s'),
                'end' => now()->addDays(2)->addHours(1)->format('Y-m-d H:i:s'),
                'location' => 'Main Office',
                'source' => 'google'
            ]
        ];

        // Generate calendar grid for the current month
        $this->currentMonth = now()->format('F Y');
        $this->generateCalendarGrid();

        $this->lastUpdated = now()->diffForHumans();
    }

    protected function generateCalendarGrid(): void
    {
        $date = Carbon::now()->startOfMonth();
        $daysInMonth = $date->daysInMonth;
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $calendar = [];
        $week = [];
        
        // Add empty days for the start of the month
        for ($i = 1; $i < $startOfMonth->dayOfWeek; $i++) {
            $week[] = null;
        }

        // Add all days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = $startOfMonth->copy()->addDays($day - 1);
            $week[] = [
                'day' => $day,
                'date' => $currentDate->format('Y-m-d'),
                'events' => array_filter($this->events, function($event) use ($currentDate) {
                    return Carbon::parse($event['start'])->format('Y-m-d') === $currentDate->format('Y-m-d');
                })
            ];

            if (count($week) === 7) {
                $calendar[] = $week;
                $week = [];
            }
        }

        // Add empty days for the end of the month
        while (count($week) < 7 && !empty($week)) {
            $week[] = null;
        }

        if (!empty($week)) {
            $calendar[] = $week;
        }

        $this->calendar = $calendar;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.calendar-widget', [
            'title' => 'Calendar',
            'category' => 'CALENDAR',
            'icon' => '<svg class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
            'events' => $this->events,
            'calendar' => $this->calendar,
            'currentMonth' => $this->currentMonth,
            'lastUpdated' => $this->lastUpdated,
            'error' => $this->error,
            'isLoading' => $this->isLoading,
        ]);
    }
} 