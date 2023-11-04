<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
   public function getData(Request $request) {
      $data = Data::filter(request(['school', 'type', 'category', 'status', 'year']))->latest()->paginate($request->per_page)->withQueryString();

      return $this->apiResponse($data);
   }
}
