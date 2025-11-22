<?php

namespace Modules\Letter\Provider;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Modules\Letter\Contract\LetterServiceInterface;
use Modules\Letter\Contract\LetterTemplateServiceInterface;
use Modules\Letter\Contract\LetterTypeServiceInterface;
use Modules\Letter\Http\Service\LetterService;
use Modules\Letter\Http\Service\LetterTemplateService;
use Modules\Letter\Http\Service\LetterTypeService;

class LetterComponentProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LetterTemplateServiceInterface::class, LetterTemplateService::class);
        $this->app->bind(LetterServiceInterface::class, LetterService::class);
        $this->app->bind(LetterTypeServiceInterface::class, LetterTypeService::class);
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
