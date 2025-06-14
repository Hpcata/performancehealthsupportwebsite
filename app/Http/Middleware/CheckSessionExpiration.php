<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckSessionExpiration
{
    public function handle(Request $request, Closure $next)
    {
        // Skip session check for API routes
        if ($request->is('api/*') || $request->is('*/api/*')) {
            return $next($request);
        }

        // Check if user is authenticated
        if (!Auth::guard('web')->check()) {
            // Check if the request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Session expired',
                    'message' => 'Your session has expired. Please login again.',
                    'redirect' => route('front.index')
                ], 401);
            }

            // For regular requests, redirect to login
            return redirect()->route('front.index')
                ->with('error', 'Your session has expired. Please login again.');
        }

        // Check if session has expired
        if (Session::has('last_activity')) {
            $lastActivity = Session::get('last_activity');
            $sessionLifetime = config('session.lifetime') * 60; // Convert minutes to seconds

            if (time() - $lastActivity > $sessionLifetime) {
                Auth::guard('web')->logout();
                Session::flush();

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'error' => 'Session expired',
                        'message' => 'Your session has expired. Please login again.',
                        'redirect' => route('front.index')
                    ], 401);
                }

                return redirect()->route('front.index')
                    ->with('error', 'Your session has expired. Please login again.');
            }
        }

        // Update last activity time
        Session::put('last_activity', time());

        return $next($request);
    }
} 