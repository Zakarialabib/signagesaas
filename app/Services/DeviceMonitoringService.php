<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\DeviceMetricType;
use App\Enums\DeviceStatus;
use App\Notifications\DeviceAlert;
use App\Tenant\Models\Device;
use App\Tenant\Models\DeviceMetric;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

final readonly class DeviceMonitoringService
{
    public function __construct(
        private DeviceIntegrationService $deviceIntegrationService
    ) {
    }

    public function checkDeviceHealth(Device $device): void
    {
        $this->updateDeviceStatus($device);
        $this->checkMetricThresholds($device);
        $this->calculateHealthScore($device);
    }

    private function updateDeviceStatus(Device $device): void
    {
        $wasOnline = $device->status === DeviceStatus::ONLINE;
        $isOnline = $device->isOnline();

        if ($wasOnline && ! $isOnline) {
            $device->markAsOffline();
            $this->logDeviceAlert($device, 'Device went offline');
        } elseif ( ! $wasOnline && $isOnline) {
            $device->markAsOnline();
            $this->logDeviceAlert($device, 'Device came back online');
        }
    }

    private function checkMetricThresholds(Device $device): void
    {
        foreach (DeviceMetricType::getThresholds() as $type => $thresholds) {
            if ($metric = $device->getLatestMetric($type)) {
                foreach ($thresholds as $key => $threshold) {
                    $value = $metric->getValue($key);

                    if ($value !== null) {
                        $this->checkThresholdViolation($device, $type, $key, $value, $threshold);
                    }
                }
            }
        }
    }

    private function checkThresholdViolation(
        Device $device,
        string $type,
        string $key,
        float $value,
        float $threshold
    ): void {
        $isViolation = match ($key) {
            'free_space_percent' => $value < $threshold,
            default              => $value > $threshold,
        };

        if ($isViolation) {
            $message = $this->formatThresholdAlert($type, $key, $value, $threshold);
            $this->logDeviceAlert($device, $message, [
                'type'      => $type,
                'metric'    => $key,
                'value'     => $value,
                'threshold' => $threshold,
            ]);
        }
    }

    private function formatThresholdAlert(string $type, string $key, float $value, float $threshold): string
    {
        return match ($key) {
            'cpu_usage_percent'    => "High CPU usage: {$value}% (threshold: {$threshold}%)",
            'memory_usage_percent' => "High memory usage: {$value}% (threshold: {$threshold}%)",
            'disk_usage_percent'   => "High disk usage: {$value}% (threshold: {$threshold}%)",
            'cpu_temp'             => "High CPU temperature: {$value}°C (threshold: {$threshold}°C)",
            'gpu_temp'             => "High GPU temperature: {$value}°C (threshold: {$threshold}°C)",
            'free_space_percent'   => "Low storage space: {$value}% free (threshold: {$threshold}%)",
            default                => "Threshold violation for {$key}: {$value} (threshold: {$threshold})",
        };
    }

    private function calculateHealthScore(Device $device): void
    {
        $score = 100;
        $factors = [];

        // Base score reduction for offline status
        if ( ! $device->isOnline()) {
            $score -= 50;
            $factors[] = 'offline';
        }

        // Check metric thresholds
        foreach (DeviceMetricType::getThresholds() as $type => $thresholds) {
            if ($metric = $device->getLatestMetric($type)) {
                foreach ($thresholds as $key => $threshold) {
                    $value = $metric->getValue($key);

                    if ($value !== null) {
                        $scoreImpact = $this->calculateMetricScoreImpact($type, $key, $value, $threshold);

                        if ($scoreImpact > 0) {
                            $score -= $scoreImpact;
                            $factors[] = "{$key}_threshold";
                        }
                    }
                }
            }
        }

        // Store health score
        DeviceMetric::recordMetric($device, DeviceMetricType::HEALTH_SCORE->value, [
            'score'     => max(0, $score),
            'factors'   => array_unique($factors),
            'timestamp' => now(),
        ]);

        // Alert if health score is below minimum threshold
        $minScore = DeviceMetricType::getThresholds()[DeviceMetricType::HEALTH_SCORE->value]['minimum_score'];

        if ($score < $minScore) {
            $this->logDeviceAlert($device, "Low health score: {$score} (minimum: {$minScore})", [
                'score'   => $score,
                'factors' => $factors,
            ]);
        }
    }

    private function calculateMetricScoreImpact(string $type, string $key, float $value, float $threshold): float
    {
        return match ($key) {
            'cpu_usage_percent', 'memory_usage_percent' => $value > $threshold ? 15 : 0,
            'disk_usage_percent' => $value > $threshold ? 10 : 0,
            'cpu_temp', 'gpu_temp' => $value > $threshold ? 20 : 0,
            'free_space_percent' => $value < $threshold ? 20 : 0,
            default              => 0,
        };
    }

    private function logDeviceAlert(Device $device, string $message, array $context = []): void
    {
        $alert = [
            'device_id'   => $device->id,
            'device_name' => $device->name,
            'tenant_id'   => $device->tenant_id,
            'message'     => $message,
            'context'     => $context,
            'timestamp'   => now(),
        ];

        // Log to device alerts channel
        Log::channel('device_alerts')->warning($message, $alert);

        // Record metric
        DeviceMetric::recordMetric($device, DeviceMetricType::ALERT->value, [
            'message'   => $message,
            'context'   => $context,
            'timestamp' => now(),
        ]);

        // Send notification to tenant admins
        $tenant = $device->tenant;

        if ($tenant) {
            $admins = $tenant->users()->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

            Notification::send($admins, new DeviceAlert($device, $message, $context));
        }
    }
}
