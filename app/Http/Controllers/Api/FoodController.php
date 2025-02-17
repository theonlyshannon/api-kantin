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
        $request = $request->validated();

        $food = new Food();
        $food->name = $request['name'];
        $food->slug = $request['slug'];
        $food->description = $request['description'];
        $food->price = $request['price'];
        $food->is_discount = $request['is_discount'];
        $food->discount = $request['discount'] ?? 0;
        $food->discount_price = $request['discount_price'] ?? 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/food', $imageName);
            $food->image = 'food/' . $imageName;
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
    public function update(FoodUpdateRequest $request, string $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return ResponseHelper::jsonResponse(false, 'Food not found', null, 404);
        }

        $request = $request->validated();

        $food->name = $request['name'];
        $food->slug = $request['slug'];
        $food->description = $request['description'];
        $food->price = $request['price'];
        $food->is_discount = $request['is_discount'];
        $food->discount = $request['discount'] ?? 0;
        $food->discount_price = $request['discount_price'] ?? 0;

        if ($request->hasFile('image')) {
            if ($food->image) {
                Storage::delete('public/' . $food->image);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/food', $imageName);
            $food->image = 'food/' . $imageName;
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
