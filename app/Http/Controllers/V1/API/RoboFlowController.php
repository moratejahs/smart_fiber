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
        $desiredClass = 'grade-s-s2'; // Specify the desired class

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
                    break; // Stop after finding the desired class
                }
            }
        }

        return response()->json([
            'class' => $results['class'] ?? null,
            // 'fullResponse' => $responseData
        ]);
    }
}
