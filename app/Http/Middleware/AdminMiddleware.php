<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure the default guard is set to 'admin' for this request
        Auth::shouldUse('admin'); // <-- This is the critical line

        // Skip middleware for login routes
        if ($request->routeIs('admin.auth.login.index') || $request->routeIs('admin.auth.login.submit') || $request->routeIs('admin.auth.register.index') ||
            $request->routeIs('admin.auth.forgot.index') || $request->routeIs('admin.auth.reset.index') ||
            $request->routeIs('admin.auth.forgot.submit') || $request->routeIs('admin.auth.reset.submit')) {
            return $next($request);
        }

        // Check if user is authenticated
        if (! Auth::guard('admin')->check()) {
            return redirect()->route('admin.auth.login.index')->with('error', 'Please login first.');
        }

        // Check if user is superadmin
        if (Auth::guard('admin')->user()->is_superadmin != 1) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.auth.login.index')->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
