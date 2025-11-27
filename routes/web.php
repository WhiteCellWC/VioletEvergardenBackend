<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\Delivery\Http\Controller\Backend\DeliveryOptionController;
use Modules\Delivery\Http\Controller\Backend\DeliveryTierController;
use Modules\Delivery\Http\Controller\Backend\ShipmentController;
use Modules\Letter\Http\Controller\Backend\LetterTemplateController;
use Modules\Letter\Http\Controller\Backend\LetterTypeController;
use Modules\LetterComponent\Http\Controller\Backend\EnvelopeTypeController;
use Modules\LetterComponent\Http\Controller\Backend\FragranceTypeController;
use Modules\LetterComponent\Http\Controller\Backend\PaperTypeController;
use Modules\LetterComponent\Http\Controller\Backend\WaxSealTypeController;
use Modules\Location\Http\Controller\Backend\CountryController;
use Modules\Location\Http\Controller\Backend\StateController;
use Modules\User\Http\Controller\Backend\UserController;

Route::get('/', function () {
    return Inertia::render('Dashboard/Dashboard');
})->name('dashboard');

Route::resource('states', StateController::class);
Route::resource('countries', CountryController::class);
Route::resource('fragrance-types', FragranceTypeController::class);
Route::resource('envelope-types', EnvelopeTypeController::class);
Route::resource('paper-types', PaperTypeController::class);
Route::resource('wax-seal-types', WaxSealTypeController::class);
Route::resource('letter-templates', LetterTemplateController::class);
Route::resource('letter-types', LetterTypeController::class);
Route::resource('delivery-options', DeliveryOptionController::class);
Route::resource('delivery-tiers', DeliveryTierController::class);
Route::resource('users', UserController::class)->only(['index']);
Route::resource('shipments', ShipmentController::class)->only(['index']);
