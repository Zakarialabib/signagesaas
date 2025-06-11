<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;

class TenantStorageService
{
    public function calculateTenantStorage(Tenant $tenant): float
    {
        // Initialize tenant context
        tenancy()->initialize($tenant);

        $totalSize = 0;

        // Assuming tenant-specific files are stored in a disk named 'tenant_media'
        // and each tenant has a dedicated folder within that disk.
        // The path would typically be 'tenant_id/media_files'
        $tenantDisk = Storage::disk('tenant_media');
        $tenantPath = $tenant->id;

        if ($tenantDisk->exists($tenantPath)) {
            $files = $tenantDisk->allFiles($tenantPath);
            foreach ($files as $file) {
                $totalSize += $tenantDisk->size($file);
            }
        }

        // Return size in MB
        return round($totalSize / (1024 * 1024), 2);
    }
}