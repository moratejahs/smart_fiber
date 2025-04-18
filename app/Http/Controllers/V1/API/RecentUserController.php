<?php

namespace App\Http\Controllers\V1\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecentUserController extends Controller
{
    public function index()
    {
        $recentUsers = User::with('datasets')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($recentUsers);
    }
}
