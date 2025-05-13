<?php

namespace App\Http\Controllers\Admin;

use App\Models\Barangay;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminDashboard extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('barangays')
            ->select([
            'barangays.name as barangay_name',
            DB::raw("COUNT(CASE WHEN datasets.grade = 'S2 (Machine Strip)' THEN 1 END) as total_S2"),
            DB::raw("COUNT(CASE WHEN datasets.grade = 'JK (Hand Strip)' THEN 1 END) as total_JK"),
            DB::raw("COUNT(CASE WHEN datasets.grade = 'M1 (Bakbak ng JK)' THEN 1 END) as total_M1"),
            DB::raw("COUNT(CASE WHEN datasets.grade = 'S3 (Bakbak ng S2)' THEN 1 END) as total_S3"),
            ])
            ->leftJoin('users', 'users.barangay', '=', 'barangays.name')
            ->leftJoin('datasets', 'datasets.user_id', '=', 'users.id')
            ->groupBy('barangays.name')
            ->orderBy('barangays.name')
            ->get();
        // Prepare the data for JavaScript
        $barangayNames = [];
        $totalDataset = [];
        foreach ($data as $item) {
            $barangayNames[] = $item->barangay_name;
            $totalDataset[] = [
            'total_S2' => $item->total_S2,
            'total_JK' => $item->total_JK,
            'total_M1' => $item->total_M1,
            'total_S3' => $item->total_S3,
            ];
        }


        return view('admin.dashboard.index', [
            'barangayNames' => $barangayNames,
            'totalDataset' => $totalDataset,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
