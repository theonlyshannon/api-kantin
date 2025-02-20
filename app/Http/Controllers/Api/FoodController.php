<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Http\Requests\FoodStoreRequest;
use App\Http\Requests\FoodUpdateRequest;
use App\Http\Resources\FoodResource;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ResponseHelper;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foods = Food::all();
        return ResponseHelper::jsonResponse(true, 'Foods fetched successfully', FoodResource::collection($foods), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FoodStoreRequest $request)
    {
        $validated = $request->validated();

        $food = new Food();
        $food->name = $validated['name'];
        $food->slug = $validated['slug'];
        $food->description = $validated['description'];
        $food->price = $validated['price'];

        $food->is_discount = $validated['is_discount'] ?? false;

        if ($food->is_discount) {
            $food->discount = $validated['discount'] ?? 0;
            $discountAmount = ($validated['price'] * $food->discount) / 100;
            $food->discount_price = $validated['price'] - $discountAmount;
        } else {
            $food->discount = 0;
            $food->discount_price = $validated['price'];
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('food', $imageName, 'public');
            $food->image = 'storage/' . $imagePath;
        }

        $food->save();

        return ResponseHelper::jsonResponse(true, 'Food created successfully', new FoodResource($food), 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return ResponseHelper::jsonResponse(false, 'Food not found', null, 404);
        }

        return ResponseHelper::jsonResponse(true, 'Food fetched successfully', new FoodResource($food), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FoodStoreRequest $request, Food $food)
    {
        $validated = $request->validated();

        $food->name = $validated['name'];
        $food->slug = $validated['slug'];
        $food->description = $validated['description'];
        $food->price = $validated['price'];

        // Penanganan diskon
        $food->is_discount = $validated['is_discount'] ?? false;

        if ($food->is_discount) {
            $food->discount = $validated['discount'] ?? 0;
            $discountAmount = ($validated['price'] * $food->discount) / 100;
            $food->discount_price = $validated['price'] - $discountAmount;
        } else {
            $food->discount = 0;
            $food->discount_price = $validated['price'];
        }

        // Penanganan update gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($food->image) {
                $oldImagePath = str_replace('storage/', 'public/', $food->image);
                Storage::delete($oldImagePath);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('food', $imageName, 'public');
            $food->image = 'storage/' . $imagePath;
        }

        $food->save();

        return ResponseHelper::jsonResponse(true, 'Food updated successfully', new FoodResource($food), 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return ResponseHelper::jsonResponse(false, 'Food not found', null, 404);
        }

        if ($food->image) {
            Storage::delete('public/' . $food->image);
        }

        $food->delete();

        return ResponseHelper::jsonResponse(true, 'Food successfully deleted', null, 200);
    }
}
