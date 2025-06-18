<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSessionExpiration
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check() && !$request->is('login', 'register', 'forgot-password', 'reset-password/*')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Session expired'], 401);
            }
            return redirect()->route('front.index')->with('error', 'Your session has expired. Please login again.');
        }

        return $next($request);
    }
} 