<?php

namespace Database\Seeders;

use App\Models\Officer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficerSeeder extends Seeder
{
   private const MORPH_ALIAS = 'officer';

   public function run(): void
   {
      $custom = Officer::factory()->create();
      User::factory()->create([
         'name' => 'Officer',
         'email' => 'officer@example.com',
         'userable_type' => self::MORPH_ALIAS,
         'userable_id' => $custom->id
      ]);

      Officer::factory(10)->create();

      $officers = Officer::select('id')->where('id', '!=', $custom->id)->distinct('id')->get();

      foreach ($officers as $o) {
         User::factory()->create([
            'userable_type' => self::MORPH_ALIAS,
            'userable_id' => $o->id
         ]);
      }
   }
}
