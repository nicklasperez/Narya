<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // al menos una minúscula
                'regex:/[A-Z]/',      // al menos una mayúscula
                'regex:/[0-9]/',      // al menos un número
                'regex:/[@$!%*#?&]/', // al menos un símbolo
            ],
            'birthdate' => 'nullable|date',
            'profile_picture' => 'nullable|url',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'surname' => $validated['surname'] ?? null,
            'email' => $validated['email'],
            'password' => $validated['password'],
            'birthdate' => $validated['birthdate'] ?? null,
            'profile_picture' => $validated['profile_picture'] ?? null,
        ]);


        $token = $user->createToken('narya-token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('narya-token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
