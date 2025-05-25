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

        // Store the uploaded image temporarily
        $image = $request->file('image');
        $imagePath = $image->store('uploads', 'public');
        $imageFullPath = storage_path('app/public/' . $imagePath);

        // Convert image to base64
        $base64Image = base64_encode(file_get_contents($imageFullPath));

        $apiKey = 'dyHNcVS6KTTg2o59MmTN';
        $roboflowUrl = "https://detect.roboflow.com/abacafinalvlatest-f3vol/1?api_key={$apiKey}";

        // Send base64 image to Roboflow
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->post($roboflowUrl, [
            'body' => $base64Image
        ]);

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
            case 'Abaca Grade - S2':
                $results = [
                    'grade' => 'JK (Hand Strip)',
                    'local_name' => 'Laguras',
                    'price' => '48 Pesos'
                ];
                break;

            case 'Abaca Grade - M1':
                $results = [
                    'grade' => 'S2 (Machine Strip)',
                    'local_name' => 'Spindle',
                    'price' => '86 Pesos'
                ];
                break;

            case 'Abaca Grade - S3':
                $results = [
                    'grade' => 'S3 (Machine Strip)',
                    'local_name' => 'Bakbak',
                    'price' => '55 Pesos'
                ];
                break;

            case 'Abaca Grade - EF':
                    $results = [
                        'grade' => 'S3 (Machine Strip)',
                        'local_name' => 'Bakbak',
                        'price' => '55 Pesos'
                    ];
                    break;

            case 'Abaca Grade - G':
                    $results = [
                        'grade' => 'S3 (Machine Strip)',
                        'local_name' => 'Bakbak',
                        'price' => '55 Pesos'
                    ];
                    break;
            case 'Abaca Grade - H':
                        $results = [
                            'grade' => 'S3 (Machine Strip)',
                            'local_name' => 'Bakbak',
                            'price' => '55 Pesos'
                        ];
                        break;
            case 'Abaca Grade - I':
                $results = [
                    'grade' => 'S3 (Machine Strip)',
                    'local_name' => 'Bakbak',
                    'price' => '55 Pesos'
                ];
                break;
            case 'Abaca Grade - JK':
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
        try {
            Dataset::create([
                'image_path' => $base64Image,
                'grade' => $results['grade'],
                'local_name' => $results['local_name'],
                'price' => $results['price'],
                'user_id' => $request->user_id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save data: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Image classified successfully'
        ]);
    }

}
