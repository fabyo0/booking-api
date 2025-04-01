<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

final class SessionController
{
    /**
     * Login
     *
     * @return JsonResponse
     */
    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->input('email'))->firstOrFail();

        // Check User
        if ( ! Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 422);
        }
        // Generate token
        $device = mb_substr($request->userAgent() ?? '', 0, 255);

        return response()->json([
            'access_token' => $user->createToken($device)->plainTextToken,
        ]);
    }

    /**
     * Destroy
     *
     * @return JsonResponse
     */
    public function destroy()
    {
        // Revoke the current user
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'You have been successfully logged out.',
        ]);
    }
}
