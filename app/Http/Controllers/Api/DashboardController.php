<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function getStats(Request $request)
    {
        try {
            Log::info('Fetching dashboard stats');

            // Cek apakah model bisa diakses
            $foodCount = Food::count();
            Log::info('Food count: ' . $foodCount);

            $userCount = User::count();
            Log::info('User count: ' . $userCount);

            return response()->json([
                'success' => true,
                'message' => 'Stats retrieved successfully',
                'data' => [
                    'total_foods' => $foodCount,
                    'total_users' => $userCount
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Dashboard stats error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching stats: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
