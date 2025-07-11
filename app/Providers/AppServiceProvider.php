<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $newDesignRoutes = config('design_migration.enabled_routes');

        // View::composer('*', function () use ($newDesignRoutes) {
        //     $currentRoute = Route::current()?->getName();
        //     $useNew = false;

        //     if ($currentRoute && in_array($currentRoute, $newDesignRoutes)) {
        //         $useNew = true;
        //     }

        //     if ($useNew) {
        //         View::getFinder()->prependLocation(resource_path('views/new'));
        //     }
        // });
    }
}
