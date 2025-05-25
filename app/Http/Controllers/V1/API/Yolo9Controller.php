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

     public function detect(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:102400',
            'user_id' =>'required',
        ]);

        $imageFile = $request->file('image');
        $imagePath = $imageFile->store('uploads', 'public'); // Save image to storage
        $base64Image = base64_encode(file_get_contents($imageFile->getPathname()));
        $roboflowUrl = "https://detect.roboflow.com/abacafinalvlatest-f3vol/1?api_key=dyHNcVS6KTTg2o59MmTN";

        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->withBody($base64Image, 'application/x-www-form-urlencoded')
          ->post($roboflowUrl);

        if ($response->successful()) {
            $data = $response->json();
            $predictedClass = $data['predictions'][0]['class'] ?? null;
            $results = [];
            switch ($predictedClass) {
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
                case 'Abaca Grade - EF':
                case 'Abaca Grade - G':
                case 'Abaca Grade - H':
                case 'Abaca Grade - I':
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

            if (!isset($results['error'])) {
                try {
                    Dataset::create([
                        'image_path' => $imagePath, // Store only the file path
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
            }

            return response()->json([
                'success' => true,
                'results' => $results,
                'roboflow_raw' => $data
            ]);
        } else {
            \Log::error('Roboflow error:', ['body' => $response->body(), 'status' => $response->status()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to get detection from Roboflow',
                'error' => $response->body()
            ], $response->status());
        }
    }


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
        $predictedClass = $response->json()['predictions'][0]['class'] ?? null;

        switch ($predictedClass) {
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
            case 'Abaca Grade - EF':
            case 'Abaca Grade - G':
            case 'Abaca Grade - H':
            case 'Abaca Grade - I':
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

        try {
            Dataset::create([
                'image_path' => $imagePath,
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
            'results' => $results
        ]);
    }

}
