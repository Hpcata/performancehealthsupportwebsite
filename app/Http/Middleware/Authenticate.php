<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    // protected function redirectTo($request)
    // {
    //     if (! $request->expectsJson()) {
    //         // If the request is for an admin route, redirect to admin login
    //         if ($request->is('admin*') || $request->is('admin/*')) {
    //             return route('index');
    //         }
    //         return route('index');
    //     }
    // }
    public function handle($request, \Closure $next, ...$guards)
    {
        if(Auth::guard('admin')->check()){
            return $next($request);
        }

        // Authenticate the user
        $this->authenticate($request, $guards);

        // Proceed with the request
        return $next($request);
    }
}
