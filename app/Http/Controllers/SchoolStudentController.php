<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSchoolStudentRequest;
use App\Models\Religion;
use App\Models\School;
use App\Models\SchoolStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SchoolStudentController extends Controller
{
   public function getSchoolStudents (Request $request) {
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

      $students = SchoolStudent::where('school_id', $request->school_id)->filter(request(['year']))->orderBy('updated_at', 'desc')->get();
      return $this->apiResponse($students);
   }

   public function create(FormSchoolStudentRequest $request) {
      $_students = $request->validated();
      $students = SchoolStudent::where('school_id', $_students['school_id'])
         ->where('year', 'like', '%'.$_students['year'].'%')
         ->where('grade', $_students['grade'])
         ->where('religion_id', $_students['religion_id'])
         ->first();

      if ($students) $students->update($_students);
      else SchoolStudent::create($_students);

      return $this->apiResponse(true, 'Data siswa berhasil ditambahkan');
   }

   public function getSchoolStudentsGrowth(Request $request, int $id) {
      $startYear = $request->start_year;
      $endYear = $request->end_year;

      $years = array_map(
         function ($i) {
            return implode('-', [$i, $i + 1]);
         },
         range($startYear, $endYear)
      );

      sort($years);

      $students = SchoolStudent::where('school_id', $id)->whereBetween('year', [$years[0], last($years)])->orderBy('updated_at', 'desc')->get();

      foreach ($years as $y) {
         $count = $students->where('year', $y)->sum('count');
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

   public function countStudents(Request $request)
   {
      $user = request()->user();
      $year = $request->year;

      $data = SchoolStudent::where('school_id', $user->userable_id)->where('year', $year)->get();

      $count = $data->map(fn ($item) => $item['count'])->reduce(fn ($sum, $current) => $sum + $current);

      return $this->apiResponse($count);
   }
}
