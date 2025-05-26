<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first' => $this->faker->firstName,
            'last' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'active' => $this->faker->boolean(90), // 90% chance of being active
            'address' => $this->faker->address,
            'phoneNumber' => $this->faker->phoneNumber,
            'role' => $this->faker->randomElement(['admin', 'user']),
            'email_verified_at' => $this->faker->optional()->dateTimeBetween('-1 years', 'now'),
            'password' => Hash::make('password'), // Default password
            'confirm_password' => Hash::make('password'), // Matching password
            'remember_token' => Str::random(10),
        ];
    }
}
