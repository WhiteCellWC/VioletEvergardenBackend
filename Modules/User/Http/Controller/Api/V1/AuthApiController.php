<?php

namespace Modules\User\Http\Controller\Api\V1;

use App\Http\Controllers\Controller;
use Exception;
use Modules\User\Action\Auth\LoginAction;
use Modules\User\Action\Auth\RegisterAction;
use Modules\User\Http\Request\Api\V1\LoginApiRequest;
use Modules\User\Http\Request\Api\V1\RegisterApiRequest;
use Throwable;

class AuthApiController extends Controller
{
    public function __construct(
        protected LoginAction $loginAction,
        protected RegisterAction $registerAction
    ) {}

    public function login(LoginApiRequest $loginApiRequest)
    {
        try {
            $data = $this->loginAction->handle($loginApiRequest);

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function register(RegisterApiRequest $registerApiRequest)
    {
        try {
            $data = $this->registerAction->handle($registerApiRequest);

            return response()->json($data);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
