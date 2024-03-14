<?php

namespace Database\Factories;

use App\Models\Religion;
use App\Models\School;
use Database\Factories\DataFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolStudent>
 */
class SchoolStudentFactory extends Factory
{
   /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
   public function definition(): array
   {
      $df = new DataFactory;

      $school_id = fake()->numberBetween(1, 10);
      $school = School::find($school_id);
      $year = $df->generateYear($df::YEARS, fake()->randomDigit());

      if ($school->school_type_id === 2) $grade = fake()->numberBetween(1, 6);
      else $grade = fake()->numberBetween(7, 9);

      $religion = Religion::count();
      $religion_id = fake()->numberBetween(1, $religion);
      $count = fake()->numberBetween(0, 100);

      return compact('school_id', 'year', 'grade', 'religion_id', 'count');
   }
}
