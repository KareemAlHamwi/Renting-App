<?php

namespace Database\Factories;

use App\Models\User\User;
use App\Models\Property\Governorate;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'title' => fake()->sentence(3), // Short, realistic title
            'description' => fake()->paragraph(3),

            'governorate_id' =>  Governorate::inRandomOrder()->value('id'),

            'address' => fake()->streetAddress(),

            'rent' => fake()->numberBetween(150, 1500), // monthly rent

            'overall_reviews' => fake()->randomFloat(1, 2.5, 5.0),

            'reviewers_number' => fake()->numberBetween(1, 200),

            'verified_at' => now(), // all verified

            'user_id' => User::inRandomOrder()->value('id'),
        ];
    }
}
