<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controller\Api\V1\UserApiController;

Route::apiResource('users', UserApiController::class);
