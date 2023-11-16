<?php

namespace App\Http\Controllers;

use App\Models\SchoolTeacher;
use Illuminate\Http\Request;

class SchoolTeacherController extends Controller
{
   public function getSchoolTeachers (Request $request) {
      $teachers = SchoolTeacher::where('school_id', $request->school_id)->filter(request(['year']))->orderBy('updated_at', 'desc')->get();
      return $this->apiResponse($teachers);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'school_id' => 'required|integer|numeric',
         'year' => 'required',
         'subject_id' => 'required|integer|numeric',
         'count' => 'required|integer|numeric'
      ]);

      $teachers = SchoolTeacher::where('school_id', $validator['school_id'])->where('subject_id', $validator['subject_id'])->where('year', 'like', '%'.$validator['year'].'%')->first();

      if ($teachers) $teachers->update($validator);
      else SchoolTeacher::create($validator);

      return $this->apiResponse(true, 'Data guru berhasil ditambahkan');
   }
}
