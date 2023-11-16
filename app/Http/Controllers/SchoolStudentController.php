<?php

namespace App\Http\Controllers;

use App\Models\SchoolStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolStudentController extends Controller
{
   public function getSchoolStudents (Request $request) {
      $students = SchoolStudent::where('school_id', $request->school_id)->filter(request(['year']))->orderBy('updated_at', 'desc')->get();
      return $this->apiResponse($students);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'school_id' => 'required',
         'year' => 'required',
         'grade' => 'required',
         'religion_id' => 'required',
         'count' => 'required|integer|numeric'
      ]);

      $students = SchoolStudent::where('school_id', $validator['school_id'])->where('year', 'like', '%'.$validator['year'].'%')->where('grade', $validator['grade'])->first();

      if ($students) $students->update($validator);
      else SchoolStudent::create($validator);

      return $this->apiResponse(true, 'Data siswa berhasil ditambahkan');
   }
}
