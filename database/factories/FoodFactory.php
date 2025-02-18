<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Food;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper\ImageHelper;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    protected $model = Food::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $isDiscount = $this->faker->boolean();
        $price = $this->faker->numberBetween(10000, 100000);
        $discount = $isDiscount ? $this->faker->randomFloat(2, 5, 50) : null;
        $discountPrice = $isDiscount ? $price - ($price * ($discount / 100)) : null;

        $imageHelper = new ImageHelper();
        $image = $imageHelper->createDummyImageWithTextSizeAndPosition(640, 480, 'center', 'center', null, 'medium');

        return [
            'image' => $image->getPathname(),
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
