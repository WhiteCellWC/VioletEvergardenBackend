<?php

namespace Modules\Delivery\Provider;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Modules\Delivery\Contract\DeliveryOptionServiceInterface;
use Modules\Delivery\Contract\DeliveryTierServiceInterface;
use Modules\Delivery\Http\Service\DeliveryOptionService;
use Modules\Delivery\Http\Service\DeliveryTierService;

class DeliveryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DeliveryOptionServiceInterface::class, DeliveryOptionService::class);
        $this->app->bind(DeliveryTierServiceInterface::class, DeliveryTierService::class);
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
