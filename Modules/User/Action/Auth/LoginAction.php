<?php

namespace Modules\User\Action\Auth;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class LoginAction
{
    public function __construct() {}

    public function handle(Request $request)
    {
        try {

            $user = User::where(User::email, $request->get('email'))->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid credentials');
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token
            ];
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
