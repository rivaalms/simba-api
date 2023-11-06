<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use App\Models\SchoolStudent;
use App\Models\SchoolTeacher;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SchoolSeeder extends Seeder
{
   private const MORPH_ALIAS = 'school';

   public function run(): void
   {
      School::factory(10)->create();
      $school = School::select('id')->distinct('id')->get();

      foreach ($school as $s) {
         User::factory()->create([
            'userable_type' => self::MORPH_ALIAS,
            'userable_id' => $s->id
         ]);
      }

      SchoolStudent::factory(10)->create();
      SchoolTeacher::factory(10)->create();
   }
}
