<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StandStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:stands,slug',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
