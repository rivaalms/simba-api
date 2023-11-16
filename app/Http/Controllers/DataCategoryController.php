<?php

namespace App\Http\Controllers;

use App\Models\DataCategory;
use Illuminate\Http\Request;

class DataCategoryController extends Controller
{
   public function getOptions() {
      $categories = DataCategory::select('name as label', 'id as value')->get()->toArray();
      return $this->apiResponse($categories);
   }
}
