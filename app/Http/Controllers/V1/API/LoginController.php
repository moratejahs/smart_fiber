<?php

namespace App\Http\Controllers\V1\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $validated = request()->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);
         // Find user by username and ensure is_admin = 0
        $user = User::where('username', $validated['username'])
        ->where('is_admin', 0)
        ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['Account not found or not a regular user.'],
            ]);
        }
          // Check password
        if (!Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Incorrect password.'],
            ]);
        }
        $token = $user->createToken($user->name)->plainTextToken;
        return response()->json([
            'message' => 'Logged in successfully.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 200);
    }

    public function canLogin()
    {
        $validated = request()->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);
         // Find user by username and ensure is_admin = 0
        $user = User::where('username', $validated['username'])
        ->where('is_admin', 0)
        ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['Account not found or not a regular user.'],
            ]);
        }
          // Check password
        if (!Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Incorrect password.'],
            ]);
        }
        return response()->json([
           'message' => 'Can login.',
           'data'=> [
               'user' => $user,
           ],
        ], 200);

    }

    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ], 200);
    }
}
