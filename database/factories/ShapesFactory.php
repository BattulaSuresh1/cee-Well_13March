<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShapesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
