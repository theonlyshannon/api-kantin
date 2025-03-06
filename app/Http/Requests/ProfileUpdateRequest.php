<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:profiles,slug,' . $this->user()->id . ',user_id',
            'description' => 'nullable|string',
        ];
    }
}
