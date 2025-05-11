<?php

namespace App\Http\Controllers\V1\APi;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class RoboFlowController extends Controller
{
    public function classify(Request $request)
    {
        $request->validate([
            'image' => 'required|file|image',
            'user_id' => 'required',
        ]);

        try {
            $apiKey = 'kbSD1BMksOvt0oqVengz';

            $imageFile = $request->file('image');
            $imageName = time() . '_' . $imageFile->getClientOriginalName();
            $imagePath = $imageFile->storeAs('images', $imageName, 'public');
            $imageUrl = asset('storage/' . $imagePath);

            $response = Http::get("https://serverless.roboflow.com/abaca-fiber-classification-yoklg/1", [
                'api_key' => $apiKey,
                'image' => $imageUrl,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Get the first prediction's class if available
                $predictionClass = $data['predictions'][0]['class'] ?? null;
                if ($predictionClass === null) {
                    return response()->json([
                        'error' => 'No predictions found'
                    ], 422);
                }
                if($predictionClass === 'grade-s-s2') {
                    $name = 'S2 (Machine Strip)';
                    $localName = 'Spindle';
                    $price = '86';
                } elseif ($predictionClass === 'grade-s-i') {
                    $name = 'M1 (Hand Strip)';
                    $localName = 'Bakbak';
                    $price = '45';
                } elseif ($predictionClass === 'grade-jk') {
                    $name = 'JK (Hand Strip)';
                    $localName = 'Laguras';
                    $price = '48';
                } else {
                    return response()->json([
                        'error' => 'Invalid prediction class'
                    ], 422);
                }
                Dataset::create([
                    'image_path' => $imagePath,
                    'grade' => $name,
                    'local_name' => $localName,
                    'price' => $price,
                    'user_id' => $request->user_id,
                ]);
                return response()->json([
                    'class' => $predictionClass,
                    'confidence' => $data['predictions'][0]['confidence'] ?? null,
                ]);
            } else {
                return response()->json([
                    'error' => 'Roboflow request failed',
                    'details' => $response->body()
                ], $response->status());
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Something went wrong.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
