<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;


final class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(rules: [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            //TODO: Available role
            'role_id' => ['required', Rule::in(Role::ROLE_USER, Role::ROLE_OWNER)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        $user->assignRole($request->role_id);

        event(new Registered($user));

        return response()->json([
            'access_token' => $user->createToken('client')->plainTextToken,
        ]);
    }
}
