<?php

namespace App\Http\Controllers;

abstract class Controller
{
   public function apiResponse($data, $message = null, $status = 200) {
      $success = ($status > 199 && $status < 300);

      return response()->json(compact('success', 'message', 'data'), $status);
   }
}
