<?php

namespace Modules\Delivery\Provider;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Modules\Delivery\Contract\DeliveryOptionServiceInterface;
use Modules\Delivery\Contract\DeliveryTierServiceInterface;
use Modules\Delivery\Contract\LetterDeliveryServiceInterface;
use Modules\Delivery\Contract\RecipientServiceInterface;
use Modules\Delivery\Http\Service\DeliveryOptionService;
use Modules\Delivery\Http\Service\DeliveryTierService;
use Modules\Delivery\Http\Service\LetterDeliveryService;
use Modules\Delivery\Http\Service\RecipientService;

class DeliveryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DeliveryOptionServiceInterface::class, DeliveryOptionService::class);
        $this->app->bind(DeliveryTierServiceInterface::class, DeliveryTierService::class);
        $this->app->bind(RecipientServiceInterface::class, RecipientService::class);
        $this->app->bind(LetterDeliveryServiceInterface::class, LetterDeliveryService::class);
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
