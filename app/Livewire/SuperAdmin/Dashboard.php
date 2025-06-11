<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use App\Tenant\Models\Device;
use App\Tenant\Models\Plan;
use App\Tenant\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;

#[Layout('layouts.super-admin')]
#[Title('SuperAdmin Dashboard')]
final class Dashboard extends Component
{
    public array $metrics = [];
    public array $recentActivity = [];
    public array $systemHealth = [];
    public array $tenantGrowth = [];
    public bool $autoRefresh = true;

    public function mount(): void
    {
        // Ensure the user is authenticated as a SuperAdmin
        if ( ! Auth::guard('superadmin')->check()) {
            $this->redirect(route('superadmin.login'));
        }
        
        $this->loadMetrics();
        $this->loadRecentActivity();
        $this->loadSystemHealth();
        $this->loadTenantGrowth();
    }

    public function refreshMetrics(): void
    {
        $this->loadMetrics();
        $this->loadRecentActivity();
        $this->loadSystemHealth();
        $this->dispatch('metrics-updated');
    }

    public function toggleAutoRefresh(): void
    {
        $this->autoRefresh = !$this->autoRefresh;
    }

    private function loadMetrics(): void
    {
        $this->metrics = Cache::remember('superadmin.dashboard.metrics', 300, function () {
            $totalTenants = Tenant::count();
            $activeTenants = Tenant::where('status', 'active')->count();
            $totalDevices = $this->getTotalDevicesAcrossAllTenants();
            $activeDevices = $this->getActiveDevicesAcrossAllTenants();
            $totalSubscriptions = $this->getTotalActiveSubscriptions();
            $monthlyRevenue = $this->getMonthlyRevenue();
            $systemUptime = $this->getSystemUptime();
            $apiRequestsToday = $this->getApiRequestsToday();

            return [
                'total_tenants' => $totalTenants,
                'active_tenants' => $activeTenants,
                'total_devices' => $totalDevices,
                'active_devices' => $activeDevices,
                'total_subscriptions' => $totalSubscriptions,
                'monthly_revenue' => $monthlyRevenue,
                'system_uptime' => $systemUptime,
                'api_requests_today' => $apiRequestsToday,
                'tenant_growth_rate' => $this->getTenantGrowthRate(),
                'device_utilization' => $totalDevices > 0 ? round(($activeDevices / $totalDevices) * 100, 1) : 0,
            ];
        });
    }

