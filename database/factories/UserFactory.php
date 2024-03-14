<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
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
         'name' => fake()->name(),
         'email' => fake()->unique()->safeEmail(),
         'password' => Hash::make('password'),
         'profile_picture' => env('APP_URL') . "/profile-pictures/sample.png"
      ];
   }

   /**
    * Indicate that the model's email address should be unverified.
    */
   public function unverified(): static
   {
      return $this->state(fn (array $attributes) => [
         'email_verified_at' => null,
      ]);
   }
}
