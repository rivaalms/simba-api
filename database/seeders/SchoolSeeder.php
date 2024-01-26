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
      $customSchool = School::factory()->create([
         'school_type_id' => 1
      ]);

      User::factory()->create([
         'name' => 'SMPN 1 Sintang',
         'email' => 'smpn1stg@example.com',
         'userable_type' => self::MORPH_ALIAS,
         'userable_id' => $customSchool->id
      ]);

      School::factory(10)->create();
      $school = School::select('id')->where('id', '!=', 1)->distinct('id')->get();

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
