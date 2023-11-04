<?php

namespace App\Http\Controllers;

use App\Models\DataStatus;
use Illuminate\Http\Request;

class DataStatusController extends Controller
{
   public function getDataStatusOptions () {
      $status = DataStatus::select('name as label', 'id as value')->get();
      return $this->apiResponse($status);
   }
}
