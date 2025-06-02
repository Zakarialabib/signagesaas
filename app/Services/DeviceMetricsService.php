<?php

declare(strict_types=1);

namespace App\Services;

use App\Tenant\Models\Device;
use App\Tenant\Models\DeviceMetric;
use Illuminate\Support\Carbon;

class DeviceMetricsService
{
    /**
     * Record device metrics
     */
    public function recordMetrics(Device $device, array $data): void
    {
        DeviceMetric::create([
            'device_id' => $device->id,
            'status' => $data['status'],
            'ip_address' => $data['ip_address'],
            'cpu_usage' => $data['metrics']['cpu_usage'],
            'memory_usage' => $data['metrics']['memory_usage'],
            'uptime' => $data['metrics']['uptime'],
            'app_version' => $data['app_version'],
            'screen_power' => $data['screen_status']['power'],
            'screen_brightness' => $data['screen_status']['brightness'],
            'storage_total' => $data['storage_info']['total'],
            'storage_free' => $data['storage_info']['free'],
            'network_type' => $data['network_info']['type'],
            'network_signal' => $data['network_info']['signal_strength'],
            'os_version' => $data['system_info']['os_version'],
            'device_model' => $data['system_info']['model'],
            'recorded_at' => Carbon::now(),
        ]);

        // Update device status
        $device->update([
            'status' => $data['status'],
            'last_seen_at' => Carbon::now(),
            'app_version' => $data['app_version'],
            'os_version' => $data['system_info']['os_version'],
            'device_model' => $data['system_info']['model'],
        ]);
    }

    /**
     * Get latest metrics for a device
     */
    public function getLatestMetrics(Device $device): ?DeviceMetric
    {
        return DeviceMetric::where('device_id', $device->id)
            ->latest('recorded_at')
            ->first();
    }

    /**
     * Get metrics summary for a device
     */
    public function getMetricsSummary(Device $device, Carbon $from, Carbon $to): array
    {
        $metrics = DeviceMetric::where('device_id', $device->id)
            ->whereBetween('recorded_at', [$from, $to])
            ->get();

        return [
            'avg_cpu_usage' => $metrics->avg('cpu_usage'),
            'avg_memory_usage' => $metrics->avg('memory_usage'),
            'avg_uptime' => $metrics->avg('uptime'),
            'avg_screen_brightness' => $metrics->avg('screen_brightness'),
            'avg_network_signal' => $metrics->avg('network_signal'),
            'total_records' => $metrics->count(),
            'first_record' => $metrics->min('recorded_at'),
            'last_record' => $metrics->max('recorded_at'),
        ];
    }
} 