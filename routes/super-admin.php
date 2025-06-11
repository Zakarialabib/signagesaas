<?php

use App\Http\Controllers\TenantImpersonationController;
use App\Livewire\Settings\TenantSettingsManager;
use App\Livewire\SuperAdmin\Auth\Login as SuperAdminLogin;
use App\Livewire\SuperAdmin\Dashboard;
use App\Livewire\SuperAdmin\TenantsManager;
use App\Livewire\SuperAdmin\TenantHealth;
use App\Livewire\SuperAdmin\PlansManager;
use App\Livewire\SuperAdmin\DeviceManager;
use App\Livewire\SuperAdmin\AuditLogManager;
use App\Livewire\SuperAdmin\CreateTenant;
use App\Livewire\SuperAdmin\GlobalSettingsManager;
use App\Livewire\SuperAdmin\SubscriptionManager;
use App\Livewire\SuperAdmin\UsersManager;
use Illuminate\Support\Facades\Route;

// SuperAdmin Authentication
Route::middleware(['web'])->prefix('superadmin')->group(function () {
    Route::get('/login', SuperAdminLogin::class)->name('superadmin.login');
});

// Super Admin Routes
Route::middleware(['web', 'superadmin'])->prefix('superadmin')->group(function () {
    // Dashboard
    Route::get('/', App\Livewire\SuperAdmin\Dashboard::class)->name('superadmin.dashboard');

    // Tenant Management
    Route::get('/tenants', TenantsManager::class)->name('superadmin.tenants');
    Route::get('/tenants/create', CreateTenant::class)->name('tenants.create');
    Route::get('/tenants/{tenant}/settings', TenantSettingsManager::class)->name('tenants.settings');
    Route::get('/tenants/settings', TenantSettingsManager::class)->name('tenants.settings.list');
    Route::get('/users', UsersManager::class)->name('superadmin.users');
    Route::get('/tenants/{tenant}/users', UsersManager::class)->name('superadmin.tenant.users');
    Route::get('/tenants/{tenant}/health', TenantHealth::class)->name('superadmin.tenant.health');

    // Plan Management
    Route::get('/plans', PlansManager::class)->name('superadmin.plans');

    // Device Management
    Route::get('/devices', DeviceManager::class)->name('superadmin.devices');

    // Audit Logs
    Route::get('/audit-logs', AuditLogManager::class)->name('superadmin.audit-logs');

    // Global Settings
    Route::get('/settings', GlobalSettingsManager::class)->name('superadmin.settings');

    // Subscription Management (shown within tenant detail view)
    Route::get('/tenants/{tenant}/subscription', SubscriptionManager::class)
        ->name('superadmin.tenant.subscription');
});

// Check for impersonation token in cookie and login
Route::get('/impersonation-check', [TenantImpersonationController::class, 'impresonateCheck'])->name('impersonation.check');
