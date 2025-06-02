<?php

use App\Http\Controllers\TenantImpersonationController;
use App\Livewire\SuperAdmin\Auth\Login as SuperAdminLogin;
use App\Livewire\SuperAdmin\PlansManager;
use App\Livewire\SuperAdmin\TenantsManager;
use App\Livewire\SuperAdmin\AuditLogManager;
use App\Livewire\SuperAdmin\GlobalSettingsManager;
use App\Livewire\SuperAdmin\SubscriptionManager;
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

    // Plan Management
    Route::get('/plans', PlansManager::class)->name('superadmin.plans');

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

// Tenant impersonation routes
Route::middleware(['web'])->group(function () {
    // Start impersonation with signature verification - note this isn't under superadmin middleware
    // to avoid 404 errors when not logged in
    Route::get('/impersonate/{tenant}/{signature}', [TenantImpersonationController::class, 'impersonate'])
        ->name('direct.impersonate');
});


