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
            ->leftJoin('users', 'users.barangay', '=', 'barangays.name')
            ->leftJoin('datasets', 'datasets.user_id', '=', 'users.id')
            ->select('barangays.name as barangay_name', DB::raw('COUNT(datasets.id) as total_datasets'))
            ->groupBy('barangays.name')
            ->orderBy('barangays.name')
            ->get();
        // Prepare the data for JavaScript
        $barangayNames = [];
        $totalDataset = [];
        foreach ($data as $item) {
            $barangayNames[] = $item->barangay_name;
            $totalDataset[] = $item->total_datasets;
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
