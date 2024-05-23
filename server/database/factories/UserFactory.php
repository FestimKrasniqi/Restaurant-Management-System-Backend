<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first' => $this->faker->firstName,
            'last' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'active' => true,
            'address' => $this->faker->address,
            'phoneNumber' => $this->faker->phoneNumber,
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'confirm_password' => 'password', 
            'remember_token' => Str::random(10),
        ];
    }
}
