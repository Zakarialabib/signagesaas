<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Devices;

use App\Tenant\Models\Device;
use App\Http\Livewire\Devices\DeviceManager;
use App\Tenant\Models\Tenant;
use App\Tenant\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class DeviceManagerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_it_shows_devices_list(): void
    {
        Device::factory()
            ->count(3)
            ->create([
                'tenant_id' => $this->tenant->id,
            ]);

        Livewire::actingAs($this->user)
            ->test(DeviceManager::class)
            ->assertViewIs('livewire.devices.device-manager')
            ->assertViewHas('devices')
            ->assertSee('Devices')
            ->assertSee('Add Device');
    }

    public function test_it_filters_devices_by_status(): void
    {
        Device::factory()
            ->online()
            ->create([
                'tenant_id' => $this->tenant->id,
                'name'      => 'Online Device',
            ]);

        Device::factory()
            ->offline()
            ->create([
                'tenant_id' => $this->tenant->id,
                'name'      => 'Offline Device',
            ]);

        Livewire::actingAs($this->user)
            ->test(DeviceManager::class)
            ->set('statusFilter', 'online')
            ->assertSee('Online Device')
            ->assertDontSee('Offline Device')
            ->set('statusFilter', 'offline')
            ->assertSee('Offline Device')
            ->assertDontSee('Online Device');
    }

    public function test_it_searches_devices(): void
    {
        Device::factory()->create([
            'tenant_id'   => $this->tenant->id,
            'name'        => 'Test Device 1',
            'hardware_id' => 'hw-123',
        ]);

        Device::factory()->create([
            'tenant_id'   => $this->tenant->id,
            'name'        => 'Test Device 2',
            'hardware_id' => 'hw-456',
        ]);

        Livewire::actingAs($this->user)
            ->test(DeviceManager::class)
            ->set('search', 'Device 1')
            ->assertSee('Test Device 1')
            ->assertDontSee('Test Device 2')
            ->set('search', 'hw-456')
            ->assertSee('Test Device 2')
            ->assertDontSee('Test Device 1');
    }

    public function test_it_refreshes_devices(): void
    {
        Device::factory()->create([
            'tenant_id' => $this->tenant->id,
            'name'      => 'Initial Device',
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(DeviceManager::class)
            ->assertSee('Initial Device');

        Device::factory()->create([
            'tenant_id' => $this->tenant->id,
            'name'      => 'New Device',
        ]);

        $component->call('refreshDevices')
            ->assertSee('Initial Device')
            ->assertSee('New Device');
    }
}
