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
