<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DataFactory extends Factory
{
   public const YEARS = ['2020', '2021', '2022', '2023', '2024'];
   /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
   public function definition(): array
   {
      return [
         'data_type_id' => mt_rand(1, 34),
         'school_id' => mt_rand(1, 11),
         'path' => Crypt::encryptString(time() . '.' . fake()->fileExtension()),
         'year' => self::generateYear(self::YEARS, fake()->randomDigit()),
         'data_status_id' => mt_rand(1, 4)
      ];
   }

   public function generateYear(Array $yearArr, int $index) {
      if (($index) >= (count($yearArr) - 1)) {
         $index = count($yearArr) - 2;
      }

      return "{$yearArr[$index]}-{$yearArr[$index+1]}";
   }
}
