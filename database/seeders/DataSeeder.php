<?php

namespace Database\Seeders;

use App\Models\Data;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      Data::factory()->count(20000)->create();
      Comment::factory(50)->create();
   }
}
