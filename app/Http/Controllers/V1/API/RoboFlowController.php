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
            'user_id' =>'required',
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

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'Roboflow request failed',
                    'details' => $response->body()
                ], $response->status());
            }

            $data = $response->json();
            $predictions = $data['predictions'] ?? [];

            if (count($predictions) === 0) {
                return response()->json(['error' => 'No predictions found'], 422);
            }

            // Count classes
            $classCounts = collect($predictions)->countBy('class');

            // Get class with most predictions
            $topClass = $classCounts->sortDesc()->keys()->first();

            // Get the first prediction with that class to get its confidence
            $topPrediction = collect($predictions)->firstWhere('class', $topClass);
            $confidence = $topPrediction['confidence'] ?? null;

            // Map class name to grade details
            $details = match ($topClass) {
                'grade-s-s2' => ['S2 (Machine Strip)', 'Spindle', '86'],
                'grade-s-i' => ['M1 (Hand Strip)', 'Bakbak', '45'],
                'grade-jk'   => ['JK (Hand Strip)', 'Laguras', '48'],
                default      => null,
            };

            if (!$details) {
                return response()->json(['error' => 'Invalid prediction class'], 422);
            }

            [$name, $localName, $price] = $details;

            Dataset::create([
                'image_path' => $imagePath,
                'grade' => $name,
                'local_name' => $localName,
                'price' => $price,
                'user_id' => $request->user_id,
            ]);

            return response()->json([
                'class' => $topClass,
                'confidence' => $confidence,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Something went wrong.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
