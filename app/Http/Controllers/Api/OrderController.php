<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Food;
use Illuminate\Support\Facades\Log; // Menambahkan import untuk Log

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

        $order = Order::find($id);

        if (!$order) {
            return ResponseHelper::jsonResponse(false, 'Order not found', null, 404);
        }

        $order->update(['status' => $request->status]);

        return ResponseHelper::jsonResponse(true, 'Order status updated successfully', new OrderResource($order), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $food = Food::findOrFail($request->food_id);

        $total_price = $food->price * $request->quantity;

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'food_id' => $request->food_id,
                'quantity' => $request->quantity,
                'total_price' => $total_price,
                'status' => 'pending'
            ]);

            return ResponseHelper::jsonResponse(
                true,
                'Order created successfully',
                new OrderResource($order),
                201
            );
        } catch (\Throwable $e) {
            Log::error('Error creating order: ' . $e->getMessage()); // Menggunakan Log yang sudah diimpor
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan saat membuat pesanan', null, 500);
        }
    }
}
