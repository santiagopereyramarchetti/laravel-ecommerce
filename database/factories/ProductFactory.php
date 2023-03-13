<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->sentence(7);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraphs(5, true),
            'cost_price' => $cp = random_int(100, 1000),
            'price' => 1.2 * $cp,
            'featured' => fake()->boolean(),
            'show_on_slider' => fake()->boolean(),
            'active' => fake()->boolean(),
        ];
    }
}
