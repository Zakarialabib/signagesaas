<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Facades\Settings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class TenantSettingsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->tenant) {
            $this->applySettings();
        }

        return $next($request);
    }

    /**
     * Apply tenant settings to the current request.
     *
     * @return void
     */
    private function applySettings(): void
    {
        // Set timezone if available
        $timezone = Settings::get('timezone');

        if ($timezone) {
            Config::set('app.timezone', $timezone);
            date_default_timezone_set($timezone);
        }

        // Set locale if available
        $locale = Settings::get('locale');

        if ($locale && in_array($locale, config('app.available_locales', ['en']))) {
            App::setLocale($locale);
        }

        // Apply other necessary settings
        // ...
    }
}
