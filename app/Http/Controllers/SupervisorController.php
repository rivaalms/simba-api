<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;

class SupervisorController extends Controller
{
   public function getSupervisorOptions() {
      $supervisors = Supervisor::select('id')->distinct('id')->get();
      $data = [];

      foreach ($supervisors as $s) {
         array_push($data, [
            'label' => $s->user->name,
            'value' => $s->id
         ]);
      }

      return $this->apiResponse($data);
   }
}
