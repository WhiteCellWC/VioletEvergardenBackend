<?php

namespace Modules\LetterComponent\Provider;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Modules\LetterComponent\Contract\EnvelopeTypeServiceInterface;
use Modules\LetterComponent\Contract\FragranceTypeServiceInterface;
use Modules\LetterComponent\Contract\PaperTypeServiceInterface;
use Modules\LetterComponent\Contract\WaxSealTypeServiceInterface;
use Modules\LetterComponent\Http\Service\EnvelopeTypeService;
use Modules\LetterComponent\Http\Service\FragranceTypeService;
use Modules\LetterComponent\Http\Service\PaperTypeService;
use Modules\LetterComponent\Http\Service\WaxSealTypeService;

class LetterComponentProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FragranceTypeServiceInterface::class, FragranceTypeService::class);
        $this->app->bind(EnvelopeTypeServiceInterface::class, EnvelopeTypeService::class);
        $this->app->bind(PaperTypeServiceInterface::class, PaperTypeService::class);
        $this->app->bind(WaxSealTypeServiceInterface::class, WaxSealTypeService::class);
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
