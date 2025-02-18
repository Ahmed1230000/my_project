<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param RegisterFormRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegisterFormRequest $request): JsonResponse
    {
        try {
            // Hash the password before creating the user
            $validatedData = $request->validated();
            $validatedData['password'] = Hash::make($validatedData['password']);

            // Create the user
            $user = User::create($validatedData);

            // Optionally, you can log the user in after registration
            // auth()->login($user);

            // Return a success response
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Registration error: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'message' => 'An error occurred during registration.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
