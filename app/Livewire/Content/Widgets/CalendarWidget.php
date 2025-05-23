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
        $today = now();
        $this->events = [
            [
                'title' => 'Daily Standup',
                'start' => $today->copy()->setTime(9, 0)->format('Y-m-d H:i:s'),
                'end' => $today->copy()->setTime(9, 15)->format('Y-m-d H:i:s'),
                'location' => 'Virtual - Zoom',
                'source' => 'google',
                'recurring' => 'daily'
            ],
            [
                'title' => 'Team Sync: Project Phoenix',
                'start' => $today->copy()->addHours(2)->format('Y-m-d H:i:s'),
                'end' => $today->copy()->addHours(3)->format('Y-m-d H:i:s'),
                'location' => 'Conference Room B',
                'source' => 'outlook'
            ],
            [
                'title' => 'Client Demo - New Features',
                'start' => $today->copy()->addDays(1)->setTime(14, 0)->format('Y-m-d H:i:s'),
                'end' => $today->copy()->addDays(1)->setTime(15, 30)->format('Y-m-d H:i:s'),
                'location' => 'Client HQ / Online',
                'source' => 'google'
            ],
            [
                'title' => 'Marketing Strategy Session',
                'start' => $today->copy()->addDays(2)->setTime(10, 0)->format('Y-m-d H:i:s'),
                'end' => $today->copy()->addDays(2)->setTime(12, 0)->format('Y-m-d H:i:s'),
                'location' => 'Marketing Dept.',
                'source' => 'outlook'
            ],
            [
                'title' => 'Company All-Hands',
                'start' => $today->copy()->addDays(3)->setTime(16, 0)->format('Y-m-d H:i:s'),
                'end' => $today->copy()->addDays(3)->setTime(17, 0)->format('Y-m-d H:i:s'),
                'location' => 'Auditorium / Livestream',
                'source' => 'google'
            ],
            [
                'title' => 'Yoga & Wellness Break',
                'start' => $today->copy()->addDays(4)->setTime(12, 30)->format('Y-m-d H:i:s'),
                'end' => $today->copy()->addDays(4)->setTime(13, 0)->format('Y-m-d H:i:s'),
                'location' => 'Wellness Room',
                'source' => 'other' // Example of a non-Google/Outlook source
            ],
            [
                'title' => 'Tech Conference 2024',
                'start' => $today->copy()->addDays(7)->setTime(9,0)->format('Y-m-d H:i:s'),
                'end' => $today->copy()->addDays(9)->setTime(17,0)->format('Y-m-d H:i:s'), // Multi-day event
                'location' => 'Convention Center',
                'source' => 'google'
            ],
             [
                'title' => 'Past Event Example',
                'start' => $today->copy()->subDays(5)->setTime(10,0)->format('Y-m-d H:i:s'),
                'end' => $today->copy()->subDays(5)->setTime(11,0)->format('Y-m-d H:i:s'),
                'location' => 'Archive Room',
                'source' => 'outlook'
            ]
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
            
            $dayEvents = array_filter($this->events, function($event) use ($currentDateString) {
                $eventStartDate = Carbon::parse($event['start'])->format('Y-m-d');
                $eventEndDate = Carbon::parse($event['end'])->format('Y-m-d');
                // Check if the event occurs on, starts on, or spans across the current date
                return ($currentDateString >= $eventStartDate && $currentDateString <= $eventEndDate);
            });

            $week[] = [
                'day' => $day,
                'date' => $currentDateString,
                'isToday' => $currentDateString === $todayDate,
                'events' => array_values($dayEvents) // Re-index array
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

        if (!empty($week)) { // Add the last week if it's not full but has days
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