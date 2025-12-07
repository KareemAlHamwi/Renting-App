<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Person;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory {
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'phone_number' => fake()->phoneNumber(),
            'username' => fake()->userName(),
            'password' => static::$password ??= Hash::make('password'),
            'verified_at' => null,
            'role' => 0,
            'person_id' => Person::factory(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static {
        return $this->state(fn(array $attributes) => [
            'verified_at' => null,
        ]);
    }
}
