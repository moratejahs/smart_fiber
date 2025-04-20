<?php

namespace App\Http\Controllers\Admin;

use App\Models\Barangay;
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
            ->select('barangays.name as barangay_name', DB::raw('COUNT(users.id) as total_users'))
            ->groupBy('barangays.name')
            ->orderBy('barangay_name', 'asc')
            ->get();

        // Prepare the data for JavaScript
        $barangayNames = $data->pluck('barangay_name')->toArray();
        $totalUsers = $data->pluck('total_users')->toArray();

        return view('admin.dashboard.index', [
            'barangayNames' => $barangayNames,
            'totalUsers' => $totalUsers,
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
