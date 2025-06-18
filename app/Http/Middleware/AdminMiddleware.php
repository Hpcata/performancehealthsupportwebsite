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
        // Skip middleware for login routes
        if ($request->routeIs('index') || $request->routeIs('login') || $request->routeIs('register') || 
            $request->routeIs('forgot-password') || $request->routeIs('reset-password') ||
            $request->routeIs('forgot-password-post') || $request->routeIs('reset-password-post')) {
            return $next($request);
        }

        // Check if user is authenticated
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('index')->with('error', 'Please login first.');
        }

        // Check if user is superadmin
        if (Auth::guard('admin')->user()->is_superadmin != 1) {
            Auth::guard('admin')->logout();
            return redirect()->route('index')->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
