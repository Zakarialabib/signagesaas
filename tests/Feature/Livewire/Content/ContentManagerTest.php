<?php

namespace Tests\Feature\Livewire\Content;

use App\Livewire\Content\ContentManager;
use App\Models\Tenant;
use App\Models\User;
use App\Enums\ContentType;
use App\Tenant\Models\Content; // Assuming tenant models are in App\Tenant\Models
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Str;

class ContentManagerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->initializeTenantWithUser();
    }

    private function initializeTenantWithUser(): void
    {
        $this->tenant = Tenant::create(['id' => 'test_tenant_'.Str::random(5)]);
        $this->tenant->domains()->create(['domain' => 'test-'.Str::random(5).'.localhost']);
        tenancy()->initialize($this->tenant);

        $this->user = User::factory()->create(['tenant_id' => $this->tenant->id]);
        $this->actingAs($this->user);
    }

    /** @test */
    public function testFilterAndDisplayForWidgetContentType()
    {
        // 1. Seed Content Items
        $widgetContent = Content::factory()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'My Test Widget',
            'type' => ContentType::WIDGET,
            'content_data' => ['widget_type' => 'TestWidgetType', 'data' => ['info' => 'Some widget data']],
        ]);

        $imageContent = Content::factory()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'My Test Image',
            'type' => ContentType::IMAGE,
            'content_data' => ['url' => 'test.jpg'],
        ]);

        // 2. Mount ContentManager
        $livewireTest = Livewire::test(ContentManager::class)
            ->assertStatus(200);

        // 3. Set typeFilter to WIDGET
        $livewireTest->set('typeFilter', ContentType::WIDGET->value)
            ->assertSee($widgetContent->name)
            ->assertDontSee($imageContent->name);
        
        // 4. Assert displayed type includes "Widget" and "(TestWidgetType)"
        // The exact HTML structure depends on the blade file, this is an example check
        $this->assertStringContainsString(ContentType::WIDGET->label(), $livewireTest->html());
        $this->assertStringContainsString('(TestWidgetType)', $livewireTest->html());

        // 5. Set typeFilter to IMAGE
        $livewireTest->set('typeFilter', ContentType::IMAGE->value)
            ->assertSee($imageContent->name)
            ->assertDontSee($widgetContent->name);
    }
}
