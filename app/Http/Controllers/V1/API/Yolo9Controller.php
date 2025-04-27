<?php

namespace App\Http\Controllers\V1\API;

use App\Models\Dataset;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class Yolo9Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [
            [
                "grade" => "S2 (Machine Strip)",
                "local_name" => "Bakbak",
                "price" => "86 Pesos"
            ],
            [
                "grade" => "JK (Hand Strip)",
                "local_name" => "Bakbak",
                "price" => "48 Pesos"
            ],
            [
                "grade" => "M1 (Bakbak ng JK)",
                "local_name" => "Bakbak",
                "price" => "45 Pesos"
            ],
            [
                "grade" => "S3 (Bakbak ng S2)",
                "local_name" => "Bakbak",
                "price" => "45 Pesos"
            ],
        ];
        // Get a random one
        $randomItem = Arr::random($data);

        $validated = $request->validate([
            'user_id' =>'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB Max
        ]);
        if (isset($validated['image'])) {
            $filePath = Storage::disk('public')->put('image_path', $validated['image']);
        }
        $dataset = Dataset::create([
            'image_path' => $filePath ?? null,
            'grade' => $randomItem['grade'],
            'local_name' => $randomItem['local_name'],
            'price' => $randomItem['price'],
            'user_id' => $validated['user_id'], // assumes user is authenticated
        ]);
        // dd($randomItem);
        return response()->json([
            'randomItem' => $randomItem,
            'date' => $dataset->created_at,
            $dataset
        ]);
    }

}
