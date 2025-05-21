<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Livewire\Auth\Register;
use App\Tenant\Models\Tenant;
use App\Tenant\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_new_tenant_can_register(): void
    {
        Config::set('app.domain', 'signagesaas.test');

        $companyName = 'Test Company';
        $email = 'test@company.com';

        Livewire::test(Register::class)
            ->set('company', $companyName)
            ->set('name', 'John Doe')
            ->set('email', $email)
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register');

        // Assert tenant was created
        $tenant = Tenant::where('email', $email)->first();
        $this->assertNotNull($tenant);
        $this->assertEquals($companyName, $tenant->name);

        // Assert domain was created
        $domain = $tenant->domains->first();
        $this->assertNotNull($domain);
        $this->assertStringContainsString(str($companyName)->slug(), $domain->domain);

        // Assert user was created in tenant database
        $tenant->run(function () use ($email) {
            $user = User::where('email', $email)->first();
            $this->assertNotNull($user);
            $this->assertEquals('admin', $user->role);
        });
    }

    public function test_registration_requires_valid_data(): void
    {
        Livewire::test(Register::class)
            ->set('company', '')
            ->set('name', '')
            ->set('email', 'not-an-email')
            ->set('password', '123')
            ->set('password_confirmation', '456')
            ->call('register')
            ->assertHasErrors([
                'company'               => 'required',
                'name'                  => 'required',
                'email'                 => 'email',
                'password'              => 'min:8',
                'password_confirmation' => 'same:password',
            ]);
    }

    public function test_email_must_be_unique(): void
    {
        // Create a tenant with the email
        $email = 'test@company.com';
        Tenant::create([
            'id'    => 'test-company',
            'name'  => 'Test Company',
            'email' => $email,
        ]);

        Livewire::test(Register::class)
            ->set('company', 'Another Company')
            ->set('name', 'John Doe')
            ->set('email', $email)
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['email' => 'unique']);
    }
}
