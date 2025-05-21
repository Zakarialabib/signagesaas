<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if user is authenticated
        if ( ! Auth::check()) {
            return redirect()->route('login');
        }

        // Super admins bypass permission checks
        if (Auth::guard('superadmin')->check()) {
            return $next($request);
        }

        // Check if user has the required permission
        if ( ! Auth::user()->hasPermissionTo($permission)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
