<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Food;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('food')
            ->where('user_id', auth()->id())
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $carts
        ]);
    }

    public function store(CartRequest $request)
    {
        $existingCart = Cart::where('user_id', auth()->id())
            ->where('food_id', $request->food_id)
            ->first();

        if ($existingCart) {
            return response()->json([
                'status' => 'info',
                'message' => 'Item sudah ada di keranjang',
                'data' => $existingCart->load('food')
            ], 200);
        }

        $food = Food::findOrFail($request->food_id);

        $cart = Cart::create([
            'food_id' => $request->food_id,
            'user_id' => auth()->id(),
            'quantity' => $request->quantity,
            'price' => $food->price
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Item berhasil ditambahkan ke keranjang',
            'data' => $cart->load('food')
        ], 201);
    }

    public function update(CartRequest $request, Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Keranjang berhasil diperbarui',
            'data' => $cart->load('food')
        ]);
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $cart->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item berhasil dihapus dari keranjang'
        ]);
    }
}
