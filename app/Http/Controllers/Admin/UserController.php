<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()
            ->orderBy('created_at', 'desc')
            ->get();
        $barangays = \App\Models\Barangay::all();
        return view('admin.user.index', compact('users', 'barangays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return to_route('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'username' => 'required|string|unique:users|max:255',
            'password' => 'required|string|min:6',
            'is_admin' => 'required|boolean'
        ]);

        // dd($request);
        $user = User::create([
            'name' => $request->name,
            'barangay' => $request->barangay,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'username' => 'required|string|max:255|unique:users,username,'.$request->user_id,
            'password' => 'nullable|string|min:6',
            'is_admin' => 'required|boolean'
        ]);
        // dd($validated);
        $user = User::findOrFail($validated['user_id']);

        $userData = [
            'name' => $request->name,
            'barangay' => $request->barangay,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'is_admin' => $request->is_admin
        ];

        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $user->update($userData);

        return to_route('admin.users.index')->with(['message' => 'User updated successfully', 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
