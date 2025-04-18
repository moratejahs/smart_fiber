<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        // Validate the request
        $validatedData = request()->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Add is_admin = 0 to the credentials
        $credentials = [
            'username' => $validatedData['username'],
            'password' => $validatedData['password'],
            'is_admin' => 0, // Only non-admin users allowed
        ];

        // Attempt to authenticate
        if (auth()->attempt($credentials)) {
            return response()->json(['message' => 'Login successful'], 200);
        }

        // Authentication failed
        return response()->json(['message' => 'Invalid credentials or admin account'], 401);
    }

    public function destroy()
    {
        // Logout the user
        auth()->logout();

        // Return a response
        return response()->json(['message' => 'Logout successful'], 200);
    }
}
