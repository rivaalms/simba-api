<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
   public function getSchool() {
      $user = new User;
      $schools = $user->where('userable_type', $user->userableType['school'])->get();
      foreach($schools as $s) {
         $s->userable;
      }
      return $this->apiResponse($schools);
   }
}
