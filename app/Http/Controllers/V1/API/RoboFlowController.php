<?php

namespace App\Http\Controllers\V1\APi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class RoboFlowController extends Controller
{
      public function classify(Request $request)
    {
        $request->validate([
            'image_url' => 'required|url'
        ]);

        $apiKey = 'kbSD1BMksOvt0oqVengz';
        $imageUrl = $request->input('image_url');

        $response = Http::withOptions([
            'query' => [
                'api_key' => $apiKey,
                'image' => $imageUrl
            ]
        ])->post("https://serverless.roboflow.com/abaca-fiber-classification-yoklg/1");

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
