<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Tenant\Models\Analytics\BandwidthLog;
use App\Tenant\Models\Analytics\ContentPlayLog;
use App\Tenant\Models\Analytics\DeviceUsageLog;
use App\Tenant\Models\Analytics\UserActivityLog;
use App\Tenant\Models\Device;
use App\Tenant\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
final class UsageAnalytics extends Component
{
    // Filter properties
    public string $dateRange = 'last7days';
    public ?string $selectedDeviceId = null;
    public ?string $selectedUserId = null;

    // Date range options
    public array $dateRangeOptions = [
        'today'      => 'Today',
        'yesterday'  => 'Yesterday',
        'last7days'  => 'Last 7 Days',
        'last30days' => 'Last 30 Days',
        'thisMonth'  => 'This Month',
        'lastMonth'  => 'Last Month',
        'custom'     => 'Custom Range',
    ];

    // Custom date range
    public ?string $startDate = null;
    public ?string $endDate = null;

    // Collections for dropdowns
    public Collection $devices;
    public Collection $users;

    // Data collections for charts and stats
    public Collection $deviceUsageData;
    public Collection $contentPlayData;
    public Collection $bandwidthData;
    public Collection $userActivityData;

    // Summary statistics
    public int $totalDeviceUsageHours = 0;
    public int $totalContentPlays = 0;
    public int $totalBandwidthUsed = 0;
    public int $totalUserActions = 0;
    public float $deviceUptime = 0.0;

    // Chart data
    public array $deviceUsageChartData = [];
    public array $contentPlayChartData = [];
    public array $bandwidthChartData = [];
    public array $userActivityChartData = [];

    // Constructor
    public function mount(): void
    {
        // $this->authorize('viewAny', Auth::user());

        // Initialize collections
        $this->devices = Device::all(['id', 'name', 'type']);
        $this->users = User::all(['id', 'name', 'email']);

        // Set default date range (last 7 days)
        $this->updateDateRange();

        // Load initial data
        // $this->loadAnalyticsData();
    }

    // Date range methods
    public function updateDateRange(?string $range = null): void
    {
        if ($range) {
            $this->dateRange = $range;
        }

        $now = Carbon::now();

        switch ($this->dateRange) {
            case 'today':
                $this->startDate = $now->startOfDay()->toDateString();
                $this->endDate = $now->endOfDay()->toDateString();

                break;

            case 'yesterday':
                $this->startDate = $now->subDay()->startOfDay()->toDateString();
                $this->endDate = $now->endOfDay()->toDateString();

                break;

            case 'last7days':
                $this->startDate = $now->subDays(7)->startOfDay()->toDateString();
                $this->endDate = Carbon::now()->endOfDay()->toDateString();

                break;

            case 'last30days':
                $this->startDate = $now->subDays(30)->startOfDay()->toDateString();
                $this->endDate = Carbon::now()->endOfDay()->toDateString();

                break;

            case 'thisMonth':
                $this->startDate = $now->startOfMonth()->toDateString();
                $this->endDate = $now->endOfMonth()->toDateString();

                break;

            case 'lastMonth':
                $this->startDate = $now->subMonth()->startOfMonth()->toDateString();
                $this->endDate = $now->endOfMonth()->toDateString();

                break;

                // Custom range keeps existing values
        }
    }

    // Filter methods
    public function setDeviceFilter(?string $deviceId): void
    {
        $this->selectedDeviceId = $deviceId;
    }

    public function setUserFilter(?string $userId): void
    {
        $this->selectedUserId = $userId;
    }

    public function clearFilters(): void
    {
        $this->selectedDeviceId = null;
        $this->selectedUserId = null;
    }

    // Data loading
    public function filterAnalytics(): void
    {
        $this->loadAnalyticsData();
    }