    private function loadRecentActivity(): void
    {
        $this->recentActivity = Cache::remember('superadmin.dashboard.activity', 300, function () {
            $recentTenants = Tenant::with('plan')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($tenant) {
                    return [
                        'type' => 'tenant_signup',
                        'tenant_name' => $tenant->name,
                        'tenant_domain' => $tenant->domain,
                        'plan_name' => $tenant->plan?->name ?? 'Free',
                        'created_at' => $tenant->created_at,
                        'status' => $tenant->status,
                    ];
                });

            return $recentTenants->toArray();
        });
    }

    private function loadSystemHealth(): void
    {
        $this->systemHealth = Cache::remember('superadmin.dashboard.health', 60, function () {
            return [
                'database' => $this->checkDatabaseHealth(),
                'redis' => $this->checkRedisHealth(),
                'storage' => $this->checkStorageHealth(),
                'queue' => $this->checkQueueHealth(),
            ];
        });
    }

    private function loadTenantGrowth(): void
    {
        $this->tenantGrowth = Cache::remember('superadmin.dashboard.growth', 3600, function () {
            $last30Days = collect(range(29, 0))->map(function ($daysAgo) {
                $date = Carbon::now()->subDays($daysAgo);
                $count = Tenant::whereDate('created_at', $date)->count();
                
                return [
                    'date' => $date->format('M j'),
                    'count' => $count,
                ];
            });

            return $last30Days->toArray();
        });
    }

    private function getTotalDevicesAcrossAllTenants(): int
    {
        $total = 0;
        $tenants = Tenant::all();
        
        foreach ($tenants as $tenant) {
            $tenant->run(function () use (&$total) {
                $total += Device::count();
            });
        }
        
        return $total;
    }

    private function getActiveDevicesAcrossAllTenants(): int
    {
        $active = 0;
        $tenants = Tenant::all();
        
        foreach ($tenants as $tenant) {
            $tenant->run(function () use (&$active) {
                $active += Device::where('status', 'online')
                    ->orWhere('last_seen_at', '>=', Carbon::now()->subMinutes(5))
                    ->count();
            });
        }
        
        return $active;
    }

    private function getTotalActiveSubscriptions(): int
    {
        $total = 0;
        $tenants = Tenant::all();
        
        foreach ($tenants as $tenant) {
            $tenant->run(function () use (&$total) {
                $total += Subscription::where('status', 'active')->count();
            });
        }
        
        return $total;
    }

    private function getMonthlyRevenue(): float
    {
        $revenue = 0;
        $tenants = Tenant::all();
        
        foreach ($tenants as $tenant) {
            $tenant->run(function () use (&$revenue) {
                $subscriptions = Subscription::where('status', 'active')
                    ->with('plan')
                    ->get();
                    
                foreach ($subscriptions as $subscription) {
                    if ($subscription->plan) {
                        $revenue += $subscription->billing_cycle === 'monthly' 
                            ? $subscription->plan->monthly_price 
                            : $subscription->plan->yearly_price / 12;
                    }
                }
            });
        }
        
        return $revenue;
    }

    private function getSystemUptime(): float
    {
        // This would typically integrate with your monitoring system
        // For now, return a mock value
        return 99.8;
    }

    private function getApiRequestsToday(): int
    {
        // This would typically read from your API logs or monitoring system
        // For now, return a mock value
        return rand(1000, 5000);
    }

    private function getTenantGrowthRate(): float
    {
        $thisMonth = Tenant::whereMonth('created_at', Carbon::now()->month)->count();
        $lastMonth = Tenant::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        
        if ($lastMonth === 0) {
            return $thisMonth > 0 ? 100 : 0;
        }
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    private function checkDatabaseHealth(): array
    {
        try {
            DB::connection()->getPdo();
            $responseTime = $this->measureDatabaseResponseTime();
            
            return [
                'status' => 'healthy',
                'response_time' => $responseTime,
                'message' => 'Database connection successful'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'response_time' => null,
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
        }
    }

    private function checkRedisHealth(): array
    {
        try {
            Cache::store('redis')->put('health_check', 'ok', 10);
            $value = Cache::store('redis')->get('health_check');
            
            return [
                'status' => $value === 'ok' ? 'healthy' : 'warning',
                'message' => $value === 'ok' ? 'Redis connection successful' : 'Redis connection issues'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Redis connection failed: ' . $e->getMessage()
            ];
        }
    }

    private function checkStorageHealth(): array
    {
        try {
            $diskSpace = disk_free_space(storage_path());
            $totalSpace = disk_total_space(storage_path());
            $usagePercent = round((($totalSpace - $diskSpace) / $totalSpace) * 100, 1);
            
            $status = $usagePercent > 90 ? 'warning' : ($usagePercent > 95 ? 'error' : 'healthy');
            
            return [
                'status' => $status,
                'usage_percent' => $usagePercent,
                'free_space' => $this->formatBytes($diskSpace),
                'message' => "Storage usage: {$usagePercent}%"
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Storage check failed: ' . $e->getMessage()
            ];
        }
    }

    private function checkQueueHealth(): array
    {
        try {
            // This would typically check your queue system (Redis, database, etc.)
            // For now, return a mock healthy status
            return [
                'status' => 'healthy',
                'pending_jobs' => rand(0, 50),
                'message' => 'Queue system operational'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Queue check failed: ' . $e->getMessage()
            ];
        }
    }

    private function measureDatabaseResponseTime(): float
    {
        $start = microtime(true);
        DB::select('SELECT 1');
        $end = microtime(true);
        
        return round(($end - $start) * 1000, 2); // Convert to milliseconds
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function render()
    {
        return view('livewire.super-admin.dashboard');
    }
}
