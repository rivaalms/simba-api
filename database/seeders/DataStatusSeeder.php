<?php

namespace Database\Seeders;

use App\Models\DataStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataStatusSeeder extends Seeder
{
   private $status = [
      ['name' => 'Sedang diverifikasi'],
      ['name' => 'Terverifikasi'],
      ['name' => 'Revisi'],
      ['name' => 'Verifikasi revisi']
   ];

   public function run(): void
   {
      foreach ($this->status as $d) {
         DataStatus::create($d);
      }
   }
}
