<?php

namespace Tests\Unit\Livewire\Content\Widgets;

use App\Livewire\Content\Widgets\WidgetDataEditorModal;
use App\Models\Tenant;
use App\Models\User;
use App\Enums\ContentType;
use App\Tenant\Models\Content; // Assuming tenant models are in App\Tenant\Models
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant; // Import the trait

class WidgetDataEditorModalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Manually create a central user for global context if needed, or set up tenancy.
        // For this test, we'll focus on tenant context.
    }

    private function initializeTenantWithUser(): User
    {
        $tenant = Tenant::create(['id' => 'test_tenant']);
        $tenant->domains()->create(['domain' => 'test.localhost']);
        tenancy()->initialize($tenant);

        // Create a user and associate with the tenant
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $this->actingAs($user);
        
        return $user;
    }

    /** @test */
    public function testSaveNewWidgetContentWithTypeWidget()
    {
        $this->initializeTenantWithUser();

        $widgetType = 'RetailProductWidget';
        $contentName = 'My Awesome Retail Product Widget';
        $widgetSpecificData = [
            'title' => 'Featured Gadgets',
            'products' => [
                ['name' => 'Gadget X', 'price' => '99.99']
            ],
            'footer_promo_text' => 'Limited time offer!'
        ];

        Livewire::test(WidgetDataEditorModal::class)
            ->call('handleOpenWidgetDataEditor', null, $widgetType, null) // zoneId, widgetType, contentId
            ->set('contentName', $contentName)
            ->set('widgetData.data', $widgetSpecificData) // Set the 'data' part of widgetData
            // widgetData.widget_type is set internally by initializeWidgetDataForEdit or when loading
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('contents', [
            'name' => $contentName,
            'type' => ContentType::WIDGET->value,
            // We need to check the JSON content_data field
        ]);

        $createdContent = Content::where('name', $contentName)->first();
        $this->assertNotNull($createdContent);
        $this->assertEquals(ContentType::WIDGET, $createdContent->type);
        $this->assertIsArray($createdContent->content_data);
        $this->assertEquals($widgetType, $createdContent->content_data['widget_type']);
        $this->assertEquals($widgetSpecificData, $createdContent->content_data['data']);
    }
}
