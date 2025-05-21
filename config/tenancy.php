<?php

declare(strict_types=1);

use Stancl\Tenancy\Database\Models\Domain;
use App\Tenant\Models\Tenant;

return [
    'tenant_model' => \App\Tenant\Models\Tenant::class,
    'domain_model' => \Stancl\Tenancy\Database\Models\Domain::class,

    'bootstrappers' => [
        \Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        \Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        \Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        \Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
    ],

    'features' => [
        \Stancl\Tenancy\Features\TenantConfig::class,
        \Stancl\Tenancy\Features\UniversalRoutes::class,
        \Stancl\Tenancy\Features\ViteBundler::class,
    ],

    'storage_driver' => 'db',

    'central_domains' => [
        'signagesaas.test'
    ],

    'database' => [
        'central_connection' => env('DB_CONNECTION', 'mysql'),
        'tenant_connection_name' => 'tenant',
    ],

    'cache' => [
        'tag_base' => 'tenant',
    ],

    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => ['local', 'public', 's3'],
        'root_override' => [
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],
    ],
];