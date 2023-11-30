<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSchoolTeacherRequest;
use App\Models\SchoolTeacher;
use Illuminate\Http\Request;

class SchoolTeacherController extends Controller
{
   public function getSchoolTeachers (Request $request) {
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
}
