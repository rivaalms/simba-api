<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
   public function getSchool(Request $request) {
      $schools = School::filter(request(['type', 'supervisor']))->latest()->paginate($request->per_page)->withQueryString();

      return $this->apiResponse($schools);
   }

   public function getSchoolOptions() {
      $schools = School::select('id')->get();
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
