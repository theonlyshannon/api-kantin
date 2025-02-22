<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'food.stand'])
            ->success();

        if ($request->month !== null && $request->year) {
            $query->whereMonth('created_at', $request->month + 1)
                  ->whereYear('created_at', $request->year);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'date' => $order->formatted_date,
                    'customer' => $order->user->name,
                    'items' => $order->food->name,
                    'stand' => $order->food->stand->name ?? 'No Stand',
                    'total' => $order->total_price,
                    'status' => $order->status
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Orders fetched successfully',
            'data' => $orders
        ]);
    }

    public function monthlySummary(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;

        $summary = Order::success()
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_price) as total_sales')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Monthly summary fetched successfully',
            'data' => $summary
        ]);
    }
}
