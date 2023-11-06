<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
   private $religions = [
      ['name' => 'Islam'],
      ['name' => 'Kristen'],
      ['name' => 'Katolik'],
      ['name' => 'Hindu'],
      ['name' => 'Buddha'],
      ['name' => 'Konghucu'],
   ];

   public function run(): void
   {
      foreach ($this->religions as $r) {
         Religion::create($r);
      }
   }
}
