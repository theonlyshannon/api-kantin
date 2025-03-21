<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:Student,Stand', // Make role optional
        ];

        // Check if stand-related fields are present in the request
        if ($this->has('stand_name') || $this->has('stand_slug')) {
            $rules['stand_name'] = 'required|string|max:255';
            $rules['stand_slug'] = 'required|string|unique:stands,slug';
            $rules['stand_description'] = 'nullable|string';
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // If stand data is present but no role is specified, set role to 'Stand'
        if (($this->has('stand_name') || $this->has('stand_slug')) && !$this->has('role')) {
            $this->merge([
                'role' => 'Stand',
            ]);
        }
        // If no role and no stand data, set default role to 'Student'
        elseif (!$this->has('role')) {
            $this->merge([
                'role' => 'Student',
            ]);
        }
    }
}
