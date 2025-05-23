<?php

declare(strict_types=1);

use App\Enums\TemplateCategory;
use App\Livewire\Auth\Login;
use App\Livewire\Pages\Home;
use App\Livewire\Auth\Register;
use App\Livewire\SuperAdmin\Auth\Login as SuperAdminLogin;
use App\Livewire\SuperAdmin\PlansManager;
use App\Livewire\SuperAdmin\TenantsManager;
use App\Livewire\SuperAdmin\AuditLogManager;
use App\Livewire\SuperAdmin\GlobalSettingsManager;
use App\Http\Controllers\TenantImpersonationController;
use App\Livewire\Content\Pages\TvDisplay;
use App\Livewire\Content\Pages\WidgetPage;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

// Language switcher (on central domains)
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
        app()->setLocale($locale);
    }

    return redirect()->back();
})->name('language.switch');

// Check for impersonation token in cookie and login
Route::get('/impersonation-check', function () {
    $impersonationToken = request()->cookie('impersonation_token');

    if ($impersonationToken) {
        [$tenantId, $userId] = explode('|', $impersonationToken);

        // Store in session for use by tenant middleware
        session(['impersonated_tenant' => $tenantId, 'impersonated_user_id' => $userId]);

        // Clear the cookie
        cookie()->queue(cookie()->forget('impersonation_token'));

        // Redirect to the dashboard
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('impersonation.check');

// Tenant impersonation routes
Route::middleware(['web'])->group(function () {
    // Start impersonation with signature verification - note this isn't under superadmin middleware
    // to avoid 404 errors when not logged in
    Route::get('/impersonate/{tenant}/{signature}', [TenantImpersonationController::class, 'impersonate'])
        ->name('direct.impersonate');
});

// End impersonation and return to superadmin
Route::get('/impersonate/stop', [TenantImpersonationController::class, 'endImpersonation'])
    ->middleware(['web', 'auth'])
    ->name('impersonate.stop');

// Auto-login route for impersonation on tenant domains
Route::get('/auto-login', function () {
    return redirect()->route('dashboard');
})
    ->middleware(['web', InitializeTenancyByDomain::class])
    ->name('tenant.auto-login');

// Landing page on central domains
Route::get('/', Home::class)->name('home');

// link auth.php
require __DIR__.'/auth.php';

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
    Route::get('/tenants/{tenant}/subscription', App\Livewire\SuperAdmin\SubscriptionManager::class)
        ->name('superadmin.tenant.subscription');
});

// Public screen preview route (requires screen token validation)
Route::get('/screen/{screen}/preview', App\Http\Controllers\ScreenPreviewController::class)
    ->name('screen.preview');

// Device API routes (used by devices to fetch their content)
Route::prefix('api/device')->group(function () {
    Route::get('/{device}/content', [App\Http\Controllers\Api\DeviceController::class, 'getContent'])
        ->name('api.device.content');
    Route::post('/{device}/ping', [App\Http\Controllers\Api\DeviceController::class, 'ping'])
        ->name('api.device.ping');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/screen-concepts', App\Livewire\Screens\ScreenConcepts::class)
        ->name('screen.concepts');
});

// Template Categories
Route::get('/template-categories', \App\Livewire\TemplateCategories\CategoryShowcase::class)->name('template-categories.index');
Route::get('/template-categories/{category}', \App\Livewire\TemplateCategories\CategoryDetail::class)->name('template-category.show');
Route::get('/templates/{template}/preview', \App\Livewire\TemplateCategories\TemplatePreview::class)->name('templates.preview');


// TV Routes
Route::prefix('tv')->group(function () {
    // Full dashboard page (all widgets)
    Route::get('display', TvDisplay::class)
        ->name('tenant.tv.display');

    // Single widget page by category
    Route::get('widget/{category}', WidgetPage::class)
        ->where('category', implode('|', array_column(TemplateCategory::cases(), 'value')))
        ->name('tenant.tv.widget');
});



