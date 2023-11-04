<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;

class SchoolController extends Controller
{
   public function getSchool() {
      $schools = User::where('userable_type', 'school')->get();
      foreach ($schools as $s) $s->userable;
      return $this->apiResponse($schools);
   }

   public function getSchoolOptions() {
      $schools = School::select('id')->with('user:id,name,userable_type,userable_id')->get();
      $data = [];

      foreach ($schools as $s) {
         array_push($data, [
            'label' => $s->user->name,
            'value' => $s->id
         ]);
      }
      return $this->apiResponse($data);
   }
}
