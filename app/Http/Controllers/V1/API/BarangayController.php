<?php

namespace App\Http\Controllers\V1\API;

use App\Models\Barangay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BarangayController extends Controller
{
    public function index()
    {
        // Fetch all barangays from the database
        $barangays = Barangay::all();
        // Return the barangays as a JSON response
        return response()->json($barangays);
    }
}
