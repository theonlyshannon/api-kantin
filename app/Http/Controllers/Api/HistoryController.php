<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $orders = Order::where('status', 'success')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

            return ResponseHelper::jsonResponse(true, 'Foods fetched successfully', FoodResource::collection($orders), 200);
    }
}
