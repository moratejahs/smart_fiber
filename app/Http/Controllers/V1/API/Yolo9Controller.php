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

        // Save the image to the public directory
        $imagePath = $request->file('image')->store('uploads', 'public');
        $imageUrl = asset('storage/' . $imagePath);

        $apiKey = 'kbSD1BMksOvt0oqVengz';

        // Call the RoboFlow API
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
        switch ($results['class'] ?? null) {
            case 'grade-jk':
                $results = [
                    'grade' => 'JK (Hand Strip)',
                    'local_name' => 'Laguras',
                    'price' => '48 Pesos'
                ];
                break;

            case 'grade-s-s2':
                $results = [
                    'grade' => 'S2 (Machine Strip)',
                    'local_name' => 'Spindle',
                    'price' => '86 Pesos'
                ];
                break;

            case 'grade-s-i':
                $results = [
                    'grade' => 'S3 (Machine Strip)',
                    'local_name' => 'Bakbak',
                    'price' => '55 Pesos'
                ];
                break;

            default:
                $results = [
                    'error' => 'Invalid image or unrecognized class'
                ];
                break;
        }

        if (empty($results['grade'])) {
            return response()->json([
            'error' => 'No prediction found'
            ], 400);
        }

        // Only store in the Dataset model if a valid grade is found
        Dataset::create([
            'image_path' => $imagePath,
            'grade' => $results['grade'],
            'local_name' => $results['local_name'],
            'price' => $results['price'],
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'message' => 'Image classified successfully'
        ]);
    }

}
