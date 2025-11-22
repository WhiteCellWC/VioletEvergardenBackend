<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controller\Api\V1\AuthApiController;
use Modules\User\Http\Controller\Api\V1\UserApiController;

Route::apiResource('users', UserApiController::class);

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);
