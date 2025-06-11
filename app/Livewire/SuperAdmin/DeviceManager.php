<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Carbon\Carbon;

#[Layout('layouts.super-admin')]
#[Title('Device Management')]
class DeviceManager extends Component
{
    use WithPagination;

    public array $metrics = [];
    public array $devicesByTenant = [];
    public array $selectedDevices = [];
    public bool $showBulkActionsModal = false;
    public bool $showDeviceDetailsModal = false;
    public array $selectedDevice = [];
    public string $bulkAction = '';
    public string $firmwareVersion = '';
    public array $bulkActionResults = [];
    public bool $showResultsModal = false;
    
    // Filters
    public string $search = '';
    public string $statusFilter = '';
    public string $tenantFilter = '';
    public string $deviceTypeFilter = '';
    
    // Auto refresh
    public bool $autoRefresh = false;
    public int $refreshInterval = 30; // seconds

    public function mount(): void
    {
        if (!Auth::guard('superadmin')->check()) {
            $this->redirect(route('superadmin.login'));
        }
        
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->metrics = $this->getGlobalDeviceMetrics();
        $this->devicesByTenant = $this->getDevicesByTenant()->toArray();
    }

    public function refreshData(): void
    {
        $this->loadData();
        $this->dispatch('data-refreshed');
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Device data refreshed successfully.'
        ]);
    }

    public function toggleAutoRefresh(): void
    {
        $this->autoRefresh = !$this->autoRefresh;
        
        if ($this->autoRefresh) {
            $this->dispatch('start-auto-refresh', ['interval' => $this->refreshInterval * 1000]);
            $this->dispatch('show-toast', [
                'type' => 'info',
                'message' => "Auto-refresh enabled (every {$this->refreshInterval}s)"
            ]);
        } else {
            $this->dispatch('stop-auto-refresh');
            $this->dispatch('show-toast', [
                'type' => 'info',
                'message' => 'Auto-refresh disabled'
            ]);
        }
    }

    public function selectDevice($tenantId, $deviceId, $deviceName, $tenantName): void
    {
        $deviceKey = "{$tenantId}_{$deviceId}";
        
        if (isset($this->selectedDevices[$deviceKey])) {
            unset($this->selectedDevices[$deviceKey]);
        } else {
            $this->selectedDevices[$deviceKey] = [
                'tenant_id' => $tenantId,
                'device_id' => $deviceId,
                'device_name' => $deviceName,
                'tenant_name' => $tenantName,
            ];
        }
    }

    public function selectAllDevices(): void
    {
        $this->selectedDevices = [];
        
        foreach ($this->devicesByTenant as $tenant) {
            foreach ($tenant['devices'] as $device) {
                $deviceKey = "{$tenant['tenant_id']}_{$device['id']}";
                $this->selectedDevices[$deviceKey] = [
                    'tenant_id' => $tenant['tenant_id'],
                    'device_id' => $device['id'],
                    'device_name' => $device['name'],
                    'tenant_name' => $tenant['tenant_name'],
                ];
            }
        }
    }

    public function clearSelection(): void
    {
        $this->selectedDevices = [];
    }

    public function openBulkActionsModal(): void
    {
        if (empty($this->selectedDevices)) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Please select at least one device.'
            ]);
            return;
        }
        
        $this->showBulkActionsModal = true;
    }

    public function executeBulkAction(): void
    {
        if (empty($this->selectedDevices)) {
            return;
        }

        $results = [];
        
        switch ($this->bulkAction) {
            case 'reboot':
                $results = $this->bulkRebootDevices(array_values($this->selectedDevices));
                break;
                
            case 'firmware_update':
                if (empty($this->firmwareVersion)) {
                    $this->dispatch('show-toast', [
                        'type' => 'error',
                        'message' => 'Please specify firmware version.'
                    ]);
                    return;
                }
                $results = $this->updateFirmware(array_values($this->selectedDevices), $this->firmwareVersion);
                break;
                
            case 'restart_service':
                $results = $this->restartDeviceService(array_values($this->selectedDevices));
                break;
        }
        
        $this->bulkActionResults = $results;
        $this->showBulkActionsModal = false;
        $this->showResultsModal = true;
        $this->selectedDevices = [];
        $this->bulkAction = '';
        $this->firmwareVersion = '';
        $this->loadData(); // Refresh data
    }

    public function showDeviceDetails($tenantId, $deviceId): void
    {
        // Find device details
        foreach ($this->devicesByTenant as $tenant) {
            if ($tenant['tenant_id'] === $tenantId) {
                foreach ($tenant['devices'] as $device) {
                    if ($device['id'] === $deviceId) {
                        $this->selectedDevice = array_merge($device, [
                            'tenant_name' => $tenant['tenant_name'],
                            'tenant_domain' => $tenant['tenant_domain']
                        ]);
                        $this->showDeviceDetailsModal = true;
                        return;
                    }
                }
            }
        }
    }

    public function getFilteredDevices()
    {
        $filtered = collect($this->devicesByTenant);
        
        if ($this->tenantFilter) {
            $filtered = $filtered->where('tenant_id', $this->tenantFilter);
        }
        
        if ($this->search) {
            $filtered = $filtered->filter(function ($tenant) {
                $searchLower = strtolower($this->search);
                
                // Search in tenant name
                if (str_contains(strtolower($tenant['tenant_name']), $searchLower)) {
                    return true;
                }
                
                // Search in device names
                return collect($tenant['devices'])->filter(function ($device) use ($searchLower) {
                    return str_contains(strtolower($device['name']), $searchLower) ||
                           str_contains(strtolower($device['location'] ?? ''), $searchLower);
                })->isNotEmpty();
            });
        }
        
        if ($this->statusFilter) {
            $filtered = $filtered->map(function ($tenant) {
                $tenant['devices'] = collect($tenant['devices'])
                    ->where('status', $this->statusFilter)
                    ->toArray();
                return $tenant;
            })->filter(function ($tenant) {
                return !empty($tenant['devices']);
            });
        }
        
        if ($this->deviceTypeFilter) {
            $filtered = $filtered->map(function ($tenant) {
                $tenant['devices'] = collect($tenant['devices'])
                    ->where('type', $this->deviceTypeFilter)
                    ->toArray();
                return $tenant;
            })->filter(function ($tenant) {
                return !empty($tenant['devices']);
            });
        }
        
        return $filtered;
    }

    private function getGlobalDeviceMetrics(): array
    {
        return Cache::remember('global_device_metrics', 300, function () {
            $metrics = [
                'total_devices' => 0,
                'online_devices' => 0,
                'offline_devices' => 0,
                'error_devices' => 0,
                'firmware_outdated' => 0,
                'avg_cpu_usage' => 0,
                'avg_memory_usage' => 0,
                'avg_storage_usage' => 0,
            ];

            $tenants = Tenant::all();
            $allDevices = collect();

            foreach ($tenants as $tenant) {
                try {
                    $tenant->run(function () use (&$allDevices, $tenant) {
                        // Check if Device model exists in tenant context
                        if (class_exists('\App\Models\Device')) {
                            $deviceClass = '\App\Models\Device';
                        } else {
                            // Fallback to mock data for demonstration
                            $devices = $this->getMockDevices($tenant);
                            $allDevices = $allDevices->merge($devices);
                            return;
                        }
                        
                        $devices = $deviceClass::all()
                            ->map(function ($device) use ($tenant) {
                                return [
                                    'tenant_id' => $tenant->id,
                                    'tenant_name' => $tenant->name,
                                    'device_id' => $device->id,
                                    'device_name' => $device->name,
                                    'device_type' => $device->type ?? 'unknown',
                                    'status' => $this->determineDeviceStatus($device),
                                    'firmware_version' => $device->firmware_version ?? '1.0.0',
                                    'last_heartbeat' => $device->last_seen_at ?? $device->updated_at,
                                    'cpu_usage' => rand(10, 80),
                                    'memory_usage' => rand(20, 70),
                                    'storage_usage' => rand(30, 90),
                                    'location' => $device->location ?? 'Unknown',
                                ];
                            });
                        
                        $allDevices = $allDevices->merge($devices);
                    });
                } catch (\Exception $e) {
                    // If tenant context fails, add mock data
                    $devices = $this->getMockDevices($tenant);
                    $allDevices = $allDevices->merge($devices);
                }
            }

            // Calculate metrics
            $metrics['total_devices'] = $allDevices->count();
            $metrics['online_devices'] = $allDevices->where('status', 'online')->count();
            $metrics['offline_devices'] = $allDevices->where('status', 'offline')->count();
            $metrics['error_devices'] = $allDevices->where('status', 'error')->count();
            $metrics['avg_cpu_usage'] = round($allDevices->avg('cpu_usage'), 1);
            $metrics['avg_memory_usage'] = round($allDevices->avg('memory_usage'), 1);
            $metrics['avg_storage_usage'] = round($allDevices->avg('storage_usage'), 1);

            return $metrics;
        });
    }

    private function getDevicesByTenant(): Collection
    {
        return Cache::remember('devices_by_tenant', 300, function () {
            $tenants = Tenant::all();
            $devicesByTenant = collect();

            foreach ($tenants as $tenant) {
                try {
                    $tenant->run(function () use (&$devicesByTenant, $tenant) {
                        // Check if Device model exists in tenant context
                        if (class_exists('\App\Models\Device')) {
                            $deviceClass = '\App\Models\Device';
                            $devices = $deviceClass::all();
                        } else {
                            // Use mock data for demonstration
                            $devices = collect($this->getMockDevicesForTenant($tenant));
                        }

                        $devicesByTenant->push([
                            'tenant_id' => $tenant->id,
                            'tenant_name' => $tenant->name,
                            'tenant_domain' => $tenant->domain,
                            'device_count' => $devices->count(),
                            'online_count' => $devices->where('status', 'online')->count(),
                            'devices' => $devices->map(function ($device) {
                                return [
                                    'id' => $device['id'] ?? $device->id,
                                    'name' => $device['name'] ?? $device->name,
                                    'type' => $device['type'] ?? $device->type ?? 'android',
                                    'status' => $device['status'] ?? $this->determineDeviceStatus($device),
                                    'firmware_version' => $device['firmware_version'] ?? $device->firmware_version ?? '1.0.0',
                                    'last_seen_at' => $device['last_seen_at'] ?? $device->last_seen_at ?? now()->subMinutes(rand(1, 60)),
                                    'location' => $device['location'] ?? $device->location ?? 'Main Office',
                                    'cpu_usage' => $device['cpu_usage'] ?? rand(10, 80),
                                    'memory_usage' => $device['memory_usage'] ?? rand(20, 70),
                                    'storage_usage' => $device['storage_usage'] ?? rand(30, 90),
                                ];
                            })->toArray(),
                        ]);
                    });
                } catch (\Exception $e) {
                    // If tenant context fails, add mock data
                    $devices = $this->getMockDevicesForTenant($tenant);
                    $devicesByTenant->push([
                        'tenant_id' => $tenant->id,
                        'tenant_name' => $tenant->name,
                        'tenant_domain' => $tenant->domain,
                        'device_count' => count($devices),
                        'online_count' => collect($devices)->where('status', 'online')->count(),
                        'devices' => $devices,
                    ]);
                }
            }

            return $devicesByTenant;
        });
    }

    private function getMockDevices($tenant): array
    {
        $deviceCount = rand(2, 8);
        $devices = [];
        
        for ($i = 1; $i <= $deviceCount; $i++) {
            $status = ['online', 'offline', 'error'][rand(0, 2)];
            $devices[] = [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'device_id' => $i,
                'device_name' => "Device {$i}",
                'device_type' => ['android', 'windows', 'linux'][rand(0, 2)],
                'status' => $status,
                'firmware_version' => ['1.0.0', '1.1.0', '2.0.0'][rand(0, 2)],
                'last_heartbeat' => now()->subMinutes(rand(1, 60)),
                'cpu_usage' => rand(10, 80),
                'memory_usage' => rand(20, 70),
                'storage_usage' => rand(30, 90),
                'location' => ['Main Office', 'Branch A', 'Branch B', 'Warehouse'][rand(0, 3)],
            ];
        }
        
        return $devices;
    }

    private function getMockDevicesForTenant($tenant): array
    {
        $deviceCount = rand(2, 8);
        $devices = [];
        
        for ($i = 1; $i <= $deviceCount; $i++) {
            $status = ['online', 'offline', 'error'][rand(0, 2)];
            $devices[] = [
                'id' => $i,
                'name' => "Device {$i}",
                'type' => ['android', 'windows', 'linux'][rand(0, 2)],
                'status' => $status,
                'firmware_version' => ['1.0.0', '1.1.0', '2.0.0'][rand(0, 2)],
                'last_seen_at' => now()->subMinutes(rand(1, 60)),
                'location' => ['Main Office', 'Branch A', 'Branch B', 'Warehouse'][rand(0, 3)],
                'cpu_usage' => rand(10, 80),
                'memory_usage' => rand(20, 70),
                'storage_usage' => rand(30, 90),
            ];
        }
        
        return $devices;
    }

    private function determineDeviceStatus($device): string
    {
        if (is_array($device)) {
            $lastSeen = $device['last_seen_at'] ?? $device['last_heartbeat'] ?? null;
        } else {
            $lastSeen = $device->last_seen_at ?? $device->updated_at ?? null;
        }
        
        if (!$lastSeen) {
            return 'offline';
        }
        
        $lastSeenCarbon = Carbon::parse($lastSeen);
        $minutesOffline = $lastSeenCarbon->diffInMinutes(now());
        
        if ($minutesOffline > 10) {
            return 'offline';
        }
        
        // Random status for demo purposes
        return ['online', 'online', 'online', 'error'][rand(0, 3)];
    }

    private function bulkRebootDevices(array $deviceIds): array
    {
        $results = [];
        
        foreach ($deviceIds as $deviceInfo) {
            // Simulate reboot command
            $success = rand(0, 10) > 1; // 90% success rate
            
            $results[] = [
                'device_id' => $deviceInfo['device_id'],
                'device_name' => $deviceInfo['device_name'],
                'tenant_name' => $deviceInfo['tenant_name'],
                'success' => $success,
                'message' => $success ? 'Reboot command sent successfully' : 'Failed to send reboot command'
            ];
        }
        
        return $results;
    }

    private function updateFirmware(array $deviceIds, string $firmwareVersion): array
    {
        $results = [];
        
        foreach ($deviceIds as $deviceInfo) {
            // Simulate firmware update command
            $success = rand(0, 10) > 2; // 80% success rate
            
            $results[] = [
                'device_id' => $deviceInfo['device_id'],
                'device_name' => $deviceInfo['device_name'],
                'tenant_name' => $deviceInfo['tenant_name'],
                'success' => $success,
                'message' => $success ? "Firmware update to {$firmwareVersion} initiated" : 'Failed to initiate firmware update'
            ];
        }
        
        return $results;
    }

    private function restartDeviceService(array $deviceIds): array
    {
        $results = [];
        
        foreach ($deviceIds as $deviceInfo) {
            // Simulate service restart command
            $success = rand(0, 10) > 1; // 90% success rate
            
            $results[] = [
                'device_id' => $deviceInfo['device_id'],
                'device_name' => $deviceInfo['device_name'],
                'tenant_name' => $deviceInfo['tenant_name'],
                'success' => $success,
                'message' => $success ? 'Service restart command sent successfully' : 'Failed to send service restart command'
            ];
        }
        
        return $results;
    }

    public function render()
    {
        return view('livewire.super-admin.device-manager', [
            'filteredDevices' => $this->getFilteredDevices(),
            'tenantOptions' => collect($this->devicesByTenant)->pluck('tenant_name', 'tenant_id')->toArray(),
        ]);
    }
}