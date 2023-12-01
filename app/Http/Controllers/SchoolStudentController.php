<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSchoolStudentRequest;
use App\Models\SchoolStudent;
use Illuminate\Http\Request;

class SchoolStudentController extends Controller
{
   public function getSchoolStudents (Request $request) {
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
}
