<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $roles = $this->getRoleNames();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $roles,
            'role' => count($roles) > 0 ? $roles[0] : null,
            'stand' => $this->when($this->stand, function () {
                return [
                    'id' => $this->stand->id,
                    'name' => $this->stand->name,
                    'slug' => $this->stand->slug,
                    'description' => $this->stand->description,
                ];
            }),
            'token' => $this->when($request->route()->getName() === 'login' || $request->route()->getName() === 'register', function() {
                return $this->createToken('api-token')->plainTextToken;
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
