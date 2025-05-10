<?php

namespace App\Http\Controllers\V1\APi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class RoboFlowController extends Controller
{
    public function classify(Request $request)
{
    // 1. Validate uploaded image
    $request->validate([
        'image' => 'required|file|image',
    ]);

    try {
        $apiKey = 'kbSD1BMksOvt0oqVengz';

        // 2. Store the image in the 'public/images' folder
        $imageFile = $request->file('image');
        $imageName = time() . '_' . $imageFile->getClientOriginalName();
        $imagePath = $imageFile->storeAs('images', $imageName, 'public'); // stores in storage/app/public/images

        // 3. Generate the public image URL
        $imageUrl = asset('storage/' . $imagePath); // e.g., http://localhost:8000/storage/images/xxx.jpg

        // 4. Send image URL to Roboflow
        $response = Http::get("https://serverless.roboflow.com/abaca-fiber-classification-yoklg/1", [
            'api_key' => $apiKey,
            'image' => $imageUrl,
        ]);

        // 5. Return the response
        if ($response->successful()) {
            return response()->json($response->json());
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
