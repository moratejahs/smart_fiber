<?php

namespace App\Http\Controllers\V1\API;

use App\Models\Dataset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DatasetController extends Controller
{

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'image' => 'required|image', // max 2MB
            'grade' => 'required|string|max:255',
            'local_name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
        ]);

        // Store image and get path
        $imagePath = $request->file('image')->store('datasets', 'public');

        // Create dataset
        $dataset = Dataset::create([
            'image_path' => $imagePath,
            'grade' => $validated['grade'],
            'local_name' => $validated['local_name'],
            'price' => $validated['price'],
            'user_id' => auth()->id(), // assumes user is authenticated
        ]);

        return response()->json([
            'message' => 'Dataset saved successfully.',
            'data' => $dataset
        ], 201);
    }

}
