<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Import Livewire components
use App\Livewire\Content\Templates\TemplateGallery;
use App\Livewire\Dashboard\Index;
use App\Livewire\Dashboard\UsageAnalytics;
use App\Livewire\Devices\DeviceManager;
use App\Livewire\Devices\IntegrationGuide;
use App\Livewire\Screens\ScreenManager;
use App\Livewire\Screens\ScreenPreview;
use App\Livewire\Screens\ScreenDisplay;
use App\Livewire\Content\ContentManager;
use App\Livewire\Content\Templates\TemplateManager;
use App\Livewire\Schedules\ScheduleManager;
use App\Livewire\Settings\ProfileSettings;
use App\Livewire\Settings\SubscriptionManager;
use App\Livewire\Settings\TenantSettingsManager;
use App\Livewire\Settings\UserManager;
use App\Livewire\Content\Pages\TvDisplay;
use App\Livewire\Content\Pages\WidgetPage;
use App\Enums\TemplateCategory;
use App\Http\Controllers\ScreenPreviewController;
use App\Http\Controllers\TenantImpersonationController;

// Language switcher (on tenant domains)
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
        app()->setLocale($locale);
    }

    return redirect()->back();
})->name('language.switch');

// Dashboard routes
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', Index::class)->name('dashboard');
    Route::get('/analytics', UsageAnalytics::class)->name('dashboard.analytics');
});

// Device management routes
Route::middleware(['auth'])->prefix('devices')->group(function () {
    Route::get('/', DeviceManager::class)->name('devices.index');
    Route::get('/integration', IntegrationGuide::class)->name('devices.integration');
});

// Screen management routes
Route::middleware(['auth'])->prefix('screens')->group(function () {
    Route::get('/', ScreenManager::class)->name('screens.index');
    Route::get('/{screen}/preview', ScreenPreview::class)->name('screen.preview');
    Route::get('/{screen}/display', ScreenDisplay::class)->name('screen.display');
});

// Content management routes
Route::middleware(['auth'])->prefix('content')->group(function () {
    Route::get('/', ContentManager::class)->name('content.index');
    Route::get('/templates', TemplateManager::class)->name('content.template.index');
    Route::get('/templates/gallery', TemplateGallery::class)->name('content.templates.gallery');
});

// Schedule management routes
Route::middleware(['auth'])->prefix('schedules')->group(function () {
    Route::get('/', ScheduleManager::class)->name('schedules.index');
});

// Settings routes
Route::middleware(['auth'])->prefix('settings')->group(function () {
    Route::get('/', TenantSettingsManager::class)->name('settings.general');
    Route::get('/profile', ProfileSettings::class)->name('settings.profile');
    Route::get('/subscription', SubscriptionManager::class)->name('settings.subscription');
    Route::get('/users', UserManager::class)->name('settings.users');
});

// Public screen preview route (requires screen token validation)

Route::get('/screen/{screen}/preview', ScreenPreviewController::class)
    ->name('screen.preview');

// End impersonation and return to superadmin

Route::get('/impersonate/stop', [TenantImpersonationController::class, 'endImpersonation'])
    ->middleware(['auth'])
    ->name('impersonate.stop');

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
