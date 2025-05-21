<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Dashboard;

use App\Livewire\Dashboard\TenantSettings;
use App\Tenant\Models\Tenant;
use App\Tenant\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TenantSettingsTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and initialize tenant
        $this->tenant = Tenant::create([
            'id'    => 'test-company',
            'name'  => 'Test Company',
            'email' => 'admin@test-company.com',
            'data'  => [
                'settings' => [
                    'timezone'                => 'UTC',
                    'language'                => 'en',
                    'notifications_enabled'   => true,
                    'default_screen_duration' => 30,
                    'company_logo'            => null,
                    'primary_color'           => '#000000',
                    'secondary_color'         => '#ffffff',
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

    public function test_settings_component_can_be_rendered(): void
    {
        $this->actingAs($this->user);

        $this->tenant->run(function () {
            Livewire::test(TenantSettings::class)
                ->assertViewIs('livewire.dashboard.tenant-settings')
                ->assertSee('Tenant Settings')
                ->assertSee('Timezone')
                ->assertSee('Language');
        });
    }

    public function test_settings_can_be_updated(): void
    {
        $this->actingAs($this->user);

        $this->tenant->run(function () {
            Livewire::test(TenantSettings::class)
                ->set('timezone', 'America/New_York')
                ->set('language', 'ar')
                ->set('notifications_enabled', false)
                ->set('default_screen_duration', 60)
                ->set('company_logo', 'https://example.com/logo.png')
                ->set('primary_color', '#ff0000')
                ->set('secondary_color', '#00ff00')
                ->call('save')
                ->assertDispatched('settings-updated');

            // Refresh tenant
            $this->tenant->refresh();

            // Assert settings were updated
            $settings = $this->tenant->data['settings'];
            $this->assertEquals('America/New_York', $settings['timezone']);
            $this->assertEquals('ar', $settings['language']);
            $this->assertFalse($settings['notifications_enabled']);
            $this->assertEquals(60, $settings['default_screen_duration']);
            $this->assertEquals('https://example.com/logo.png', $settings['company_logo']);
            $this->assertEquals('#ff0000', $settings['primary_color']);
            $this->assertEquals('#00ff00', $settings['secondary_color']);
        });
    }

    public function test_settings_validation(): void
    {
        $this->actingAs($this->user);

        $this->tenant->run(function () {
            Livewire::test(TenantSettings::class)
                ->set('timezone', 'Invalid/Timezone')
                ->set('language', 'invalid')
                ->set('default_screen_duration', 5)
                ->call('save')
                ->assertHasErrors([
                    'timezone'                => 'in',
                    'language'                => 'in',
                    'default_screen_duration' => 'min',
                ]);
        });
    }

    public function test_settings_are_loaded_from_tenant(): void
    {
        $this->actingAs($this->user);

        $this->tenant->run(function () {
            $component = Livewire::test(TenantSettings::class);

            $this->assertEquals('UTC', $component->get('timezone'));
            $this->assertEquals('en', $component->get('language'));
            $this->assertTrue($component->get('notifications_enabled'));
            $this->assertEquals(30, $component->get('default_screen_duration'));
            $this->assertNull($component->get('company_logo'));
            $this->assertEquals('#000000', $component->get('primary_color'));
            $this->assertEquals('#ffffff', $component->get('secondary_color'));
        });
    }
}
