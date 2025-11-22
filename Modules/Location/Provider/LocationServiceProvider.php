<?php

namespace Modules\Location\Provider;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Modules\Location\Contract\CountryServiceInterface;
use Modules\Location\Contract\StateServiceInterface;
use Modules\Location\Http\Service\CountryService;
use Modules\Location\Http\Service\StateService;

class LocationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CountryServiceInterface::class, CountryService::class);
        $this->app->bind(StateServiceInterface::class, StateService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::middleware([
            'api',
            EnsureFrontendRequestsAreStateful::class
        ])
            ->prefix('api/v1')
            ->as('api.v1.')
            ->group(function () {
                require __DIR__ . '/../routes/api_v1.php';
            });
    }
}
