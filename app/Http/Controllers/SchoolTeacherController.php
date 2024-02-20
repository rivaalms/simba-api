<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\School;
use Illuminate\Http\Request;
use App\Models\SchoolTeacher;
use App\Http\Requests\FormSchoolTeacherRequest;

class SchoolTeacherController extends Controller
{
   public function getSchoolTeachers (Request $request) {
      $user = $request->user();

      if ($user->userable_type == 'school' && $request->school_id != $user->userable_id) {
         return $this->apiResponse(null, 'Aksi dilarang', 403);
      }

      if ($user->userable_type == 'supervisor') {
         $school = School::select('id', 'supervisor_id')->where('id', $request->school_id)->first();
         if ($school->supervisor_id != $user->userable_id) {
            return $this->apiResponse(null, 'Aksi dilarang', 403);
         }
      }

      $teachers = SchoolTeacher::where('school_id', $request->school_id)->filter(request(['year']))->orderBy('updated_at', 'desc')->get();
      return $this->apiResponse($teachers);
   }

   public function create(FormSchoolTeacherRequest $request) {
      $_teachers = $request->validated();
      $teachers = SchoolTeacher::where('school_id', $_teachers['school_id'])
         ->where('subject_id', $_teachers['subject_id'])
         ->where('year', 'like', '%'.$_teachers['year'].'%')
         ->first();

      if ($teachers) $teachers->update($_teachers);
      else SchoolTeacher::create($_teachers);

      return $this->apiResponse(true, 'Data guru berhasil ditambahkan');
   }

   public function getSchoolTeachersGrowth(Request $request, int $id) {
      $startYear = $request->start_year;
      $endYear = $request->end_year;

      $years = array_map(
         function ($i) {
            return implode('-', [$i, $i + 1]);
         },
         range($startYear, $endYear)
      );

      sort($years);

      $teachers = SchoolTeacher::where('school_id', $id)->whereBetween('year', [$years[0], last($years)])->orderBy('updated_at', 'desc')->get();

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

      return $this->apiResponse($result);
   }

   public function countTeachers(Request $request)
   {
      $user = request()->user();
      $year = $request->year;

      $data = SchoolTeacher::where('school_id', $user->userable_id)->where('year', $year)->get();

      $count = $data->map(fn ($item) => $item['count'])->reduce(fn ($sum, $current) => $sum + $current);

      return $this->apiResponse($count);
   }
}
