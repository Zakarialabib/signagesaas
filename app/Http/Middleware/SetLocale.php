<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            $preferredLanguage = $request->getPreferredLanguage(['en', 'ar']);
            app()->setLocale($preferredLanguage ?? 'en');
            session()->put('locale', app()->getLocale());
        }

        return $next($request);
    }
}
