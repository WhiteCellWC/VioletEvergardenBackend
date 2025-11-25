<?php

namespace Modules\User\Http\Controller\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => "User with this email does not exist."
            ], 404);
        }

        // Generate 6-digit code
        $code = rand(100000, 999999);

        // Store code + expiry (15 min)
        $user->{User::passwordResetCode} = $code;
        $user->{User::passwordResetCodeExpiry} = Carbon::now()->addMinutes(15);
        $user->save();

        try {
            Mail::raw(
                "Your verification code is: {$code}\nThis code will expire in 15 minutes.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject("Your Verification Code");
                }
            );

            return response()->json([
                'status' => true,
                'message' => "Verification code sent successfully."
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => "Unable to send email.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function validateVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => "User not found.",
            ], 404);
        }

        // no code saved
        if (! $user->password_reset_code) {
            return response()->json([
                'status' => false,
                'message' => "No verification code found. Please request a new one.",
            ], 400);
        }

        // check match
        if ($user->password_reset_code != $request->code) {
            return response()->json([
                'status' => false,
                'message' => "Invalid verification code.",
            ], 422);
        }

        // check expiry
        if (Carbon::parse($user->password_reset_code_expiry)->isPast()) {
            return response()->json([
                'status' => false,
                'message' => "Verification code has expired.",
            ], 410);
        }

        $user->email_verified_at = now();

        $user->password_reset_code = null;
        $user->password_reset_code_expiry = null;

        $user->save();

        return response()->json([
            'status' => true,
            'message' => "Verification code is valid."
        ]);
    }
}
