<?php

use Illuminate\Support\Facades\Route;
use Modules\Delivery\Http\Controller\Api\V1\DeliveryOptionApiController;

Route::apiResource('delivery-options', DeliveryOptionApiController::class);
