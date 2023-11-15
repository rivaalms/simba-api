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
}
