<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\VerifyOtpRequest;
use Illuminate\Support\Facades\Cache;

use Illuminate\Routing\Controller as BaseController;

class AdminAuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('profile', 'logout');
        $this->middleware('throttle:10,1')->only('login', 'forgotPassword');
    }

    public function login(Request $request)
    {
        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            Log::warning('Invalid credentials for: ' . $request->email);
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        Log::info('Admin logged in: ' . $request->email);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $admin
        ], 200);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $cachedOtp = Cache::get("otp_{$request->email}");
        $admin = Admin::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$admin || $cachedOtp !== $request->otp) {
            return response()->json(['message' => 'Invalid or expired OTP'], 401);
        }

        $admin->update(['otp' => null]);
        Cache::forget("otp_{$request->email}");

        return response()->json(['message' => 'OTP verified']);
    }

    public function forgotPassword(Request $request)
    {
        try {
            $status = Password::broker('admins')->sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => 'Reset link sent to your email'])
                : response()->json(['message' => 'Unable to send reset link'], 500);
        } catch (\Exception $e) {
            Log::error('Forgot password failed: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) {
                $admin->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully']);
        }

        return response()->json(['error' => 'Unable to reset password'], 500);
    }

    public function profile()
    {
       Log::info('Profile route hit. Token: ' . request()->header('Authorization'));

        $admin = auth()->user();

        if (!$admin) {
            Log::error('Unauthorized access - No user found.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return response()->json(['admin' => $admin], 200);   
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
