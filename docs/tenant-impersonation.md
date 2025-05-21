# Tenant Impersonation System

This document explains how to set up and use the tenant impersonation functionality in the SignageSaaS application.

## Overview

The tenant impersonation feature allows SuperAdmin users to log in as a tenant's admin user to assist with troubleshooting, configuration, or any other tenant-specific tasks. When impersonating a tenant, the SuperAdmin will see the application from the tenant's perspective with their permissions and data.

## Components

The tenant impersonation system consists of:

1. **TenantImpersonationController** - Handles the impersonation process, including signature verification
2. **TenantImpersonationMiddleware** - Performs automatic login on tenant domains
3. **TenantImpersonationServiceProvider** - Handles tenant impersonation at the application level
4. **Impersonation Banner** - Visual indicator when impersonation is active

## Local Development Setup

For local development with subdomains to work properly:

### 1. Create hosts file entries

You can either:

a) Run our setup script (requires admin privileges):

```bash
php artisan tinker scripts/setup_hosts.php
```

b) Manually add entries to your hosts file (`C:\Windows\System32\drivers\etc\hosts`):

```
127.0.0.1 signagesaas.test
127.0.0.1 tenant1.signagesaas.test
127.0.0.1 tenant2.signagesaas.test
# Add more tenant subdomains as needed
```

### 2. Configure Apache/Nginx for wildcard subdomains

For Laragon:

1. Copy the configuration file to Laragon:
    ```bash
    cp docker/apache/signagesaas.conf C:/laragon/etc/apache2/sites-enabled/
    ```
2. Restart Laragon

For manual Apache setup:

1. Enable the vhost_alias module: `a2enmod vhost_alias`
2. Add the virtual host configuration to your Apache config

## Usage

### For SuperAdmins

1. Log in to the SuperAdmin dashboard at `http://signagesaas.test/superadmin`
2. Navigate to the Tenants management page
3. Click the "Impersonate" icon (user silhouette) for the tenant you want to impersonate
4. Confirm the impersonation in the dialog
5. You will be redirected to the tenant's domain and logged in automatically

### Exiting Impersonation

When impersonating a tenant, an amber-colored banner appears at the top of every page with:

- The name of the tenant being impersonated
- A "Return to SuperAdmin" button to exit impersonation

Click the "Return to SuperAdmin" button to end the impersonation session and return to the SuperAdmin dashboard.

## Security Considerations

The impersonation system implements several security measures:

1. **HMAC Signatures** - Impersonation requests are signed with the application key to prevent tampering
2. **Session-based Auth** - Impersonation state is stored in the session, not in cookies or URLs
3. **Visual Indicators** - A prominent banner always shows when impersonation is active
4. **Automatic Authorization** - All tenant actions follow the normal tenant permission system
5. **Domain Validation** - Impersonation only works on valid tenant domains

## Troubleshooting

### Common Issues

1. **404 Not Found Error**

    - Make sure your tenant has a domain configured
    - Verify that the tenant exists in the database
    - Check that the route is correctly registered (not hidden behind middleware)

2. **Signature Mismatch**

    - Verify that your app key is consistent
    - Make sure the tenant ID is correct

3. **Not Being Logged In Automatically**

    - Check that the TenantImpersonationMiddleware is properly registered
    - Make sure there are admin users in the tenant's database
    - Check the Laravel logs for any authentication errors

4. **Domain Not Working**
    - Verify your hosts file has the correct entries
    - Make sure Apache/Nginx is configured for wildcard subdomains
    - Restart your web server after configuration changes

### Debugging Steps

1. Check the Laravel logs at `storage/logs/laravel.log` for any errors
2. Try executing each step manually:

    - Visit `/impersonate/{tenant-id}/{signature}` directly
    - Check if session variables are being set correctly
    - Verify that users exist for the tenant

3. Advanced: Create a test route to debug the impersonation process:
    ```php
    Route::get('/debug-impersonation/{tenant}', function($tenant) {
        // Debug code here
    });
    ```

## Related Files

- `app/Http/Controllers/TenantImpersonationController.php`
- `app/Http/Middleware/TenantImpersonationMiddleware.php`
- `app/Providers/TenantImpersonationServiceProvider.php`
- `resources/views/components/impersonation-banner.blade.php`
- `docker/apache/signagesaas.conf`
- `scripts/setup_hosts.php`
