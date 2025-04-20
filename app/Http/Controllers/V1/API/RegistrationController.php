<?php

namespace App\Http\Controllers\V1\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegistrationController extends Controller
{
    public function store(Request $request){
        // Validate the request
        $validatedData = request()->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'barangay' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'phone_number' => $validatedData['phone_number'],
            'barangay' => $validatedData['barangay'],
            'username' => $validatedData['username'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Return a response
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}
