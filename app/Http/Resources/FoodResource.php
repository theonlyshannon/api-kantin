<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageUrl = null;
        if ($this->image) {
            // Cek apakah sudah berisi URL lengkap
            if (str_starts_with($this->image, 'http') || str_starts_with($this->image, 'storage/')) {
                $imageUrl = $this->image;
            } else {
                $imageUrl = url($this->image);
            }
        }

        return [
            'id' => $this->id,
            'image' => $imageUrl,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'is_discount' => $this->is_discount,
            'discount' => $this->discount,
            'discount_price' => $this->discount_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
