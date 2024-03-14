<?php

namespace Database\Seeders;

use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupervisorSeeder extends Seeder
{
   private const MORPH_ALIAS = 'supervisor';

   public function run(): void
   {
      $custom = Supervisor::factory()->create();
      User::factory()->create([
         'name' => 'Riva Almero',
         'email' => 'riva@example.com',
         'userable_type' => self::MORPH_ALIAS,
         'userable_id' => $custom->id
      ]);

      Supervisor::factory(10)->create();
      $supervisor = Supervisor::select('id')->where('id', '!=', $custom->id)->distinct()->get();

      foreach ($supervisor as $s) {
         User::factory()->create([
            'userable_type' => self::MORPH_ALIAS,
            'userable_id' => $s->id
         ]);
      }
   }
}
