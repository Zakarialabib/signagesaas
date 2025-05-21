<?php

declare(strict_types=1);

namespace App\Services;

use App\Tenant\Models\Tenant;
use Illuminate\Support\Facades\DB;

final readonly class TenantMetricsService
{
    public function getMetrics(Tenant $tenant): array
    {
        return $tenant->run(function () {
            return [
                'total_devices'   => $this->getTotalDevices(),
                'active_devices'  => $this->getActiveDevices(),
                'storage_usage'   => $this->getStorageUsage(),
                'total_screens'   => $this->getTotalScreens(),
                'active_screens'  => $this->getActiveScreens(),
                'recent_activity' => $this->getRecentActivity(),
            ];
        });
    }

    private function getTotalDevices(): int
    {
        return DB::table('devices')->count();
    }

    private function getActiveDevices(): int
    {
        return DB::table('devices')
            ->where('last_ping_at', '>=', now()->subMinutes(5))
            ->count();
    }

    private function getStorageUsage(): array
    {
        $usage = DB::table('media')->sum('size');
        $limit = config('tenant.storage_limit', 10 * 1024 * 1024 * 1024); // 10GB default

        return [
            'used'       => $usage,
            'limit'      => $limit,
            'percentage' => $limit > 0 ? ($usage / $limit) * 100 : 0,
        ];
    }

    private function getTotalScreens(): int
    {
        return DB::table('screens')->count();
    }

    private function getActiveScreens(): int
    {
        return DB::table('screens')
            ->where('is_active', true)
            ->count();
    }

    private function getRecentActivity(): array
    {
        return DB::table('activity_log')
            ->select(['description', 'created_at', 'causer_type', 'causer_id', 'subject_type', 'subject_id'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->toArray();
    }
}
