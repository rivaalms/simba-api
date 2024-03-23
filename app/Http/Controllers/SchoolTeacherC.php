<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolTeacherReq;
use App\Models\School;
use App\Models\SchoolTeacher;
use App\Traits\CheckUserable;
use Illuminate\Http\Request;

class SchoolTeacherC extends Controller
{
   use CheckUserable;

   public function get(Request $request) {
      $user = $request->user();

      if ($this->isSchool($user) && $request->school_id != $user->userable_id) {
         return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
      }

      if ($this->isSupervisor($user)) {
         $school = School::select('id', 'supervisor_id')
            ->where('id', $request->school_id)
            ->first();
         if ($school->supervisor_id != $user->userable_id) {
            return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
         }
      }

      $teachers = SchoolTeacher::where('school_id', $request->school_id)
         ->filter(request(['year']))
         ->orderBy('updated_at', 'desc')
         ->get();

      return parent::apiResponse($teachers);
   }

   public function create(SchoolTeacherReq $request) {
      $searchArray = $request->safe()->except('count');
      $countArray = $request->safe()->only('count');

      SchoolTeacher::updateOrCreate($searchArray, $countArray);
      return parent::apiResponse(true, 'Data guru berhasil diperbarui');
   }

   public function growth(Request $request, int $id) {
      $startYear = $request->start_year;
      $endYear = $request->end_year;

      $years = array_map(
         function ($i) {
            return implode('-', [$i, $i + 1]);
         },
         range($startYear, $endYear)
      );

      sort($years);

      $teachers = SchoolTeacher::where('school_id', $id)
         ->whereBetween('year', [$years[0], last($years)])
         ->orderBy('updated_at', 'desc')
         ->get();

      foreach ($years as $y) {
         $count = $teachers->where('year', $y)->sum('count');
         $data[$y] = $count;
      }

      $result = array_map(
         function ($year, $total) {
            return ['year' => $year, 'total' => $total];
         },
         array_keys($data),
         $data
      );

      return parent::apiResponse($result);
   }

   public function count(Request $request) {
      $user = $request->user();
      $year = $request->year;

      $data = SchoolTeacher::where('school_id', $user->userable_id)
         ->where('year', $year)
         ->get();

      $count = $data->map(fn ($item) => $item['count'])
         ->reduce(fn ($sum, $current) => $sum + $current);

      return parent::apiResponse($count);
   }
}
