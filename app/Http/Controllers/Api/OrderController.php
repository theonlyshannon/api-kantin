<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Food;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::with(['user', 'food'])
                          ->latest()
                          ->get();

            return ResponseHelper::jsonResponse(
                true,
                'Pesanan berhasil diambil',
                OrderResource::collection($orders),
                200
            );
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan saat mengambil pesanan', null, 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'food_id' => 'required|exists:foods,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $food = Food::findOrFail($request->food_id);

            // Hitung total harga
            $price = $food->is_discount ? $food->discount_price : $food->price;
            $total_price = $price * $request->quantity;

            $order = Order::create([
                'user_id' => auth()->id(),
                'food_id' => $food->id,
                'quantity' => $request->quantity,
                'total_price' => $total_price,
                'status' => 'pending'
            ]);

            $order->load(['user', 'food']);

            return ResponseHelper::jsonResponse(
                true,
                'Pesanan berhasil dibuat',
                new OrderResource($order),
                201
            );
        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan saat membuat pesanan', null, 500);
        }
    }

    public function updateStatus(string $id, Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,order,success',
            ]);

            $order = Order::findOrFail($id);
            $order->update(['status' => $request->status]);

            $order->load(['user', 'food']);

            return ResponseHelper::jsonResponse(
                true,
                'Status pesanan berhasil diperbarui',
                new OrderResource($order),
                200
            );
        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan saat mengubah status pesanan', null, 500);
        }
    }

    public function show(string $id)
    {
        try {
            $order = Order::with(['user', 'food'])->findOrFail($id);

            return ResponseHelper::jsonResponse(
                true,
                'Detail pesanan berhasil diambil',
                new OrderResource($order),
                200
            );
        } catch (\Exception $e) {
            Log::error('Error fetching order detail: ' . $e->getMessage());
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan saat mengambil detail pesanan', null, 404);
        }
    }

    public function destroy(string $id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return ResponseHelper::jsonResponse(
                true,
                'Pesanan berhasil dihapus',
                null,
                200
            );
        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            return ResponseHelper::jsonResponse(false, 'Terjadi kesalahan saat menghapus pesanan', null, 500);
        }
    }
}
