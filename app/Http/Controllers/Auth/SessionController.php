<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required',
        ]);

        try {
            $user = User::firstWhere('email', $request->email);

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => 'The provided credentials are incorrect.',
                ]);
            }

            return response()->json([
                'access_token' => $user->createToken('client')->plainTextToken,
            ], Response::HTTP_CREATED);

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function destroy(Request $request)
    {
        Auth::guard('api')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
