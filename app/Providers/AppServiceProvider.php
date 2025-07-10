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
        $newDesignRoutes = config('design_migration.enabled_routes');

        View::composer('*', function () use ($newDesignRoutes) {
            $currentRoute = Route::current()?->getName();
            $useNew = false;

            Log::info('Current route:', ['route' => $currentRoute]);
            Log::info('New design routes:', $newDesignRoutes);

            if ($currentRoute && in_array($currentRoute, $newDesignRoutes)) {
                $useNew = true;
                Log::info('Using new design for route:', ['route' => $currentRoute]);
            }

            if ($useNew) {
                View::getFinder()->prependLocation(resource_path('views/new'));
                Log::info('Prepended new view location.');
            }
        });
    }
}
