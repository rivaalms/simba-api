<?php

namespace Database\Seeders;

use App\Models\Redraft;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RedraftSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      Redraft::factory(50)->create();
   }
}
