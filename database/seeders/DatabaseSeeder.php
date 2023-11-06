<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Data;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    */
   public function run(): void
   {
      User::factory()->create([
         'name' => 'Admin',
         'email' => 'admin@example.com',
         'userable_type' => null,
         'userable_id' => null,
      ]);

      $this->call([
         SchoolTypeSeeder::class,
         DataStatusSeeder::class,
         DataCategorySeeder::class,
         DataTypeSeeder::class,

         SubjectSeeder::class,
         ReligionSeeder::class,

         SchoolSeeder::class,
         SupervisorSeeder::class,
         OfficerSeeder::class,
      ]);

      Data::factory()->count(100)->create();
   }
}
