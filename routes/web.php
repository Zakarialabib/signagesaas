<?php

declare(strict_types=1);

use App\Enums\TemplateCategory;
use App\Livewire\Auth\Login;
use App\Livewire\Pages\Home;
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
require __DIR__.'/super-admin.php';
require __DIR__.'/auth.php';

// Public screen preview route (requires screen token validation)
Route::get('/screen/{screen}/preview', App\Http\Controllers\ScreenPreviewController::class)
    ->name('screen.preview');

Route::middleware(['auth'])->group(function () {
    Route::get('/screen-concepts', App\Livewire\Screens\ScreenConcepts::class)
        ->name('screen.concepts');
});

// Template Categories
Route::get('/template-categories', App\Livewire\TemplateCategories\CategoryShowcase::class)->name('template-categories.index');
Route::get('/template-categories/{category}', App\Livewire\TemplateCategories\CategoryDetail::class)->name('template-category.show');
Route::get('/templates/{template}/preview', App\Livewire\TemplateCategories\TemplatePreview::class)->name('templates.preview');

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
