<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use Illuminate\Http\Request;

class ReligionController extends Controller
{
   public function get(Request $request) {
      $religions = Religion::select('id', 'name')->get();
      return $this->apiResponse($religions);
   }
}
