<?php

namespace App\Http\Controllers\V1\APi;

use App\Models\Dataset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class RoboFlowController extends Controller
{
   public function classify(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'user_id' => 'required',
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
            $class = $prediction['class'] ?? 'Unknown';

            // Map the class to the corresponding details
            switch ($class) {
                case 'grade-jk':
                    $results = [
                        'name' => 'JK (Hand Strip)',
                        'local_name' => 'Laguras',
                        'price' => '48 Pesos'
                    ];
                    break;

                case 'grade-s-s2':
                    $results = [
                        'name' => 'S2 (Machine Strip)',
                        'local_name' => 'Spindle',
                        'price' => '86 Pesos'
                    ];
                    break;

                case 'grade-s-i':
                    $results = [
                        'name' => 'S3 (Machine Strip)',
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
        } else {
            $results = [
                'error' => 'No predictions found'
            ];
        }

        Dataset::create([
            'image_path' => $imagePath,
            'grade' => $results['name'] ?? null,
            'local_name' => $results['local_name'] ?? null,
            'price' => $results['price'] ?? null,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'results' => $results,
            'fullResponse' => $responseData
        ]);
    }

}
