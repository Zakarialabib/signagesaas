<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Facades\Settings;
use App\Tenant\Models\Tenant;
use Illuminate\Console\Command;
use Stancl\Tenancy\Facades\Tenancy;

class TestTenantSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:tenant-settings {tenant? : The ID of the tenant to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test tenant settings functionality';

    /** Execute the console command. */
    public function handle()
    {
        $tenantId = $this->argument('tenant') ?? 'demo';

        $tenant = Tenant::find($tenantId);

        if ( ! $tenant) {
            $this->error("Tenant '{$tenantId}' not found");

            return 1;
        }

        $this->info("Testing settings for tenant: {$tenant->id}");

        // Check raw settings in tenant model
        $this->info('Settings stored in tenant model:');
        $this->table(['Key', 'Value'], $this->formatSettings($tenant->settings ?? []));

        // Initialize tenancy to test the Settings facade
        Tenancy::initialize($tenant);

        $this->info('Settings available through Settings facade:');
        $allSettings = Settings::getAll();
        $this->table(['Key', 'Value'], $this->formatSettings($allSettings));

        // Test specific settings
        $this->info('Testing specific settings:');
        $testKeys = ['siteName', 'timezone', 'dateFormat', 'locale', 'primaryColor'];

        foreach ($testKeys as $key) {
            $value = Settings::get($key, 'Not set');
            $this->line("- {$key}: {$value}");
        }

        return 0;
    }

    /**
     * Format settings for table output.
     *
     * @param array $settings
     * @return array
     */
    private function formatSettings(array $settings): array
    {
        $formatted = [];

        foreach ($settings as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } elseif (is_array($value)) {
                $value = json_encode($value);
            }

            $formatted[] = [$key, (string) $value];
        }

        return $formatted;
    }
}
