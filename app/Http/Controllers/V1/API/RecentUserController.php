<?php

namespace App\Http\Controllers\V1\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RecentUserController extends Controller
{
    public function index(string $userId)
    {
        $recentDatasets = Dataset::query()
            ->select('grade', 'local_name', 'price', 'created_at', 'image_path') // keep original datetime
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->formatted_date = Carbon::parse($item->created_at)->format('m-d-Y');
                return $item;
            });

        return response()->json($recentDatasets);
    }
}
