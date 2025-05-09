<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Food;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper\ImageHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    protected $model = Food::class;

    public function definition(): array
    {
        $foodNames = [
            'Nasi Goreng', 'Mie Ayam', 'Sate Ayam', 'Rendang', 'Bakso',
            'Soto Ayam', 'Gado-Gado', 'Ayam Geprek', 'Pempek', 'Gudeg',
        ];

        $name = $this->faker->randomElement($foodNames);
        $isDiscount = $this->faker->boolean();
        $price = $this->faker->numberBetween(10000, 100000);
        $discount = $isDiscount ? $this->faker->randomFloat(2, 5, 50) : null;
        $discountPrice = $isDiscount ? $price - ($price * ($discount / 100)) : null;

        // Buat dummy image
        $imageHelper = new ImageHelper();
        $tempImage = $imageHelper->createDummyImageWithTextSizeAndPosition(640, 480, 'center', 'center', null, 'medium');

        // Simpan ke storage/public/food
        $path = Storage::disk('public')->putFile('food', new File($tempImage->getPathname()));

        return [
            'image' => $path,
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
            'price' => $price,
            'is_discount' => $isDiscount,
            'discount' => $discount,
            'discount_price' => $discountPrice,
        ];
    }
}
