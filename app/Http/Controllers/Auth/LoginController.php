<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required',
            'password' => 'required|string',
        ]);
        // dd($request->all());
        // Attempt to log the user in
        if (auth()->attempt($request->only('username', 'password'))) {
            // Check if the authenticated user is an admin
            if (auth()->user()->is_admin == 1) {
                // Redirect to intended page
                return redirect()->route('admin.dashboard.index');
            }

            // Logout the user if not an admin
            auth()->logout();

            // Redirect back with an error message
            return redirect()->back()->withErrors([
                'username' => 'You do not have permission to access this resource.',
            ]);
        }

        // If authentication fails, redirect back with an error message
        return redirect()->back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        // Log out the user
        auth()->logout();

        // Redirect to the login page
        return to_route('login');
    }
}
