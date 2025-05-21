<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Device Heartbeat Request Data Transfer Object
 *
 * This DTO handles the data sent by devices during heartbeat/ping operations.
 * It contains status information, system metrics, and diagnostic data from edge devices.
 */
final readonly class DeviceHeartbeatRequest
{
    /**
     * @param string $status Current device status (online, offline, error, maintenance)
     * @param string|null $ipAddress Current IP address of the device
     * @param array|null $metrics Performance metrics (CPU, memory, temperature)
     * @param string|null $appVersion Current version of the device application
     * @param array|null $screenStatus Status of connected displays (power, brightness, errors)
     * @param array|null $storageInfo Storage usage information (total, used, free)
     * @param array|null $networkInfo Network connectivity information (signal strength, type)
     * @param array|null $systemInfo System diagnostic information (uptime, OS version)
     * @param array|null $hardwareInfo Hardware specifications and diagnostics
     * @param array|null $displayMetrics Metrics related to display performance
     * @param array|null $temperature Temperature readings from the device
     * @param array|null $errorLogs Error logs from the device
     */
    public function __construct(
        public string $status,
        public ?string $ipAddress = null,
        public ?array $metrics = null,
        public ?string $appVersion = null,
        public ?array $screenStatus = null,
        public ?array $storageInfo = null,
        public ?array $networkInfo = null,
        public ?array $systemInfo = null,
        public ?array $hardwareInfo = null,
        public ?array $displayMetrics = null,
        public ?array $temperature = null,
        public ?array $errorLogs = null,
    ) {
    }

    /**
     * Create from request data
     *
     * @param array $data Raw data from the device heartbeat request
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'] ?? 'online',
            ipAddress: $data['ip_address'] ?? null,
            metrics: $data['metrics'] ?? null,
            appVersion: $data['app_version'] ?? null,
            screenStatus: $data['screen_status'] ?? null,
            storageInfo: $data['storage_info'] ?? null,
            networkInfo: $data['network_info'] ?? null,
            systemInfo: $data['system_info'] ?? null,
            hardwareInfo: $data['hardware_info'] ?? null,
            displayMetrics: $data['display_metrics'] ?? null,
            temperature: $data['temperature'] ?? null,
            errorLogs: $data['error_logs'] ?? null,
        );
    }

    /**
     * Get a list of valid device statuses
     *
     * @return array<string>
     */
    public static function getValidStatuses(): array
    {
        return [
            'online',
            'offline',
            'error',
            'maintenance',
            'rebooting',
            'updating',
            'standby',
        ];
    }

    /**
     * Convert the DTO to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status'          => $this->status,
            'ip_address'      => $this->ipAddress,
            'metrics'         => $this->metrics,
            'app_version'     => $this->appVersion,
            'screen_status'   => $this->screenStatus,
            'storage_info'    => $this->storageInfo,
            'network_info'    => $this->networkInfo,
            'system_info'     => $this->systemInfo,
            'hardware_info'   => $this->hardwareInfo,
            'display_metrics' => $this->displayMetrics,
            'temperature'     => $this->temperature,
            'error_logs'      => $this->errorLogs,
        ];
    }

    /**
     * Validate the heartbeat data structure
     *
     * @param array $data Raw data from the device heartbeat request
     * @return bool Whether the data is valid
     */
    public static function validate(array $data): bool
    {
        // Status must be one of the valid statuses
        if (isset($data['status']) && ! in_array($data['status'], self::getValidStatuses())) {
            return false;
        }

        // Validate array fields
        $arrayFields = [
            'metrics',
            'screen_status',
            'storage_info',
            'network_info',
            'system_info',
            'hardware_info',
            'display_metrics',
            'temperature',
            'error_logs',
        ];

        foreach ($arrayFields as $field) {
            if (isset($data[$field]) && ! is_array($data[$field])) {
                return false;
            }
        }

        return true;
    }
}
