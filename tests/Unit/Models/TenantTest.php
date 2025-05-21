<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_be_created_with_valid_data(): void
    {
        $data = [
            'id'    => 'test-company',
            'name'  => 'Test Company',
            'email' => 'admin@test-company.com',
            'plan'  => 'basic',
            'data'  => [
                'settings' => [
                    'timezone' => 'UTC',
                    'language' => 'en',
                ],
            ],
        ];

        $tenant = Tenant::create($data);

        $this->assertInstanceOf(Tenant::class, $tenant);
        $this->assertEquals($data['id'], $tenant->id);
        $this->assertEquals($data['name'], $tenant->name);
        $this->assertEquals($data['email'], $tenant->email);
        $this->assertEquals($data['plan'], $tenant->plan);
        $this->assertEquals($data['data'], $tenant->data);
    }

    public function test_tenant_can_create_domain(): void
    {
        $tenant = Tenant::create([
            'id'    => 'test-company',
            'name'  => 'Test Company',
            'email' => 'admin@test-company.com',
        ]);

        $domain = $tenant->createDomain([
            'domain' => 'test-company.signagesaas.test',
        ]);

        $this->assertNotNull($domain);
        $this->assertEquals('test-company.signagesaas.test', $domain->domain);
        $this->assertTrue($tenant->domains->contains($domain));
    }

    public function test_tenant_can_check_trial_status(): void
    {
        $tenant = Tenant::create([
            'id'            => 'test-company',
            'name'          => 'Test Company',
            'email'         => 'admin@test-company.com',
            'trial_ends_at' => now()->addDays(14),
        ]);

        $this->assertTrue($tenant->isOnTrial());

        $tenant->trial_ends_at = now()->subDay();
        $tenant->save();

        $this->assertFalse($tenant->isOnTrial());
    }

    public function test_tenant_custom_columns_are_defined(): void
    {
        $columns = Tenant::getCustomColumns();

        $this->assertIsArray($columns);
        $this->assertContains('id', $columns);
        $this->assertContains('name', $columns);
        $this->assertContains('email', $columns);
        $this->assertContains('plan', $columns);
        $this->assertContains('data', $columns);
    }
}
