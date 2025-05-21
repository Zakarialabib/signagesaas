# Tenant Settings System

## Overview

The SignageSaaS platform uses a JSON-based tenant settings system that allows each tenant to maintain their own configuration values. This approach is more efficient than traditional table-based settings, as it eliminates the need for extra database queries and joins.

## Technical Implementation

### Storage

Settings are stored in the `settings` JSON column of the `tenants` table. This allows us to:

- Store all settings in a single location per tenant
- Retrieve all settings with a single database query
- Update settings in a single operation
- Eliminate the need for migrations when adding new settings

### Components

The settings system consists of the following components:

1. **SettingsHelper** (`app/Support/SettingsHelper.php`)

    - Core utility class for working with tenant settings
    - Handles caching to improve performance
    - Methods for getting, updating, and checking settings

2. **Settings Facade** (`app/Facades/Settings.php`)

    - Provides a convenient way to access settings throughout the application
    - Available methods: `get()`, `getAll()`, `has()`, `update()`

3. **TenantSettingsMiddleware** (`app/Http/Middleware/TenantSettingsMiddleware.php`)

    - Applies settings at the beginning of each request
    - Sets application-level configuration based on tenant settings
    - Configures timezone, locale, etc.

4. **TenantSettingsManager** (`app/Livewire/Settings/TenantSettingsManager.php`)

    - Livewire component for the settings UI
    - Handles form validation and saving settings
    - Includes permission checks for viewing and editing

5. **TenantObserver** (`app/Observers/TenantObserver.php`)

    - Ensures new tenants start with default settings
    - Automatically applies defaults during tenant creation

6. **TenantPolicy** (`app/Policies/TenantPolicy.php`)
    - Handles authorization for viewing and updating tenant settings

## Using the Settings System

### Getting Settings

```php
// Using the facade
$siteName = \App\Facades\Settings::get('siteName', 'Default Site Name');

// Getting all settings
$allSettings = \App\Facades\Settings::getAll();

// Checking if a setting exists
if (\App\Facades\Settings::has('customLogo')) {
    // Do something with the custom logo
}
```

### Updating Settings

```php
// Update a single setting or multiple settings
\App\Facades\Settings::update([
    'siteName' => 'New Site Name',
    'primaryColor' => '#4f46e5',
]);
```

## Available Settings

| Key                        | Type    | Description                   | Default        |
| -------------------------- | ------- | ----------------------------- | -------------- |
| siteName                   | string  | The name of the tenant's site | SignageSaaS    |
| contactEmail               | string  | Primary contact email         | System default |
| timezone                   | string  | Tenant timezone               | UTC            |
| dateFormat                 | string  | Date display format           | Y-m-d          |
| timeFormat                 | string  | Time display format           | H:i            |
| locale                     | string  | Interface language            | en             |
| primaryColor               | string  | Primary brand color           | #4f46e5        |
| secondaryColor             | string  | Secondary brand color         | #9ca3af        |
| darkModeDefault            | boolean | Default to dark mode          | false          |
| defaultScreenDuration      | integer | Default duration in seconds   | 15             |
| defaultTransition          | string  | Default screen transition     | fade           |
| notificationsEnabled       | boolean | Enable notifications          | true           |
| deviceOfflineAlerts        | boolean | Alert when devices go offline | true           |
| contentUpdateNotifications | boolean | Notify on content updates     | true           |
| securityAlerts             | boolean | Send security notifications   | true           |

## Permissions

These permissions control access to the settings system:

- `settings.view`: Allows a user to view tenant settings
- `settings.edit`: Allows a user to modify tenant settings

## Utilities

The following commands are available for working with settings:

```bash
# Update tenant settings with default values
php artisan tenant:update-settings

# Test settings system
php artisan test:tenant-settings

# List all permissions (including settings-related ones)
php artisan permissions:list settings
```
