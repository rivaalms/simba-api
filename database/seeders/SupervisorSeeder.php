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
      Supervisor::factory(10)->create();

      $supervisor = Supervisor::select('id')->get();

      foreach ($supervisor as $s) {
         User::factory()->create([
            'userable_type' => self::MORPH_ALIAS,
            'userable_id' => $s->id
         ]);
      }
   }
}
