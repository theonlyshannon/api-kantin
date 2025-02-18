<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255|unique:foods,name',
            'slug' => 'required|string|max:255|unique:foods,slug',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'is_discount' => 'boolean',
            'discount' => 'required_if:is_discount,true|numeric|between:0,100|nullable',
            'discount_price' => 'nullable|integer|min:0',
        ];
    }
}

