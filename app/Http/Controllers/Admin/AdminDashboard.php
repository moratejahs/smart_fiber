<?php

namespace App\Http\Controllers\Admin;

use App\Models\Barangay;
use App\Models\Dataset;
use App\Models\User;
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
        // Define month names
        $monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Get user count per month for the current year
        $usersByMonth = DB::table('users')
            ->select([
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as user_count')
            ])
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('user_count', 'month')
            ->toArray();

        // Initialize array with zeros for all months
        $monthlyData = array_fill(1, 12, 0);

        // Fill in the actual counts
        foreach ($usersByMonth as $month => $count) {
            $monthlyData[$month] = $count;
        }

        // Convert to simple array (values only)
        $monthlyData = array_values($monthlyData);

        return view('admin.dashboard.index', [
            'monthNames' => $monthNames,
            'monthlyData' => $monthlyData,
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
