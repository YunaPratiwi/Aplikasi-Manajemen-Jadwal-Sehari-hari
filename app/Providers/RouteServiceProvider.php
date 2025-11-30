<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // Web routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // API routes (optional, only if file exists)
            $apiPath = base_path('routes/api.php');
            if (file_exists($apiPath)) {
                Route::prefix('api')
                    ->middleware('api')
                    ->group($apiPath);
            }
        });
    }
}
