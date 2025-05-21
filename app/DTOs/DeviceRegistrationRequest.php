<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class DeviceRegistrationRequest
{
    public function __construct(
        public string $tenantId,
        public string $name,
        public string $type,
        public string $hardwareId,
        public ?string $ipAddress = null,
        public ?string $screenResolution = null,
        public string $orientation = 'landscape',
        public ?string $osVersion = null,
        public ?string $appVersion = null,
        public ?array $location = null,
        public ?string $timezone = null,
        public ?array $settings = null,
    ) {
    }
}
