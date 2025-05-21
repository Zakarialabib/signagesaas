<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Livewire\Auth\Login;
use App\Tenant\Models\Tenant;
use App\Tenant\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Stancl\Tenancy\Facades\Tenancy;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private string $domain;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up test domain
        Config::set('app.domain', 'signagesaas.test');

        // Create a tenant
        $this->tenant = Tenant::create([
            'id'    => 'test-company',
            'name'  => 'Test Company',
            'email' => 'admin@test-company.com',
        ]);

        // Create domain
        $this->domain = 'test-company.'.config('app.domain');
        $this->tenant->createDomain(['domain' => $this->domain]);

        // Create user in tenant context
        $this->tenant->run(function () {
            $this->user = User::create([
                'name'     => 'Test User',
                'email'    => 'admin@test-company.com',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]);
        });
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get("http://{$this->domain}/login");
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_in_tenant_context(): void
    {
        Tenancy::initialize($this->tenant);

        Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect("https://{$this->domain}/dashboard");

        $this->assertAuthenticated();
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        Tenancy::initialize($this->tenant);

        Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_users_cannot_authenticate_in_wrong_tenant(): void
    {
        // Create another tenant
        $anotherTenant = Tenant::create([
            'id'    => 'another-company',
            'name'  => 'Another Company',
            'email' => 'admin@another-company.com',
        ]);

        $anotherDomain = 'another-company.'.config('app.domain');
        $anotherTenant->createDomain(['domain' => $anotherDomain]);

        // Try to login with user from first tenant
        Tenancy::initialize($anotherTenant);

        Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_remember_me_functionality(): void
    {
        Tenancy::initialize($this->tenant);

        Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->set('remember', true)
            ->call('login');

        $this->assertAuthenticated();
        $this->assertNotNull($this->user->fresh()->remember_token);
    }
}
