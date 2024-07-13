<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Summary of AuthController
 */
class AuthController extends BaseController
{
    /**
     * Summary of register
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]
        );

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user,
            'token' => $user->createToken('MyApp')->plainTextToken
        ]);
    }

    /**
     * Summary of login
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('AuthToken')->plainTextToken;

        return response()->json(['token' => $token, 'token_type' => 'Bearer']);
    }

    /**
     * Summary of logout
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Throwable $e) {
            Log::error("LOGOUT_ERROR: " . $e->getMessage());
            return $this->sendError("Logout error", [], 500);
        }
    }
}
