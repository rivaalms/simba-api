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
}
