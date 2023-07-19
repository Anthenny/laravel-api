<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'title' => fake()->text,
            'slug' => 'test-slug',
            'color' => fake()->text,
            'thumbnail' => fake()->imageUrl,
            'category' => fake()->randomElement(['armbanden', 'oorbellen', 'tassen']),
            'price' => fake()->randomFloat(2, 100, 1000),
            'quantity' => fake()->randomNumber(),
            'reserved' => false,
            'weight' => fake()->randomNumber(),
            'description' => fake()->text
        ];
    }
}
