<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Tenant;
use App\Models\User;
use App\Tenant\Models\Device;
use App\Tenant\Models\Screen;
use App\Tenant\Models\Content;
use App\Tenant\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\TenantStorageService;

#[Layout('layouts.super-admin')]
#[Title('Tenant Health Monitor')]
class TenantHealth extends Component
{
    public $tenant;
    public $healthMetrics = [];
    public $overallHealth = 'good';
    public $refreshInterval = 30; // seconds

    public function mount($tenantId)
    {
        $this->tenant = Tenant::findOrFail($tenantId);
        $this->runHealthChecks();
    }

    public function runHealthChecks()
    {
        $this->healthMetrics = [
            'database' => $this->checkDatabaseConnectivity(),
            'users' => $this->checkUserStatus(),
            'devices' => $this->checkDeviceStatus(),
            'screens' => $this->checkScreenStatus(),
            'content' => $this->checkContentStatus(),
            'storage' => $this->checkStorageUsage(),
        ];

        $this->calculateOverallHealth();
    }

    public function checkDatabaseConnectivity()
    {
        try {
            // Switch to tenant context and test database connection
            tenancy()->initialize($this->tenant);
            
            $connectionTest = DB::connection()->getPdo();
            $tableCount = DB::select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = DATABASE()")[0]->count;
            
            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
                'details' => [
                    'tables' => $tableCount,
                    'connection' => 'active'
                ],
                'score' => 100
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Database connection failed: ' . $e->getMessage(),
                'details' => [
                    'error' => $e->getMessage()
                ],
                'score' => 0
            ];
        }
    }

    public function checkUserStatus()
    {
        try {
            tenancy()->initialize($this->tenant);
            
            $totalUsers = User::count();
            $activeUsers = User::where('created_at', '>=', now()->subDays(30))->count();
            $adminUsers = User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->count();
            
            $score = 100;
            $status = 'healthy';
            $message = 'User metrics are healthy';
            
            if ($adminUsers === 0) {
                $score = 20;
                $status = 'critical';
                $message = 'No admin users found';
            } elseif ($totalUsers === 0) {
                $score = 30;
                $status = 'warning';
                $message = 'No users in the system';
            } elseif ($activeUsers / max($totalUsers, 1) < 0.1) {
                $score = 60;
                $status = 'warning';
                $message = 'Low user activity detected';
            }
            
            return [
                'status' => $status,
                'message' => $message,
                'details' => [
                    'total' => $totalUsers,
                    'active' => $activeUsers,
                    'admins' => $adminUsers,
                    'activity_rate' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0
                ],
                'score' => $score
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Failed to check user status: ' . $e->getMessage(),
                'details' => ['error' => $e->getMessage()],
                'score' => 0
            ];
        }
    }

    public function checkDeviceStatus()
    {
        try {
            tenancy()->initialize($this->tenant);
            
            $totalDevices = Device::count();
            $onlineDevices = Device::where('last_seen_at', '>=', now()->subMinutes(10))->count();
            $offlineDevices = $totalDevices - $onlineDevices;
            
            $score = 100;
            $status = 'healthy';
            $message = 'Device connectivity is good';
            
            if ($totalDevices === 0) {
                $score = 50;
                $status = 'warning';
                $message = 'No devices registered';
            } elseif ($onlineDevices / $totalDevices < 0.5) {
                $score = 40;
                $status = 'warning';
                $message = 'Many devices are offline';
            } elseif ($onlineDevices / $totalDevices < 0.2) {
                $score = 20;
                $status = 'critical';
                $message = 'Most devices are offline';
            }
            
            return [
                'status' => $status,
                'message' => $message,
                'details' => [
                    'total' => $totalDevices,
                    'online' => $onlineDevices,
                    'offline' => $offlineDevices,
                    'online_rate' => $totalDevices > 0 ? round(($onlineDevices / $totalDevices) * 100, 1) : 0
                ],
                'score' => $score
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Failed to check device status: ' . $e->getMessage(),
                'details' => ['error' => $e->getMessage()],
                'score' => 0
            ];
        }
    }

    public function checkScreenStatus()
    {
        try {
            tenancy()->initialize($this->tenant);
            
            $totalScreens = Screen::count();
            $activeScreens = Screen::where('is_active', true)->count();
            $inactiveScreens = $totalScreens - $activeScreens;
            
            $score = 100;
            $status = 'healthy';
            $message = 'Screen status is optimal';
            
            if ($totalScreens === 0) {
                $score = 50;
                $status = 'warning';
                $message = 'No screens configured';
            } elseif ($activeScreens / $totalScreens < 0.7) {
                $score = 60;
                $status = 'warning';
                $message = 'Some screens are inactive';
            }
            
            return [
                'status' => $status,
                'message' => $message,
                'details' => [
                    'total' => $totalScreens,
                    'active' => $activeScreens,
                    'inactive' => $inactiveScreens,
                    'active_rate' => $totalScreens > 0 ? round(($activeScreens / $totalScreens) * 100, 1) : 0
                ],
                'score' => $score
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Failed to check screen status: ' . $e->getMessage(),
                'details' => ['error' => $e->getMessage()],
                'score' => 0
            ];
        }
    }

    public function checkContentStatus()
    {
        try {
            tenancy()->initialize($this->tenant);
            
            $totalContent = Content::count();
            $scheduledContent = Schedule::whereHas('contents')->count();
            
            // Placeholder for actual storage calculation. 
            // In a real scenario, this would involve summing file sizes 
            // from a storage service or dedicated media table.
            $storageUsed = 0; // This needs proper implementation

            $score = 100;
            $status = 'healthy';
            $message = 'Content management is operational';

            if ($totalContent === 0) {
                $score = 50;
                $status = 'warning';
                $message = 'No content uploaded yet';
            } elseif ($scheduledContent === 0 && $totalContent > 0) {
                $score = 70;
                $status = 'warning';
                $message = 'Content exists but none is scheduled';
            }
            
            return [
                'status' => $status,
                'message' => $message,
                'details' => [
                    'total' => $totalContent,
                    'scheduled' => $scheduledContent,
                    'storage_used_mb' => round($storageUsed / 1024 / 1024, 2) // Assuming bytes, converting to MB
                ],
                'score' => $score
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'warning',
                'message' => 'Content status check incomplete: ' . $e->getMessage(),
                'details' => ['error' => $e->getMessage()],
                'score' => 50
            ];
        }
    }

    public function checkStorageUsage()
    {
        try {
            tenancy()->initialize($this->tenant);
            
            // Get tenant's plan storage limit (assuming it's stored in tenant data)
            $planLimit = $this->tenant->data['plan_storage_limit'] ?? 1000; // MB
            
            $tenantStorageService = new TenantStorageService();
            $usedStorage = $tenantStorageService->calculateTenantStorage($this->tenant); // MB
            
            $usagePercentage = ($usedStorage / $planLimit) * 100;
            
            $score = 100;
            $status = 'healthy';
            $message = 'Storage usage is within limits';
            
            if ($usagePercentage > 90) {
                $score = 20;
                $status = 'critical';
                $message = 'Storage usage is critically high';
            } elseif ($usagePercentage > 75) {
                $score = 50;
                $status = 'warning';
                $message = 'Storage usage is approaching limit';
            } elseif ($usagePercentage > 50) {
                $score = 80;
                $status = 'healthy';
                $message = 'Storage usage is moderate';
            }
            
            return [
                'status' => $status,
                'message' => $message,
                'details' => [
                    'used_mb' => $usedStorage,
                    'limit_mb' => $planLimit,
                    'usage_percentage' => round($usagePercentage, 1),
                    'available_mb' => $planLimit - $usedStorage
                ],
                'score' => $score
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'warning',
                'message' => 'Storage check incomplete: ' . $e->getMessage(),
                'details' => ['error' => $e->getMessage()],
                'score' => 50
            ];
        }
    }

    public function calculateOverallHealth()
    {
        $totalScore = 0;
        $maxScore = 0;
        $criticalIssues = 0;
        
        foreach ($this->healthMetrics as $metric) {
            $totalScore += $metric['score'];
            $maxScore += 100;
            
            if ($metric['status'] === 'critical') {
                $criticalIssues++;
            }
        }
        
        $averageScore = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
        
        if ($criticalIssues > 0 || $averageScore < 40) {
            $this->overallHealth = 'critical';
        } elseif ($averageScore < 70) {
            $this->overallHealth = 'warning';
        } elseif ($averageScore < 90) {
            $this->overallHealth = 'good';
        } else {
            $this->overallHealth = 'excellent';
        }
        
        // Update tenant's health status
        $this->tenant->update([
            'data' => array_merge($this->tenant->data ?? [], [
                'health_status' => $this->overallHealth,
                'health_score' => round($averageScore, 1),
                'last_health_check' => now()->toISOString()
            ])
        ]);
    }

    public function refreshHealth()
    {
        $this->runHealthChecks();
        $this->dispatch('health-refreshed');
    }

    public function render()
    {
        return view('livewire.super-admin.tenant-health');
    }
}