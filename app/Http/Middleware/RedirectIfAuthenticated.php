<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Facades\Tenancy;
use Symfony\Component\HttpFoundation\Response;

final class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Check if we're on a tenant domain
                if ($tenant = Tenancy::getTenant()) {
                    return redirect("https://{$tenant->domains->first()->domain}/dashboard");
                }

                // If on central domain, redirect to home
                return redirect('/');
            }
        }

        return $next($request);
    }
}
