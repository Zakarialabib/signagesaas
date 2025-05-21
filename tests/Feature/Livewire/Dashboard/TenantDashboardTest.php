<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Dashboard;

use App\Livewire\Dashboard\Index;
use App\Tenant\Models\Tenant;
use App\Tenant\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class TenantDashboardTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and initialize tenant
        $this->tenant = Tenant::create([
            'id'            => 'test-company',
            'name'          => 'Test Company',
            'email'         => 'admin@test-company.com',
            'plan'          => 'basic',
            'trial_ends_at' => now()->addDays(14),
            'data'          => [
                'settings' => [
                    'timezone'                => 'UTC',
                    'language'                => 'en',
                    'notifications_enabled'   => true,
                    'default_screen_duration' => 30,
                ],
            ],
        ]);

        // Create domain
        $this->tenant->domains()->create(['domain' => 'test-company.signagesaas.test']);

        // Create user in tenant context
        $this->tenant->run(function () {
            $this->user = User::create([
                'name'     => 'Test User',
                'email'    => 'test@test-company.com',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]);
        });
    }

    public function test_dashboard_can_be_rendered(): void
    {
        $this->actingAs($this->user);

        $this->tenant->run(function () {
            Livewire::test(Index::class)
                ->assertViewIs('livewire.dashboard.tenant-dashboard')
                ->assertSee('Tenant Dashboard')
                ->assertSee('Trial Period')
                ->assertSee('Devices')
                ->assertSee('Storage Usage');
        });
    }

    public function test_dashboard_shows_correct_metrics(): void
    {
        $this->actingAs($this->user);

        $this->tenant->run(function () {
            // Create some test data
            DB::table('devices')->insert([
                ['name' => 'Device 1', 'last_ping_at' => now()],
                ['name' => 'Device 2', 'last_ping_at' => now()->subMinutes(10)],
            ]);

            DB::table('screens')->insert([
                ['name' => 'Screen 1', 'is_active' => true],
                ['name' => 'Screen 2', 'is_active' => false],
            ]);

            Livewire::test(Index::class)
                ->assertSet('metrics.total_devices', 2)
                ->assertSet('metrics.active_devices', 1)
                ->assertSet('metrics.total_screens', 2)
                ->assertSet('metrics.active_screens', 1);
        });
    }

    public function test_dashboard_shows_trial_status(): void
    {
        $this->actingAs($this->user);

        $this->tenant->run(function () {
            $component = Livewire::test(Index::class);

            // Assert trial information is shown
            $component->assertSee('Trial Period')
                ->assertSee($this->tenant->trial_ends_at->format('Y-m-d'))
                ->assertSee('basic'); // Current plan
        });
    }
}
