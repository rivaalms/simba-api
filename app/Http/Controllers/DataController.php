<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;

class DataController extends Controller
{
   public function getData(Request $request) {
      $data = Data::paginate(10)->withQueryString();

      foreach ($data->items() as $d) {
         $d->school->name = $d->school->users[0]->name;
         $d->school->email = $d->school->users[0]->email;
         unset($d->school->users);
      }
      return $this->apiResponse($data);
   }
}
