<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolTeacher>
 */
class SchoolTeacherFactory extends Factory
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

      $subject = Subject::count();
      $subject_id = fake()->numberBetween(1, $subject);
      $count = fake()->numberBetween(0, 100);

      return compact('school_id', 'year', 'subject_id', 'count');
   }
}
