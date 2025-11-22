<?php

namespace Modules\User\Action\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class RegisterAction
{
    public function __construct() {}

    public function handle(Request $request)
    {
        try {
            $user = User::create([
                User::name => $request->get('name'),
                User::email => $request->get('email'),
                User::password => Hash::make($request->get('password'))
            ]);

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
