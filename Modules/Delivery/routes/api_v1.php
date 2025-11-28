<?php

use Illuminate\Support\Facades\Route;
use Modules\Delivery\Http\Controller\Api\V1\DeliveryOptionApiController;
use Modules\Delivery\Http\Controller\Api\V1\DeliveryTierApiController;
use Modules\Delivery\Http\Controller\Api\V1\SendLetterApiController;
use Modules\Delivery\Http\Controller\Api\V1\ShipmentApiController;

Route::apiResource('delivery-options', DeliveryOptionApiController::class);
Route::apiResource('delivery-tiers', controller: DeliveryTierApiController::class);
Route::post('send-letter', [SendLetterApiController::class, 'sendLetter']);
Route::get('received-letter', [SendLetterApiController::class, 'receivedLetter']);
Route::get('shipments', [ShipmentApiController::class, 'index']);
Route::get('shipments/{shipment}', [ShipmentApiController::class, 'show']);
