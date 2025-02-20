<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'food'])->get();
        return ResponseHelper::jsonResponse(true, 'Orders fetched successfully', OrderResource::collection($orders), 200);
    }

    public function updateStatus(string $id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,order,success',
        ]);

        // Cari order berdasarkan ID
        $order = Order::find($id);

        if (!$order) {
            return ResponseHelper::jsonResponse(false, 'Order not found', null, 404);
        }

        // Update status order
        $order->update(['status' => $request->status]);

        return ResponseHelper::jsonResponse(true, 'Order status updated successfully', new OrderResource($order), 200);
    }


}