    private function loadAnalyticsData(): void
    {
        // Load device usage data
        $deviceQuery = DeviceUsageLog::query()
            ->when($this->startDate && $this->endDate, function ($query) {
                return $query->forDateRange($this->startDate, $this->endDate);
            })
            ->when($this->selectedDeviceId, function ($query) {
                return $query->forDevice($this->selectedDeviceId);
            });

        $this->deviceUsageData = $deviceQuery
            ->select([
                'device_id',
                'event_type',
                DB::raw('SUM(duration_seconds) as total_duration'),
                DB::raw('COUNT(*) as event_count'),
            ])
            ->groupBy(['device_id', 'event_type'])
            ->get();

        // Calculate total device usage in hours
        $this->totalDeviceUsageHours = (int) round($deviceQuery->sum('duration_seconds') / 3600);

        // Load content play data
        $contentQuery = ContentPlayLog::query()
            ->when($this->startDate && $this->endDate, function ($query) {
                return $query->forDateRange($this->startDate, $this->endDate);
            })
            ->when($this->selectedDeviceId, function ($query) {
                return $query->forDevice($this->selectedDeviceId);
            });

        $this->contentPlayData = $contentQuery
            ->select([
                'content_id',
                DB::raw('SUM(duration_seconds) as total_duration'),
                DB::raw('COUNT(*) as play_count'),
            ])
            ->groupBy('content_id')
            ->get();

        $this->totalContentPlays = (int) $contentQuery->count();

        // Load bandwidth data
        $bandwidthQuery = BandwidthLog::query()
            ->when($this->startDate && $this->endDate, function ($query) {
                return $query->forDateRange($this->startDate, $this->endDate);
            })
            ->when($this->selectedDeviceId, function ($query) {
                return $query->forDevice($this->selectedDeviceId);
            })
            ->when($this->selectedUserId, function ($query) {
                return $query->forUser($this->selectedUserId);
            });

        $this->bandwidthData = $bandwidthQuery
            ->select([
                'direction',
                DB::raw('SUM(bytes_transferred) as total_bytes'),
                DB::raw('DATE(recorded_at) as date'),
            ])
            ->groupBy(['direction', DB::raw('DATE(recorded_at)')])
            ->orderBy('date')
            ->get();

        // Calculate total bandwidth in MB
        $this->totalBandwidthUsed = (int) round($bandwidthQuery->sum('bytes_transferred') / (1024 * 1024));

        // Load user activity data
        $activityQuery = UserActivityLog::query()
            ->when($this->startDate && $this->endDate, function ($query) {
                return $query->forDateRange($this->startDate, $this->endDate);
            })
            ->when($this->selectedUserId, function ($query) {
                return $query->forUser($this->selectedUserId);
            });

        $this->userActivityData = $activityQuery
            ->select([
                'action',
                DB::raw('COUNT(*) as action_count'),
                'user_id',
            ])
            ->groupBy(['action', 'user_id'])
            ->get();

        $this->totalUserActions = (int) $activityQuery->count();

        // Calculate device uptime if a specific device is selected
        if ($this->selectedDeviceId) {
            $totalPossibleTime = Carbon::parse($this->startDate)->diffInSeconds(Carbon::parse($this->endDate));

            if ($totalPossibleTime > 0) {
                $actualUptime = $deviceQuery->where('event_type', 'heartbeat')->sum('duration_seconds');
                $this->deviceUptime = round(($actualUptime / $totalPossibleTime) * 100, 2);
            }
        }

        // Prepare data for charts
        $this->prepareChartData();
    }

    private function prepareChartData(): void
    {
        // Device Usage Chart (Example: Hours per device)
        $this->deviceUsageChartData = $this->deviceUsageData
            ->groupBy('device_id')
            ->map(function ($group, $deviceId) {
                $device = $this->devices->firstWhere('id', $deviceId);

                return [
                    'label' => $device ? $device->name : 'Unknown Device',
                    'value' => round($group->sum('total_duration') / 3600, 1), // Hours
                ];
            })
            ->values()
            ->toArray();

        // Content Play Chart (Example: Plays per content)
        // Requires joining with Content model to get names - simplified here
        $this->contentPlayChartData = $this->contentPlayData
            ->map(function ($item) {
                return [
                    'label' => 'Content ID: '.$item->content_id, // Replace with actual content name later
                    'value' => $item->play_count,
                ];
            })
            ->sortByDesc('value')
            ->take(10) // Top 10 content
            ->values()
            ->toArray();

        // Bandwidth Chart (Example: Daily usage MB)
        $this->bandwidthChartData = $this->bandwidthData
            ->groupBy('date')
            ->map(function ($group, $date) {
                return [
                    'label' => Carbon::parse($date)->format('M d'),
                    'value' => round($group->sum('total_bytes') / (1024 * 1024), 2), // MB
                ];
            })
            ->values()
            ->toArray();

        // User Activity Chart (Example: Actions count)
        $this->userActivityChartData = $this->userActivityData
            ->groupBy('action')
            ->map(function ($group, $action) {
                return [
                    'label' => ucwords(str_replace('_', ' ', $action)),
                    'value' => $group->sum('action_count'),
                ];
            })
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.usage-analytics');
    }
}
