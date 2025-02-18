<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param LoginFormRequest $request
     * @return JsonResponse
     */
    public function __invoke(LoginFormRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Optionally, you can generate a token for API authentication
        $authUser = Auth::user();
        $token = $authUser->createToken('authToken')->accessToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => Auth::user(),
            'token' => $token, // Include this if using API tokens
        ], 200);
    }
}
