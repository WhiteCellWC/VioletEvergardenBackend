<?php

use Illuminate\Support\Facades\Route;
use Modules\Delivery\Http\Controller\Api\V1\DeliveryOptionApiController;
use Modules\Delivery\Http\Controller\Api\V1\DeliveryTierApiController;

Route::apiResource('delivery-options', DeliveryOptionApiController::class);
Route::apiResource('delivery-tiers', DeliveryTierApiController::class);
