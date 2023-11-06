<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
   private $subjects = [
      [
         'name' => 'Matematika',
         'abbreviation' => 'MTK'
      ],
      [
         'name' => 'Bahasa Indonesia',
         'abbreviation' => 'BI'
      ],
      [
         'name' => 'Bahasa Inggris',
         'abbreviation' => 'BING'
      ],
      [
         'name' => 'Ilmu Pengetahuan Alam',
         'abbreviation' => 'IPA'
      ],
      [
         'name' => 'Ilmu Pengetahuan Sosal',
         'abbreviation' => 'IPS'
      ],
      [
         'name' => 'Pendidikan Kewarganegaraan',
         'abbreviation' => 'PKn'
      ],
      [
         'name' => 'Pendidikan Agama Islam',
         'abbreviation' => 'PAI'
      ],
      [
         'name' => 'Pendidikan Agama Kristen',
         'abbreviation' => 'PAKr'
      ],
      [
         'name' => 'Pendidikan Agama Katolik',
         'abbreviation' => 'PAKa'
      ],
      [
         'name' => 'Pendidikan Agama Hindu',
         'abbreviation' => 'PAH'
      ],
      [
         'name' => 'Pendidikan Agama Buddha',
         'abbreviation' => 'PAB'
      ],
      [
         'name' => 'Pendidikan Agama Konghucu',
         'abbreviation' => 'PAKo'
      ],
      [
         'name' => 'Seni Budaya',
         'abbreviation' => 'SB'
      ],
      [
         'name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan',
         'abbreviation' => 'OR'
      ],
      [
         'name' => 'Teknologi Informasi dan Komunikasi',
         'abbreviation' => 'TIK'
      ],
      [
         'name' => 'Bimbingan Konseling',
         'abbreviation' => 'BK'
      ],
      [
         'name' => 'Prakarya dan Kewirausahaan',
         'abbreviation' => 'PKU'
      ]
   ];

   public function run(): void
   {
      foreach ($this->subjects as $s) {
         Subject::create($s);
      }
   }
}
