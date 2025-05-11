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
        'image' => 'required|image',
    ]);

    try {
        $apiKey = 'pRkeW0FKtpmxrHhqqUaZ';

        // Save image to /public/images folder
        $path = $request->file('image')->store('images', 'public');
        $imagePath = public_path("storage/{$path}");

        // Get raw base64 (no data URI prefix)
        $base64 = base64_encode(file_get_contents($imagePath));

        // Send raw base64 as body
        $response = Http::withBody($base64, 'text/plain')
            ->post("https://detect.roboflow.com/abaca-fiber-classification-yoklg-hwx5g/1?api_key={$apiKey}");

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json([
            'error' => 'Roboflow request failed.',
            'details' => $response->body()
        ], $response->status());

    } catch (\Throwable $e) {
        return response()->json([
            'error' => 'Something went wrong.',
            'message' => $e->getMessage(),
        ], 500);
    }
}

}
