<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'food_id' => 'required|exists:foods,id',
            'quantity' => 'required|integer|min:1'
        ];
    }
}
