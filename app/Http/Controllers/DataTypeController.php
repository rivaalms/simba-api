<?php

namespace App\Http\Controllers;

use App\Models\DataType;
use Illuminate\Http\Request;

class DataTypeController extends Controller
{
   public function getOptions(Request $request) {
      $types = DataType::select('name as label', 'id as value')->where('data_category_id', $request->category)->get();
      return $this->apiResponse($types);
   }
}
