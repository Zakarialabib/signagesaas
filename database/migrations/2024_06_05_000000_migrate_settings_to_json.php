<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migration. */
    public function up(): void
    {
        // Get all tenants
        $tenants = DB::table('tenants')->get();

        // For each tenant, migrate their settings
        foreach ($tenants as $tenant) {
            // Get current tenant settings
            $currentSettings = json_decode($tenant->settings ?? '{}', true) ?: [];

            // Get settings from the settings table for this tenant
            if (Schema::hasTable('settings')) {
                $tableSettings = DB::table('settings')
                    ->where('tenant_id', $tenant->id)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        // Format the value - handle booleans, numbers, etc.
                        $value = $item->value;

                        // Try to detect if it's a boolean
                        if ($value === '1' || $value === '0' || $value === 'true' || $value === 'false') {
                            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                        }
                        // Try to detect if it's an integer
                        elseif (is_numeric($value) && intval($value) == $value) {
                            $value = intval($value);
                        }

                        return [$item->key => $value];
                    })
                    ->toArray();

                // Merge with existing settings (new settings take precedence)
                $mergedSettings = array_merge($currentSettings, $tableSettings);

                // Update tenant with new settings
                DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['settings' => json_encode($mergedSettings)]);
            }
        }
    }

    /** Reverse the migration. */
    public function down(): void
    {
        // No way to reverse this migration accurately
    }
};
