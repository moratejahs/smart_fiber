<?php

namespace App\Http\Controllers\V1\API;

use App\Models\Dataset;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class Yolo9Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'user_id' => 'required',
        ]);

        // Store the uploaded image in public disk
        $image = $request->file('image');
        $imagePath = $image->store('uploads', 'public');
        $publicUrl = asset('storage/' . $imagePath); // Generate public URL

        $apiKey = 'dyHNcVS6KTTg2o59MmTN';
        $model = 'abacafinalvlatest-f3vol';
        $version = '1';
        $roboflowUrl = "https://detect.roboflow.com/{$model}/{$version}?api_key={$apiKey}&image=" . urlencode($publicUrl);

        // Send image URL to Roboflow
        $response = Http::post($roboflowUrl);
        $responseData = $response->json();
        $predictions = $responseData['predictions'] ?? [];
        $results = [];
        $desiredClass = 'grade-s-s2';

        if (!empty($predictions)) {
            foreach ($predictions as $prediction) {
                if ($prediction['class'] === $desiredClass) {
                    $results = [
                        'class' => $prediction['class'],
                        'confidence' => $prediction['confidence'] ?? 0,
                        'boundingBox' => [
                            'x' => $prediction['x'] ?? 0,
                            'y' => $prediction['y'] ?? 0,
                            'width' => $prediction['width'] ?? 0,
                            'height' => $prediction['height'] ?? 0,
                        ],
                    ];
                    break;
                }
            }
        }

        if (empty($results['class'])) {
            return response()->json([
                'error' => 'No prediction found'
            ], 400);
        }

        // // Store in Dataset if needed (update as per your schema)
        // try {
        //     Dataset::create([
        //         'image_path' => $publicUrl,
        //         'grade' => $results['class'],
        //         'user_id' => $request->user_id,
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'error' => 'Failed to save data: ' . $e->getMessage()
        //     ], 500);
        // }

        return response()->json([
            'roboflow_response' => $responseData,
            'results' => $results
        ]);
    }

}
