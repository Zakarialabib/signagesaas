<?php

declare(strict_types=1);

namespace Tests\Unit\Devices\Services;

use App\Devices\DTOs\DeviceRegistrationRequest;
use App\Events\DeviceRegistered;
use App\Exceptions\DeviceRegistrationException;
use App\Tenant\Models\Device;
use App\Services\DeviceRegistrationService;
use App\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class DeviceRegistrationServiceTest extends TestCase
{
    use RefreshDatabase;

    private DeviceRegistrationService $service;
    private Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(DeviceRegistrationService::class);
        $this->tenant = Tenant::factory()->create();
    }

    public function test_it_registers_a_device(): void
    {
        Event::fake();

        $request = new DeviceRegistrationRequest(
            tenantId: $this->tenant->id,
            name: 'Test Device',
            type: 'media-player',
            hardwareId: 'test-hardware-id',
            ipAddress: '192.168.1.1',
            screenResolution: '1920x1080',
            orientation: 'landscape',
            osVersion: 'Android 12',
            appVersion: '1.0.0',
            location: ['lat' => 0, 'lng' => 0],
            timezone: 'UTC',
            settings: ['brightness' => 100]
        );

        $device = $this->service->register($request);

        $this->assertInstanceOf(Device::class, $device);
        $this->assertDatabaseHas('devices', [
            'tenant_id'   => $this->tenant->id,
            'name'        => 'Test Device',
            'type'        => 'media-player',
            'hardware_id' => 'test-hardware-id',
        ]);

        Event::assertDispatched(DeviceRegistered::class, function ($event) use ($device) {
            return $event->device->id === $device->id;
        });
    }

    public function test_it_throws_exception_on_duplicate_hardware_id(): void
    {
        Device::factory()->create([
            'tenant_id'   => $this->tenant->id,
            'hardware_id' => 'existing-hardware-id',
        ]);

        $this->expectException(DeviceRegistrationException::class);

        $request = new DeviceRegistrationRequest(
            tenantId: $this->tenant->id,
            name: 'Test Device',
            type: 'media-player',
            hardwareId: 'existing-hardware-id'
        );

        $this->service->register($request);
    }
}
