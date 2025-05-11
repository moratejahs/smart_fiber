<?php

namespace App\Http\Controllers\V1\APi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class RoboFlowController extends Controller
{
    public function classify(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        // Save the uploaded image to the public directory
        $imagePath = $request->file('image')->store('uploads', 'public');
        $imageUrl = asset('storage/' . $imagePath);

        $apiKey = 'kbSD1BMksOvt0oqVengz';

        // Send the image URL to the RoboFlow API
        $response = Http::post("https://serverless.roboflow.com/abaca-fiber-classification-yoklg/1?api_key={$apiKey}&image={$imageUrl}");

        $responseData = $response->json();
        $predictions = $responseData['predictions'] ?? [];
        $results = [];
        if (!empty($predictions)) {
            $prediction = $predictions[0]; // Get the first prediction
            $results = [
            'class' => $prediction['class'] ?? 'Unknown',
            'confidence' => $prediction['confidence'] ?? 0,
            'boundingBox' => [
                'x' => $prediction['x'] ?? 0,
                'y' => $prediction['y'] ?? 0,
                'width' => $prediction['width'] ?? 0,
                'height' => $prediction['height'] ?? 0,
            ],
            ];
        }

        return response()->json([
            'results' => $results,
            'fullResponse' => $responseData
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Roboflow request failed',
                'details' => $response->body()
            ], $response->status());
        }
    }
}
