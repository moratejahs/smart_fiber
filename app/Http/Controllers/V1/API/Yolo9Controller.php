<?php

namespace App\Http\Controllers\V1\API;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Yolo9Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            ["name" => "S2 (Machine Strip)", "price" => "86 Pesos"],
            ["name" => "JK (Hand Strip)", "price" => "48 Pesos"],
            ["name" => "M1 (Bakbak ng JK)", "price" => "45 Pesos"],
            ["name" => "S3 (Bakbak ng S2)", "price" => "45 Pesos"],
        ];

        // Get a random one
        $randomItem = Arr::random($data);

        return response()->json($randomItem);
    }

}
