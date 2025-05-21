<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DeviceMonitoringService;
use App\Tenant\Models\Device;
use Illuminate\Console\Command;
use Exception;

class MonitorDeviceHealth extends Command
{
    protected $signature = 'devices:monitor-health';
    protected $description = 'Monitor device health and store metrics';

    public function __construct(
        private readonly DeviceMonitoringService $monitoringService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting device health monitoring...');

        Device::chunk(100, function ($devices) {
            foreach ($devices as $device) {
                try {
                    $this->monitoringService->checkDeviceHealth($device);
                } catch (Exception $e) {
                    $this->error("Error monitoring device {$device->id}: ".$e->getMessage());
                }
            }
        });

        $this->info('Device health monitoring completed.');

        return 0;
    }
}
