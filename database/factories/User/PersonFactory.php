<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;

use function Symfony\Component\Clock\now;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Person>
 */
class PersonFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->firstName(),
            'birthdate' => fake()->date(),
            'personal_photo' => 'test',
            'id_photo' => 'test',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
