<?php

namespace Database\Seeders;

use App\Models\DataCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataCategorySeeder extends Seeder
{
   private $categories = [
      ['name' => 'ARKAS', 'slug' => 'arkas'],
      ['name' => 'Laporan Keadaan Sekolah', 'slug' => 'laporan-keadaan-sekolah'],
      ['name' => 'Dokumen Kurikulum', 'slug' => 'dokumen-kurikulum'],
      ['name' => 'ANBK', 'slug' => 'anbk'],
      ['name' => 'SKP', 'slug' => 'skp'],
      ['name' => 'Lain-lain', 'slug' => 'lain-lain'],
   ];

   public function run(): void
   {
      foreach ($this->categories as $d) {
         DataCategory::create($d);
      }
   }
}
