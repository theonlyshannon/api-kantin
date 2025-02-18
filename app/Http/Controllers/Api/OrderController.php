<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['food', 'user'])
            ->where('user_id', auth()->id())
            ->get();

        return ResponseHelper::jsonResponse(true, 'Orders fetched successfully', $orders, 200);
    }

    public function store(Request $request)
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return ResponseHelper::jsonResponse(false, 'Keranjang kosong', null, 400);
        }

        // Buat order untuk setiap item di cart
        foreach ($cartItems as $item) {
            Order::create([
                'user_id' => auth()->id(),
                'food_id' => $item->food_id,
                'quantity' => $item->quantity,
                'status' => 'order'
            ]);

            // Hapus item dari cart
            $item->delete();
        }

        return ResponseHelper::jsonResponse(true, 'Pesanan berhasil dibuat', null, 201);
    }

    public function show(string $id)
    {
        $order = Order::with(['food', 'user'])->find($id);

        if (!$order) {
            return ResponseHelper::jsonResponse(false, 'Order not found', null, 404);
        }

        if ($order->user_id !== auth()->id()) {
            return ResponseHelper::jsonResponse(false, 'Unauthorized', null, 403);
        }

        return ResponseHelper::jsonResponse(true, 'Order fetched successfully', $order, 200);
    }

    public function updateStatus(string $id, Request $request)
    {
        $order = Order::find($id);

        if (!$order) {
            return ResponseHelper::jsonResponse(false, 'Order not found', null, 404);
        }

        $request->validate([
            'status' => 'required|in:pending,order,success'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return ResponseHelper::jsonResponse(true, 'Status pesanan berhasil diperbarui', $order, 200);
    }
}
