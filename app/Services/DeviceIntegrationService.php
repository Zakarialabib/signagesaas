<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\DeviceHeartbeatRequest;
use App\Tenant\Models\Device;
use App\Tenant\Models\DeviceMetric;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

final readonly class DeviceIntegrationService
{
    /** Generate a signed URL for OTA updates */
    public function generateSignedUpdateUrl(Device $device, string $filePath, int $expirationMinutes = 60): string
    {
        // Ensure the file exists
        if ( ! Storage::exists($filePath)) {
            throw new InvalidArgumentException("Update file does not exist: {$filePath}");
        }

        // Generate a signed URL with expiration
        return URL::temporarySignedRoute(
            'api.device.download',
            now()->addMinutes($expirationMinutes),
            ['device' => $device->id, 'file' => $filePath]
        );
    }

    /** Generate an authentication token for a device */
    public function generateDeviceToken(Device $device): string
    {
        // Create a token with device abilities
        return $device->createToken(
            'device_auth_token',
            ['device:sync', 'device:ping']
        )->plainTextToken;
    }

    /** Validate device heartbeat and update status */
    public function processHeartbeat(Device $device, DeviceHeartbeatRequest $heartbeat): void
    {
        // Update device with heartbeat data
        $device->update([
            'last_ping_at' => now(),
            'status'       => $heartbeat->status ?? $device->status,
            'ip_address'   => $heartbeat->ipAddress ?? $device->ip_address,
            'app_version'  => $heartbeat->appVersion ?? $device->app_version,
        ]);

        // Store metrics in time-series format
        if ($heartbeat->metrics) {
            DeviceMetric::recordMetric($device, 'performance', $heartbeat->metrics);
        }

        // Store hardware information
        if ($heartbeat->hardwareInfo) {
            $device->update(['hardware_info' => $heartbeat->hardwareInfo]);
            DeviceMetric::recordMetric($device, 'hardware', $heartbeat->hardwareInfo);
        }

        // Store display metrics
        if ($heartbeat->displayMetrics) {
            DeviceMetric::recordMetric($device, 'display', $heartbeat->displayMetrics);
        }

        // Process temperature readings
        if ($heartbeat->temperature) {
            DeviceMetric::recordMetric($device, 'temperature', $heartbeat->temperature);
        }

        // Store network information
        if ($heartbeat->networkInfo) {
            DeviceMetric::recordMetric($device, 'network', $heartbeat->networkInfo);
        }

        // Process system information
        if ($heartbeat->systemInfo) {
            $device->update(['system_info' => $heartbeat->systemInfo]);
            DeviceMetric::recordMetric($device, 'system', $heartbeat->systemInfo);
        }

        // Process storage information
        if ($heartbeat->storageInfo) {
            $device->update(['storage_info' => $heartbeat->storageInfo]);
            DeviceMetric::recordMetric($device, 'storage', $heartbeat->storageInfo);
        }

        // Handle screen status
        if ($heartbeat->screenStatus) {
            $this->processScreenStatus($device, $heartbeat->screenStatus);
        }

        // Process error logs
        if ($heartbeat->errorLogs) {
            $this->processErrorLogs($device, $heartbeat->errorLogs);
            DeviceMetric::recordMetric($device, 'errors', $heartbeat->errorLogs);
        }
    }

    /** Process screen status updates */
    private function processScreenStatus(Device $device, array $screenStatus): void
    {
        foreach ($device->screens as $screen) {
            if (isset($screenStatus[$screen->id])) {
                $screen->update([
                    'status'     => $screenStatus[$screen->id]['status'] ?? $screen->status,
                    'last_check' => now(),
                    'metadata'   => array_merge(
                        $screen->metadata ?? [],
                        ['screen_status' => $screenStatus[$screen->id]]
                    ),
                ]);
            }
        }

        // Record screen status metrics
        DeviceMetric::recordMetric($device, 'screen_status', $screenStatus);
    }

    /** Process error logs */
    private function processErrorLogs(Device $device, array $errorLogs): void
    {
        foreach ($errorLogs as $log) {
            Log::error("Device Error: {$device->name}", [
                'device_id' => $device->id,
                'error'     => $log,
                'timestamp' => now(),
            ]);
        }
    }

    /** Check if device needs content sync */
    public function deviceNeedsSync(Device $device): bool
    {
        return $device->needsSync() || $device->screens()->where('updated_at', '>', $device->last_sync_at ?? now()->subDays(30))->exists();
    }

    /** Mark device as synced */
    public function markDeviceSynced(Device $device): void
    {
        $device->update([
            'last_sync_at' => now(),
            'sync_status'  => 'success',
        ]);
    }
}
