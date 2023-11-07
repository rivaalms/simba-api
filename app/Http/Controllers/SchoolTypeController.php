<?php

namespace App\Http\Controllers;

use App\Models\SchoolType;

class SchoolTypeController extends Controller
{
   public function getSchoolTypeOptions() {
      $data = SchoolType::select('name as label', 'id as value')->distinct('id')->get()->toArray();
      return $this->apiResponse($data);
   }
}
