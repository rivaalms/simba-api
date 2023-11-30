<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
   /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
   public function definition(): array
   {

      $user_id = mt_rand(1, 30);
      $data_id = mt_rand(1, 100);
      $message = fake()->text();
      $reply_to = null;

      return compact('user_id', 'data_id', 'reply_to', 'message');
   }
}
