<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Tenant\Models\Content; // Ensure this path is correct for your Content model
use App\Enums\ContentType;     // Ensure this path is correct for your ContentType enum
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define a list of known widget_type identifiers
        // These are the values found in content_data['widget_type'] that signify a custom content is actually a widget
        $knownWidgetTypes = [
            'MenuWidget',
            'RetailProductWidget',
            'WeatherWidget', // Assuming this was stored with type CUSTOM and widget_type 'WeatherWidget'
            'ClockWidget',
            'AnnouncementWidget',
            'RssFeedWidget',
            'CalendarWidget',
            // Add any other existing widget_type strings that were used with ContentType::CUSTOM
        ];

        // It's safer to update in chunks if there's a lot of content
        Content::where('type', ContentType::CUSTOM->value)
            ->chunkById(100, function ($contents) use ($knownWidgetTypes) {
                foreach ($contents as $content) {
                    // Check if 'widget_type' exists in content_data and is one of the known types
                    if (isset($content->content_data['widget_type']) && 
                        is_string($content->content_data['widget_type']) && // Ensure it's a string before in_array
                        in_array($content->content_data['widget_type'], $knownWidgetTypes)) {
                        
                        // Use DB::table for direct update to avoid model events/casting issues during migration
                        // and to prevent updated_at from being automatically changed if not desired.
                        DB::table('contents')
                            ->where('id', $content->id)
                            ->update(['type' => ContentType::WIDGET->value]);
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This attempts to revert WIDGET types back to CUSTOM.
        // This is a broad revert. It assumes any content item with type WIDGET
        // and a 'widget_type' in its content_data should go back to CUSTOM.
        // This might not perfectly restore the original state if some WIDGET types
        // were created directly as WIDGET after this migration was applied.
        Content::where('type', ContentType::WIDGET->value)
            ->chunkById(100, function ($contents) {
                foreach ($contents as $content) {
                    // Only revert if it seems to have been a migrated custom widget
                    if (isset($content->content_data['widget_type'])) {
                        DB::table('contents')
                            ->where('id', $content->id)
                            ->update(['type' => ContentType::CUSTOM->value]);
                    }
                }
            });
    }
};
