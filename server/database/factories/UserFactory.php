<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->name(),
            'lastname' => fake()->name(),
            'phone_prefix' => '+420',
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'street' => fake()->streetAddress(),
            'postcode' => str_replace(' ', '', fake()->postcode()),
            'city' => fake()->city(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'verification_code' => Str::random(64),
            'invitation_code' => Str::upper(Str::random(6)),
            'active' => 1,
            'verified' => 1,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
