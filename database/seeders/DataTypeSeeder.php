<?php

namespace Database\Seeders;

use App\Models\DataType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataTypeSeeder extends Seeder
{
   private $types = [
      [
         'name' => 'ERKAS',
         'slug' => 'erkas',
         'data_category_id' => '1'
      ],
      [
         'name' => 'SPTJM',
         'slug' => 'sptjm',
         'data_category_id' => '1'
      ],
      [
         'name' => 'BKU',
         'slug' => 'bku',
         'data_category_id' => '1'
      ],
      [
         'name' => 'BBKU',
         'slug' => 'bbku',
         'data_category_id' => '1'
      ],
      [
         'name' => 'BBB',
         'slug' => 'bbb',
         'data_category_id' => '1'
      ],
      [
         'name' => 'BBP',
         'slug' => 'bbp',
         'data_category_id' => '1'
      ],
      [
         'name' => 'Modal',
         'slug' => 'modal',
         'data_category_id' => '1'
      ],
      [
         'name' => 'K7',
         'slug' => 'k7',
         'data_category_id' => '1'
      ],
      [
         'name' => 'RPK',
         'slug' => 'rpk',
         'data_category_id' => '1'
      ],
      [
         'name' => 'Kwitansi',
         'slug' => 'kwitansi',
         'data_category_id' => '1'
      ],
      [
         'name' => 'Nota',
         'slug' => 'nota',
         'data_category_id' => '1'
      ],
      [
         'name' => 'R7',
         'slug' => 'r7',
         'data_category_id' => '2'
      ],
      [
         'name' => 'R10',
         'slug' => 'r10',
         'data_category_id' => '2'
      ],
      [
         'name' => 'Model 8355',
         'slug' => 'model-8355',
         'data_category_id' => '2'
      ],
      [
         'name' => 'Sarana Prasarana Sekolah',
         'slug' => 'sarana-prasarana-sekolah',
         'data_category_id' => '2'
      ],
      [
         'name' => 'Dokumen 1',
         'slug' => 'dokumen-1',
         'data_category_id' => '3'
      ],
      [
         'name' => 'Dokumen 2',
         'slug' => 'dokumen-2',
         'data_category_id' => '3'
      ],
      [
         'name' => 'Dokumen 3',
         'slug' => 'dokumen-3',
         'data_category_id' => '3'
      ],
      [
         'name' => 'Laporan ANBK',
         'slug' => 'laporan-anbk',
         'data_category_id' => '4'
      ],
      [
         'name' => 'Daftar Hadir ANBK',
         'slug' => 'daftar-hadir-anbk',
         'data_category_id' => '4'
      ],
      [
         'name' => 'Berita Acara ANBK',
         'slug' => 'berita-acara-anbk',
         'data_category_id' => '4'
      ],
      [
         'name' => 'SK Panitia ANBK',
         'slug' => 'sk-panitia-anbk',
         'data_category_id' => '4'
      ],
      [
         'name' => 'Dokumen Kegiatan ANBK',
         'slug' => 'dokumen-kegiatan-anbk',
         'data_category_id' => '4'
      ],
      [
         'name' => 'Dokumen SKP',
         'slug' => 'dokumen-skp',
         'data_category_id' => '5'
      ],
      [
         'name' => 'Nilai SKP',
         'slug' => 'nilai-skp',
         'data_category_id' => '5'
      ],
      [
         'name' => 'Ekstra Kulikuler',
         'slug' => 'ekstrakulikuler',
         'data_category_id' => '6'
      ],
      [
         'name' => 'Intra Kulikuler',
         'slug' => 'intrakulikuler',
         'data_category_id' => '6'
      ],
      [
         'name' => 'Proyek Sekolah',
         'slug' => 'proyek-sekolah',
         'data_category_id' => '6'
      ],
      [
         'name' => 'Karya Tulis Siswa',
         'slug' => 'karya-tulis-siswa',
         'data_category_id' => '6'
      ],
      [
         'name' => 'Karya Tulis Guru',
         'slug' => 'karya-tulis-guru',
         'data_category_id' => '6'
      ],
      [
         'name' => 'Nilai Rapor Siswa',
         'slug' => 'nilai-rapor-siswa',
         'data_category_id' => '6'
      ],
      [
         'name' => 'Ujian Sekolah',
         'slug' => 'ujian-sekolah',
         'data_category_id' => '6'
      ],
      [
         'name' => 'Absensi Guru',
         'slug' => 'absensi-guru',
         'data_category_id' => '6'
      ],
      [
         'name' => 'Dokumen Ijazah',
         'slug' => 'dokumen-ijazah',
         'data_category_id' => '6'
      ],
   ];

   public function run(): void
   {
      foreach ($this->types as $d) {
         DataType::create($d);
      }
   }
}
