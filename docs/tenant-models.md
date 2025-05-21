# Tenant Models Configuration

This document explains how to manage tenant-specific models in the application.

## Overview

Our multi-tenant application uses the [stancl/tenancy](https://tenancyforlaravel.com/) package for tenant separation. Models are a key part of this separation, and we use two approaches:

1. **BelongsToTenant trait**: This adds automatic tenant scoping to models
2. **Namespace separation**: Models in `App\Tenant\Models` namespace

## Directory Structure

```
app/
├── Models/                 # Central/global models (Tenant, User, etc.)
├── Providers/
│   ├── TenancyServiceProvider.php  # Core tenancy functionality
│   └── TenantServiceProvider.php   # Tenant model bindings
└── Tenant/
    ├── Models/             # Tenant-specific models
    ├── Livewire/           # Tenant-specific Livewire components
    ├── Middleware/         # Tenant-specific middleware
    ├── Scopes/             # Tenant-specific query scopes
    └── Routes/             # Tenant-specific route configuration
```

## Moving Models to Tenant Namespace

We provide commands to automate the process of moving models to the tenant namespace:

```bash
# Move models from app/Models to app/Tenant/Models
php artisan tenant:move-models

# Exclude specific models
php artisan tenant:move-models --exclude=User,Role,Permission

# Move a specific model
php artisan tenant:move-models app/Models/Product.php
```

## Updating References

After moving models, you need to update references to them throughout your codebase:

```bash
# Automatically update references (use --dry-run first to see changes)
php artisan tenant:configure-models --dry-run

# Then run it for real
php artisan tenant:configure-models

# Specify a specific path to update
php artisan tenant:configure-models --path=app/Livewire
```

## Proper Implementation of BelongsToTenant

### How BelongsToTenant Works

The `BelongsToTenant` trait from stancl/tenancy provides:

1. Automatic `tenant_id` column addition to model operations
2. Automatic tenant scoping on all queries
3. An implicit relationship to the tenant model

### Correct Model Structure

```php
<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Product extends Model
{
    use BelongsToTenant;

    // No need to define a tenant() method - it's provided by the trait

    // The trait automatically adds tenant_id to these arrays if missing
    protected $fillable = ['name', 'price'];

    // Rest of your model...
}
```

### What NOT to Do

❌ **Don't** manually define a tenant relationship when using the trait:

```php
// WRONG - conflicts with the BelongsToTenant trait
public function tenant()
{
    return $this->belongsTo(Tenant::class);
}
```

❌ **Don't** manually add tenant filters in queries:

```php
// WRONG - redundant with BelongsToTenant
Product::where('tenant_id', tenant('id'))->get();
```

### What You SHOULD Do

✅ Use the trait and let it handle the relationship:

```php
// The trait automatically adds global scope
$products = Product::all(); // Already tenant-scoped
```

✅ Use automatic tenant_id assignment:

```php
// The trait automatically adds tenant_id
$product = Product::create(['name' => 'Example Product']);
```

## Creating Tenant Migrations

All tenant model tables must have a `tenant_id` column with a foreign key constraint to ensure data integrity. We provide a command to generate properly structured tenant migrations:

```bash
# Create a migration for a new tenant model
php artisan tenant:migration create_products

# Specify the table name explicitly
php artisan tenant:migration create_products --table=products

# Create a migration based on an existing model
php artisan tenant:migration create_products --model=Product
```

### Migration Structure

The generated migrations include:

- UUID primary key
- Properly typed `tenant_id` column
- Foreign key constraint to the tenants table
- Soft deletes (optional but recommended)

Example migration:

```php
Schema::create('products', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('tenant_id');
    // Your columns go here

    $table->timestamps();
    $table->softDeletes();

    // Foreign key constraint
    $table->foreign('tenant_id')
        ->references('id')
        ->on('tenants')
        ->onDelete('cascade');
});
```

### Checking Existing Migrations

Ensure all tenant-scoped models have tables with:

1. A `tenant_id` column matching the tenant ID type (string/uuid)
2. A foreign key constraint to the tenants table

If needed, create migration fixes:

```bash
php artisan make:migration add_tenant_id_foreign_key_to_products_table
```

Then implement the foreign key:

```php
public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        // Add foreign key constraint if not exists
        $table->foreign('tenant_id')
            ->references('id')
            ->on('tenants')
            ->onDelete('cascade');
    });
}
```

## Service Providers

Our application uses multiple providers to manage tenancy:

1. **TenancyServiceProvider**: Core integration with the stancl/tenancy package

    - Registers event listeners for tenancy lifecycle
    - Registers the TenantServiceProvider during initialization
    - Configures tenant-specific settings (timezone, locale)

2. **TenantServiceProvider**: Handles tenant model bindings

    - Manages model dependency injection bindings
    - Registers when tenancy is initialized
    - Automatically resolves `App\Models\*` to `App\Tenant\Models\*`

3. **Tenant RouteServiceProvider**: Configures route model binding for tenant models
    - Automatically binds route parameters to tenant models
    - Added to `bootstrap/tenant-providers.php` for automatic loading

### Provider Registration

The necessary providers are already registered in `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenancyServiceProvider::class, // Core tenancy provider
    App\Providers\TenantServiceProvider::class,  // Tenant model bindings provider
    // Other providers...
];
```

## Routes and Route Model Binding

When using the tenant models, route model binding works automatically thanks to the `RouteServiceProvider`:

```php
// routes/tenant.php
Route::get('/devices/{device}', function (App\Tenant\Models\Device $device) {
    // $device is automatically resolved to a tenant-scoped Device model
    return view('devices.show', compact('device'));
});
```

The `RouteServiceProvider` handles binding both singular and plural parameter names:

- `/users/{user}` will resolve to `App\Tenant\Models\User`
- `/products/{product}` will resolve to `App\Tenant\Models\Product`

## Database Schema Requirements

For models using the `BelongsToTenant` trait, you need:

1. A `tenant_id` column in the model's table
2. A foreign key constraint to the tenants table (recommended)

Example migration:

```php
Schema::create('devices', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('tenant_id');
    $table->string('name');
    // ... other columns
    $table->timestamps();

    $table->foreign('tenant_id')
        ->references('id')
        ->on('tenants')
        ->onUpdate('cascade')
        ->onDelete('cascade');
});
```

## Best Practices

1. **Use Dependency Injection**: Prefer constructor injection over static methods
2. **Use model binding**: Let Laravel resolve models from route parameters
3. **Be consistent**: Don't mix `App\Models` and `App\Tenant\Models` in tenant code
4. **Let the trait work**: Don't override or duplicate functionality from `BelongsToTenant`

## Troubleshooting

### Common Issues

1. **Model not found**: Ensure the namespace is correct and the model exists
2. **Tenant not automatically scoped**: Ensure the model uses BelongsToTenant trait
3. **Mixed models**: If you see models from both App\Models and App\Tenant\Models, check your bindings
4. **Route binding not working**: Verify that the tenant RouteServiceProvider is registered
5. **Tenant relationship error**: Make sure you're not defining a tenant() method when using BelongsToTenant

### Debugging Tips

```php
// Check what model class you're using
dd(get_class($model));

// Check tenant context
dd(tenant());

// Check tenant global scope is applied to the query
dd(Product::toSql()); // Should include WHERE tenant_id = ?

// Debug route model binding
Route::get('/debug/{device}', function ($device) {
    dd($device);
});
```

## When to Use Central vs Tenant Models

- **Central Models**: Global entities shared across tenants (Tenant, Plan, GlobalSettings)
- **Tenant Models**: Anything specific to a tenant (User, Product, Order, etc.)

## Migrating Existing Code

Follow this process for migrating existing code:

1. First, move models: `php artisan tenant:move-models`
2. Update references: `php artisan tenant:configure-models`
3. Test each feature to ensure it works with tenant models
4. Fix any remaining issues manually

## Moving Livewire Components

Similarly, you can move Livewire components:

```bash
php artisan tenant:move-livewire
```
