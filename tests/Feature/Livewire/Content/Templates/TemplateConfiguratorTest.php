<?php

namespace Tests\Feature\Livewire\Content\Templates;

use App\Livewire\Content\Templates\TemplateConfigurator;
use App\Models\Tenant;
use App\Models\User;
use App\Enums\ContentType;
use App\Tenant\Models\Content; // Assuming tenant models are in App\Tenant\Models
use App\Tenant\Models\Template;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Str;

class TemplateConfiguratorTest extends TestCase
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
    public function testWidgetPreviewWithSelectableContent()
    {
        // 1. Seed Template
        $template = Template::factory()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Template for Preview',
            'layout' => [
                'zones' => [
                    'zone1' => [
                        'id' => 'zone1',
                        'name' => 'Main Widget Zone',
                        'type' => 'widget', // Zone type
                        'widget_type' => 'RetailProductWidget', // Specific widget expected
                        'content_id' => null, // Will be set later
                        'settings' => ['backgroundColor' => '#FFFFFF'],
                        'x_percentage' => 0, 'y_percentage' => 0, 'width_percentage' => 100, 'height_percentage' => 100,
                    ]
                ]
            ]
        ]);

        // 2. Seed Content Items
        $contentDataA = ['title' => 'Preview A Title', 'products' => [['name' => 'Product A1']]];
        $contentA = Content::factory()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Preview Content A',
            'type' => ContentType::WIDGET,
            'content_data' => ['widget_type' => 'RetailProductWidget', 'data' => $contentDataA],
        ]);

        $contentDataB = ['title' => 'Assigned B Title', 'products' => [['name' => 'Product B1']]];
        $contentB = Content::factory()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Assigned Content B',
            'type' => ContentType::WIDGET,
            'content_data' => ['widget_type' => 'RetailProductWidget', 'data' => $contentDataB],
        ]);
        
        // Assign Content B to the zone in the template layout
        $layout = $template->layout;
        $layout['zones']['zone1']['content_id'] = $contentB->id;
        $template->update(['layout' => $layout]);
        $template = $template->fresh(); // Re-fetch template to ensure layout is updated

        // 3. Mount TemplateConfigurator
        $livewireTest = Livewire::test(TemplateConfigurator::class, ['template' => $template])
            ->assertStatus(200);

        // 4. Simulate calling loadAvailablePreviewContent
        $livewireTest->call('loadAvailablePreviewContent', 'zone1')
            ->assertSet("availablePreviewContent.zone1", function ($previewContent) use ($contentA) {
                // Check if Content A is in the available list
                foreach ($previewContent as $item) {
                    if ($item['id'] == $contentA->id) {
                        return true;
                    }
                }
                return false;
            });
            
        // 5. Simulate calling setPreviewData with Content A
        $livewireTest->call('setPreviewData', 'zone1', $contentA->id)
            ->assertSet("zonePreviewContentData.zone1", $contentDataA)
            ->assertSeeHtml($contentDataA['title']); // Check if title from Content A is rendered

        // Check that the preview content is used, not the assigned one
        $this->assertStringContainsString($contentDataA['title'], $livewireTest->html());
        $this->assertStringNotContainsString($contentDataB['title'], $livewireTest->html());

        // 6. Simulate calling setPreviewData with null (clear preview)
        $livewireTest->call('setPreviewData', 'zone1', null)
            ->assertSet("zonePreviewContentData.zone1", null) // Or it might be unset, check component logic
            ->assertSeeHtml($contentDataB['title']); // Should now render assigned Content B

        // Check that the assigned content is now used
        $this->assertStringContainsString($contentDataB['title'], $livewireTest->html());
        $this->assertStringNotContainsString($contentDataA['title'], $livewireTest->html());
    }
}
