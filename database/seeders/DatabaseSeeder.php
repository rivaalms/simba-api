<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Data;
use App\Models\User;
use App\Models\School;
use App\Models\Officer;
use App\Models\DataType;
use App\Models\DataStatus;
use App\Models\SchoolType;
use App\Models\Supervisor;
use App\Models\DataCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
         'userable_id' => null
      ]);

      SchoolType::create([
         'name' => 'SMP'
      ]);
      SchoolType::create([
         'name' => 'SD'
      ]);

      School::factory(2)->create();

      DataCategory::create([
         'name' => 'ARKAS',
         'slug' => 'arkas'
      ]);
      DataCategory::create([
         'name' => 'Laporan Keadaan Sekolah',
         'slug' => 'laporan-keadaan-sekolah'
      ]);
      DataCategory::create([
         'name' => 'Dokumen Kurikulum',
         'slug' => 'dokumen-kurikulum'
      ]);
      DataCategory::create([
         'name' => 'ANBK',
         'slug' => 'anbk'
      ]);
      DataCategory::create([
         'name' => 'SKP',
         'slug' => 'skp'
      ]);
      DataCategory::create([
         'name' => 'Lain-lain',
         'slug' => 'lain-lain'
      ]);

      DataStatus::create([
         'name' => 'Sedang diverifikasi'
      ]);
      DataStatus::create([
         'name' => 'Terverifikasi'
      ]);
      DataStatus::create([
         'name' => 'Revisi'
      ]);
      DataStatus::create([
         'name' => 'Verifikasi revisi'
      ]);

      $dataTypes = [
         ['ERKAS', 'erkas', '1'],
         ['SPTJM', 'sptjm', '1'],
         ['BKU', 'bku', '1'],
         ['BBKU', 'bbku', '1'],
         ['BBB', 'bbb', '1'],
         ['BBP', 'bbp', '1'],
         ['Modal', 'modal', '1'],
         ['K7', 'k7', '1'],
         ['RPK', 'rpk', '1'],
         ['Kwitansi', 'kwitansi', '1'],
         ['Nota', 'nota', '1'],
         ['R7', 'r7', '2'],
         ['R10', 'r10', '2'],
         ['Model 8355', 'model-8355', '2'],
         ['Sarana Prasarana Sekolah', 'sarana-prasarana-sekolah', '2'],
         ['Dokumen 1', 'dokumen-1', '3'],
         ['Dokumen 2', 'dokumen-2', '3'],
         ['Dokumen 3', 'dokumen-3', '3'],
         ['Laporan ANBK', 'laporan-anbk', '4'],
         ['Daftar Hadir ANBK', 'daftar-hadir-anbk', '4'],
         ['Berita Acara ANBK', 'berita-acara-anbk', '4'],
         ['SK Panitia ANBK', 'sk-panitia-anbk', '4'],
         ['Dokumen Kegiatan ANBK', 'dokumen-kegiatan-anbk', '4'],
         ['Dokumen SKP', 'dokumen-skp', '5'],
         ['Nilai SKP', 'nilai-skp', '5'],
         ['Ekstra Kulikuler', 'ekstrakulikuler', '6'],
         ['Intra Kulikuler', 'intrakulikuler', '6'],
         ['Proyek Sekolah', 'proyek-sekolah', '6'],
         ['Karya Tulis Siswa', 'karya-tulis-siswa', '6'],
         ['Karya Tulis Guru', 'karya-tulis-guru', '6'],
         ['Nilai Rapor Siswa', 'nilai-rapor-siswa', '6'],
         ['Ujian Sekolah', 'ujian-sekolah', '6'],
         ['Absensi Guru', 'absensi-guru', '6'],
         ['Dokumen Ijazah', 'dokumen-ijazah', '6'],
      ];

      foreach ($dataTypes as $d) {
         DataType::create([
            'name' => $d[0],
            'slug' => $d[1],
            'data_category_id' => $d[2]
         ]);
      }

      Data::factory()->count(500)->create();
   }
}
