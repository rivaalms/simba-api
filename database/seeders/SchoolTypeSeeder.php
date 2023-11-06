<?php

namespace Database\Seeders;

use App\Models\SchoolType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolTypeSeeder extends Seeder
{
   private $types = [
      ['name' => 'SMP'],
      ['name' => 'SD']
   ];

   public function run(): void
   {
      foreach ($this->types as $t) {
         SchoolType::create($t);
      }
   }
}
