<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DataFactory extends Factory
{
   /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
   public function definition(): array
   {
      return [
         'data_type_id' => mt_rand(1, 34),
         'school_id' => mt_rand(1, 2),
         'path' => $this->faker->sentence,
         'year' => '2022-2023',
         'data_status_id' => mt_rand(1, 4)
      ];
   }
}
