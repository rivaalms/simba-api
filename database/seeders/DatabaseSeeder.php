<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

         DataSeeder::class,
      ]);
   }
}
