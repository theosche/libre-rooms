<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imageable_type' => Room::class,
            'imageable_id' => Room::factory(),
            'path' => 'rooms/test/'.fake()->uuid().'.jpg',
            'original_name' => fake()->word().'.jpg',
            'order' => 0,
        ];
    }
}
