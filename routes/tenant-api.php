<?php

declare(strict_types=1);

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\DeviceAuthController;
use App\Http\Controllers\Api\DeviceHeartbeatController;
use App\Http\Controllers\Api\DeviceContentController;
use App\Http\Controllers\Api\DeviceUpdateController;
use App\Http\Controllers\Api\DeviceMetricsController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\ScreenController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

// Public device authentication endpoints (no tenant context required)
Route::post('device/register', [DeviceAuthController::class, 'register'])
    ->name('api.tenant.device.register');
Route::post('device/authenticate', [DeviceAuthController::class, 'authenticate'])
    ->name('api.tenant.device.authenticate');

// Protected device API endpoints (require device authentication)
Route::middleware(['auth:sanctum', 'App\Http\Middleware\DeviceAuthentication'])->group(function () {
    
    // Core device management endpoints
    Route::apiResource('devices', DeviceController::class)
        ->names([
            'index' => 'api.tenant.devices.index',
            'store' => 'api.tenant.devices.store',
            'show' => 'api.tenant.devices.show',
            'update' => 'api.tenant.devices.update',
            'destroy' => 'api.tenant.devices.destroy',
        ]);
    
    // Device operational endpoints
    Route::prefix('device')->name('api.tenant.device.')->group(function () {
        // Content and sync endpoints
        Route::get('{device}/content', [DeviceController::class, 'getContent'])
            ->name('content');
        Route::post('{device}/ping', [DeviceController::class, 'ping'])
            ->name('ping');
        
        // Heartbeat endpoint
        Route::post('heartbeat/{device}', [DeviceHeartbeatController::class, 'heartbeat'])
            ->name('heartbeat');
        
        // Content sync endpoints
        Route::prefix('content')->name('content.')->group(function () {
            Route::get('sync/{device}', [DeviceContentController::class, 'sync'])
                ->name('sync');
            Route::get('media/{device}/{content}', [DeviceContentController::class, 'downloadMedia'])
                ->middleware('signed')
                ->name('media');
        });
        
        // OTA update endpoints
        Route::prefix('update')->name('update.')->group(function () {
            Route::get('check/{device}', [DeviceUpdateController::class, 'checkUpdates'])
                ->name('check');
            Route::get('download/{device}', [DeviceUpdateController::class, 'downloadUpdate'])
                ->middleware('signed')
                ->name('download');
        });
        
        // Device metrics endpoints
        Route::prefix('metrics')->name('metrics.')->group(function () {
            Route::get('{device}', [DeviceMetricsController::class, 'index'])
                ->name('index');
            Route::get('{device}/latest', [DeviceMetricsController::class, 'latest'])
                ->name('latest');
            Route::get('{device}/summary', [DeviceMetricsController::class, 'summary'])
                ->name('summary');
        });
    });

    // Content management endpoints
    Route::apiResource('content', ContentController::class)
        ->names([
            'index' => 'api.tenant.content.index',
            'store' => 'api.tenant.content.store',
            'show' => 'api.tenant.content.show',
            'update' => 'api.tenant.content.update',
            'destroy' => 'api.tenant.content.destroy',
        ]);
    
    // Screen management endpoints
    Route::apiResource('screens', ScreenController::class)
        ->names([
            'index' => 'api.tenant.screens.index',
            'store' => 'api.tenant.screens.store',
            'show' => 'api.tenant.screens.show',
            'update' => 'api.tenant.screens.update',
            'destroy' => 'api.tenant.screens.destroy',
        ]);
    
    // Settings API endpoints
    Route::prefix('settings')->name('api.tenant.settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])
            ->name('index');
        Route::get('{key}', [SettingController::class, 'show'])
            ->name('show');
        Route::patch('/', [SettingController::class, 'update'])
            ->name('update');
    });
});
